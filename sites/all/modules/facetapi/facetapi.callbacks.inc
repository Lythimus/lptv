<?php

/**
 * @file
 * Callbacks referenced in hook implementations.
 */

/**
 * Map callback for node types.
 */
function facetapi_map_bundle(array $values) {
  $names = node_type_get_names();
  return array_intersect_key($names, array_flip($values));
}

/**
 * Map callback for node authors.
 */
function facetapi_map_author(array $values) {
  $result = db_select('users', 'u')
    ->fields('u', array('uid', 'name'))
    ->condition('uid', $values, 'IN')
    ->execute();

  $map = array();
  while ($record = $result->fetchAssoc()) {
    $map[$record['uid']] = $record['name'];
  }
  if (isset($map[0])) {
    $map[0] = variable_get('anonymous', t('Anonymous'));
  }
  return $map;
}

/**
 * Map callback for languages.
 */
function facetapi_map_language(array $values) {
  $map = array();
  $language_list = language_list();
  foreach ($values as $language) {
    if (isset($language_list[$language])) {
      $map[$language] = $language_list[$language]->name;
    }
    else {
      $map[$language] = t('Language neutral');
    }
  }
  return $map;
}

/**
 * Maps date ranges to human readable dates.
 *
 * @param $ranges
 *   An array containing the date ranges.
 *
 * @return
 *   An array mapping the ranges to nice display dates.
 */
function facetapi_map_date(array $values) {
  $map = array();
  foreach ($values as $value) {
    $range = explode(' TO ', trim($value, '{[]}'));
    if (2 == count($range)) {
      $gap = facetapi_get_date_gap($range[0], $range[1]);
      $map[$value] = facetapi_format_date($range[0], $gap);
    }
  }
  return $map;
}

/**
 * Callback that returns the minimum date in the node table.
 *
 * @param $facet
 *   An array containing the facet definition.
 *
 * @return
 *   The minimum time in the node table.
 *
 * @todo Cache this value.
 */
function facetapi_get_min_date(array $facet) {
  $query = db_select('node', 'n')->condition('status', 1);
  $query->addExpression('MIN(' . $facet['name'] . ')', 'max');
  return $query->execute()->fetch()->max;
}

/**
 * Callback that returns the minimum value in the node table.
 *
 * @param $facet
 *   An array containing the facet definition.
 *
 * @return
 *   The minimum time in the node table.
 *
 * @todo Cache this value.
 */
function facetapi_get_max_date(array $facet) {
  $query = db_select('node', 'n')->condition('status', 1);
  $query->addExpression('MAX(' . $facet['name'] . ')', 'max');
  return $query->execute()->fetch()->max;
}

/**
 * Map callback for taxonomy terms.
 */
function facetapi_map_taxonomy_terms(array $values) {
  $map = array();
  $terms = taxonomy_term_load_multiple($values);
  foreach ($terms as $term) {
    $map[$term->tid] = $term->name;
  }
  return $map;
}

/**
 * Gets parent information for taxonomy terms.
 *
 * @param array $values
 *   An array containing the term ids.
 *
 * @return
 *   An associative array keyed by term ID to parent ID.
 */
function facetapi_get_taxonomy_hierarchy(array $values) {
  $result = db_select('taxonomy_term_hierarchy', 'th')
    ->fields('th', array('tid', 'parent'))
    ->condition('th.parent', '0', '>')
    ->condition(db_or()
      ->condition('th.tid', $values, 'IN')
      ->condition('th.parent', $values, 'IN')
    )
    ->execute();

  $parents = array();
  foreach ($result as $record) {
    $parents[$record->tid][] = $record->parent;
  }
  return $parents;
}

