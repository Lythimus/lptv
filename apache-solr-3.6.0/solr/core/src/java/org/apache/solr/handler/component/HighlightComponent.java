/**
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

package org.apache.solr.handler.component;

import org.apache.lucene.queryParser.ParseException;
import org.apache.lucene.search.Query;
import org.apache.solr.common.SolrException;
import org.apache.solr.common.params.CommonParams;
import org.apache.solr.common.params.HighlightParams;
import org.apache.solr.common.params.SolrParams;
import org.apache.solr.common.util.NamedList;
import org.apache.solr.common.util.SimpleOrderedMap;
import org.apache.solr.highlight.SolrHighlighter;
import org.apache.solr.highlight.DefaultSolrHighlighter;
import org.apache.solr.request.SolrQueryRequest;
import org.apache.solr.search.QParser;
import org.apache.solr.util.plugin.PluginInfoInitialized;
import org.apache.solr.util.plugin.SolrCoreAware;
import org.apache.solr.core.PluginInfo;
import org.apache.solr.core.SolrCore;

import java.io.IOException;
import java.net.URL;
import java.util.Map;
import java.util.List;

/**
 * TODO!
 *
 * @version $Id: HighlightComponent.java 1198785 2011-11-07 15:43:23Z koji $
 * @since solr 1.3
 */
public class HighlightComponent extends SearchComponent implements PluginInfoInitialized, SolrCoreAware
{
  public static final String COMPONENT_NAME = "highlight";
  private PluginInfo info = PluginInfo.EMPTY_INFO;
  private SolrHighlighter highlighter;


  public void init(PluginInfo info) {
    this.info = info;
  }

  @Override
  public void prepare(ResponseBuilder rb) throws IOException {
    SolrParams params = rb.req.getParams();
    rb.doHighlights = highlighter.isHighlightingEnabled(params);
    if(rb.doHighlights){
      String hlq = params.get(HighlightParams.Q);
      if(hlq != null){
        try {
          QParser parser = QParser.getParser(hlq, null, rb.req);
          rb.setHighlightQuery(parser.getHighlightQuery());
        } catch (ParseException e) {
          throw new SolrException(SolrException.ErrorCode.BAD_REQUEST, e);
        }
      }
    }
  }

  public void inform(SolrCore core) {
    List<PluginInfo> children = info.getChildren("highlighting");
    if(children.isEmpty()) {
      PluginInfo pluginInfo = core.getSolrConfig().getPluginInfo(SolrHighlighter.class.getName()); //TODO deprecated configuration remove later
      if (pluginInfo != null) {
        highlighter = core.createInitInstance(pluginInfo, SolrHighlighter.class, null, DefaultSolrHighlighter.class.getName());
        highlighter.initalize(core.getSolrConfig());
      } else {
        DefaultSolrHighlighter defHighlighter = new DefaultSolrHighlighter(core);
        defHighlighter.init(PluginInfo.EMPTY_INFO);
        highlighter = defHighlighter;
      }
    } else {
      highlighter = core.createInitInstance(children.get(0),SolrHighlighter.class,null, DefaultSolrHighlighter.class.getName());
    }

  }

  @Override
  public void process(ResponseBuilder rb) throws IOException {
    if (rb.doHighlights) {
      SolrQueryRequest req = rb.req;
      SolrParams params = req.getParams();

      String[] defaultHighlightFields;  //TODO: get from builder by default?

      if (rb.getQparser() != null) {
        defaultHighlightFields = rb.getQparser().getDefaultHighlightFields();
      } else {
        defaultHighlightFields = params.getParams(CommonParams.DF);
      }
      
      Query highlightQuery = rb.getHighlightQuery();
      if(highlightQuery==null) {
        if (rb.getQparser() != null) {
          try {
            highlightQuery = rb.getQparser().getHighlightQuery();
            rb.setHighlightQuery( highlightQuery );
          } catch (Exception e) {
            throw new SolrException(SolrException.ErrorCode.BAD_REQUEST, e);
          }
        } else {
          highlightQuery = rb.getQuery();
          rb.setHighlightQuery( highlightQuery );
        }
      }
      
      if(highlightQuery != null) {
        boolean rewrite = !(Boolean.valueOf(params.get(HighlightParams.USE_PHRASE_HIGHLIGHTER, "true")) &&
            Boolean.valueOf(params.get(HighlightParams.HIGHLIGHT_MULTI_TERM, "true")));
        highlightQuery = rewrite ?  highlightQuery.rewrite(req.getSearcher().getReader()) : highlightQuery;
      }

      // No highlighting if there is no query -- consider q.alt="*:*
      if( highlightQuery != null ) {
        NamedList sumData = highlighter.doHighlighting(
                rb.getResults().docList,
                highlightQuery,
                req, defaultHighlightFields );
        
        if(sumData != null) {
          // TODO ???? add this directly to the response?
          rb.rsp.add("highlighting", sumData);
        }
      }
    }
  }

  @Override
  public void modifyRequest(ResponseBuilder rb, SearchComponent who, ShardRequest sreq) {
    if (!rb.doHighlights) return;

    // Turn on highlighting only only when retrieving fields
    if ((sreq.purpose & ShardRequest.PURPOSE_GET_FIELDS) != 0) {
        sreq.purpose |= ShardRequest.PURPOSE_GET_HIGHLIGHTS;
        // should already be true...
        sreq.params.set(HighlightParams.HIGHLIGHT, "true");      
    } else {
      sreq.params.set(HighlightParams.HIGHLIGHT, "false");      
    }
  }

  @Override
  public void handleResponses(ResponseBuilder rb, ShardRequest sreq) {
  }

  @Override
  public void finishStage(ResponseBuilder rb) {
    if (rb.doHighlights && rb.stage == ResponseBuilder.STAGE_GET_FIELDS) {

      Map.Entry<String, Object>[] arr = new NamedList.NamedListEntry[rb.resultIds.size()];

      // TODO: make a generic routine to do automatic merging of id keyed data
      for (ShardRequest sreq : rb.finished) {
        if ((sreq.purpose & ShardRequest.PURPOSE_GET_HIGHLIGHTS) == 0) continue;
        for (ShardResponse srsp : sreq.responses) {
          NamedList hl = (NamedList)srsp.getSolrResponse().getResponse().get("highlighting");
          for (int i=0; i<hl.size(); i++) {
            String id = hl.getName(i);
            ShardDoc sdoc = rb.resultIds.get(id);
            int idx = sdoc.positionInResponse;
            arr[idx] = new NamedList.NamedListEntry<Object>(id, hl.getVal(i));
          }
        }
      }

      // remove nulls in case not all docs were able to be retrieved
      rb.rsp.add("highlighting", removeNulls(new SimpleOrderedMap(arr)));      
    }
  }


  static NamedList removeNulls(NamedList nl) {
    for (int i=0; i<nl.size(); i++) {
      if (nl.getName(i)==null) {
        NamedList newList = nl instanceof SimpleOrderedMap ? new SimpleOrderedMap() : new NamedList();
        for (int j=0; j<nl.size(); j++) {
          String n = nl.getName(j);
          if (n != null) {
            newList.add(n, nl.getVal(j));
          }
        }
        return newList;
      }
    }
    return nl;
  }

  public SolrHighlighter getHighlighter() {
    return highlighter;
  }
  ////////////////////////////////////////////
  ///  SolrInfoMBean
  ////////////////////////////////////////////
  
  @Override
  public String getDescription() {
    return "Highlighting";
  }
  
  @Override
  public String getVersion() {
    return "$Revision: 1198785 $";
  }
  
  @Override
  public String getSourceId() {
    return "$Id: HighlightComponent.java 1198785 2011-11-07 15:43:23Z koji $";
  }
  
  @Override
  public String getSource() {
    return "$URL: https://svn.apache.org/repos/asf/lucene/dev/branches/branch_3x/solr/core/src/java/org/apache/solr/handler/component/HighlightComponent.java $";
  }
  
  @Override
  public URL[] getDocs() {
    return null;
  }
}
