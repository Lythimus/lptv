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

package org.apache.solr.schema;

import org.apache.lucene.document.Fieldable;
import org.apache.lucene.search.SortField;
import org.apache.solr.response.TextResponseWriter;
import org.apache.solr.response.XMLWriter;
import org.apache.solr.search.QParser;
import org.apache.solr.search.function.ValueSource;

import java.io.IOException;
import java.util.Map;
/**
 * @version $Id: StrField.java 1298427 2012-03-08 15:22:27Z janhoy $
 */
public class StrField extends PrimitiveFieldType {
  @Override
  public SortField getSortField(SchemaField field,boolean reverse) {
    return getStringSort(field,reverse);
  }

  @Override
  public void write(XMLWriter xmlWriter, String name, Fieldable f) throws IOException {
    xmlWriter.writeStr(name, f.stringValue());
  }

  @Override
  public void write(TextResponseWriter writer, String name, Fieldable f) throws IOException {
    writer.writeStr(name, f.stringValue(), true);
  }

  @Override
  public ValueSource getValueSource(SchemaField field, QParser parser) {
    field.checkFieldCacheSource(parser);
    return new StrFieldSource(field.getName());
  }
}