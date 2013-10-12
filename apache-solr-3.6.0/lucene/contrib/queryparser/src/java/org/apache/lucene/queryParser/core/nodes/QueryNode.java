package org.apache.lucene.queryParser.core.nodes;

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

import java.io.Serializable;
import java.util.List;
import java.util.Map;

import org.apache.lucene.queryParser.core.parser.EscapeQuerySyntax;

/**
 * A {@link QueryNode} is a interface implemented by all nodes on a QueryNode
 * tree.
 */
public interface QueryNode extends Serializable {

  /** convert to a query string understood by the query parser */
  // TODO: this interface might be changed in the future
  public CharSequence toQueryString(EscapeQuerySyntax escapeSyntaxParser);

  /** for printing */
  public String toString();

  /** get Children nodes */
  public List<QueryNode> getChildren();

  /** verify if a node is a Leaf node */
  public boolean isLeaf();

  /** verify if a node contains a tag */
  public boolean containsTag(String tagName);
  
  /** verify if a node contains a tag 
   * @deprecated use {@link #containsTag(String)} instead
   */
  @Deprecated
  public boolean containsTag(CharSequence tagName);

  /**
   * @param tagName
   * @return of stored on under that tag name
   */
  public Object getTag(String tagName);
  
  /**
   * @param tagName
   * @return of stored on under that tag name
   * 
   * @deprecated use {@link #getTag(String)} instead
   */
  @Deprecated
  public Object getTag(CharSequence tagName);

  public QueryNode getParent();

  /**
   * Recursive clone the QueryNode tree The tags are not copied to the new tree
   * when you call the cloneTree() method
   * 
   * @return the cloned tree
   * @throws CloneNotSupportedException
   */
  public QueryNode cloneTree() throws CloneNotSupportedException;

  // Below are the methods that can change state of a QueryNode
  // Write Operations (not Thread Safe)

  // add a new child to a non Leaf node
  public void add(QueryNode child);

  public void add(List<QueryNode> children);

  // reset the children of a node
  public void set(List<QueryNode> children);

  /**
   * Associate the specified value with the specified tagName. If the tagName
   * already exists, the old value is replaced. The tagName and value cannot be
   * null. tagName will be converted to lowercase.
   * 
   * @param tagName
   * @param value
   */
  public void setTag(String tagName, Object value);
  
  /**
   * Associate the specified value with the specified tagName. If the tagName
   * already exists, the old value is replaced. The tagName and value cannot be
   * null. tagName will be converted to lowercase.
   * 
   * @param tagName
   * @param value
   * 
   * @deprecated use {@link #setTag(String, Object)} instead
   */
  @Deprecated
  public void setTag(CharSequence tagName, Object value);

  /**
   * Unset a tag. tagName will be converted to lowercase.
   * 
   * @param tagName
   */
  public void unsetTag(String tagName);
  
  /**
   * Unset a tag. tagName will be converted to lowercase.
   * 
   * @param tagName
   * 
   * @deprecated use {@link #unsetTag(String)} instead
   */
  @Deprecated
  public void unsetTag(CharSequence tagName);

  /**
   * Returns a map containing all tags attached to this query node. 
   * 
   * @return a map containing all tags attached to this query node
   * 
   * @deprecated use {@link #getTagMap()}
   */
  @Deprecated
  public Map<CharSequence, Object> getTags();
  
  /**
   * Returns a map containing all tags attached to this query node. 
   * 
   * @return a map containing all tags attached to this query node
   */
  public Map<String, Object> getTagMap();

}
