<?php

/**
 * @file
 * The node specific translation functions and hook implementations.
 */

/**
 * Identifies a content type which has translation support enabled.
 */
define('ENTITY_TRANSLATION_ENABLED', 4);

/**
 * Do not show translation metadata.
 */
define('ENTITY_TRANSLATION_METADATA_HIDE', 0);

/**
 * Add translation metadata to the original authoring information.
 */
define('ENTITY_TRANSLATION_METADATA_SHOW', 1);

/**
 * Replace the original authoring information with translation metadata.
 */
define('ENTITY_TRANSLATION_METADATA_REPLACE', 2);

/**
 * Check if the given entity has node translation enabled.
 */
function entity_translation_node($entity_type, $node) {
  return $entity_type == 'node' && function_exists('translation_supported_type') && translation_supported_type($node->type);
}

/**
 * Implements hook_menu_alter().
 */
function entity_translation_menu_alter(&$items) {
  if (module_exists('i18n_node')) {
    $items['node/%node/translate']['page callback'] = 'entity_translation_overview';
    $items['node/%node/translate']['file'] = 'entity_translation.admin.inc';
    $items['node/%node/translate']['module'] = 'entity_translation';
  }
}

/**
 * Node specific access callback.
 */
function entity_translation_node_tab_access($node) {
  if ($node->language != LANGUAGE_NONE) {
    if (entity_translation_node_supported_type($node->type)) {
      return entity_translation_tab_access('node');
    }
    elseif (entity_translation_node('node', $node)) {
      return _translation_tab_access($node);
    }
  }
  return FALSE;
}

/**
 * Returns whether the given node type has support for translations.
 *
 * @return
 *   Boolean value.
 */
function entity_translation_node_supported_type($type) {
  return variable_get('language_content_type_' . $type, 0) == ENTITY_TRANSLATION_ENABLED;
}

/**
 * Perform alterations on the node edit form.
 *
 * Clean up the language selector to avoid the possibility to change the node
 * language to a value already assigned to an existing translation.
 * Convert the translation update status fieldset into a vartical tab.
 */
function entity_translation_node_alter_form(&$form, $form_state, $handler) {
  $translations = $handler->getTranslations();

  // Disable languages for existing translations, so it is not possible to
  // switch this node to some language which is already in the translation set.
  foreach ($translations->data as $langcode => $translation) {
    if ($langcode != $translations->original) {
      unset($form['language']['#options'][$langcode]);
    }
  }
  if (count($translations->data) > 1) {
    unset($form['language']['#options']['']);
  }

  if (isset($form['translation'])) {
    $form['translation'] += array(
      '#group' => 'additional_settings',
      '#weight' => 100,
      '#attached' => array(
        'js' => array(drupal_get_path('module', 'entity_translation') . '/entity_translation.node-form.js'),
      ),
    );
  }
}

/**
 * Implements hook_node_view().
 *
 * Provide content language switcher links to navigate among node translations.
 */
function entity_translation_node_view($node, $build_mode) {
  if (!empty($node->translations) && drupal_multilingual() && entity_translation_node_supported_type($node->type)) {
    $path = 'node/' . $node->nid;
    $links = language_negotiation_get_switch_links(LANGUAGE_TYPE_CONTENT, $path);

    if (is_object($links) && !empty($links->links)) {
      $handler = entity_translation_get_handler('node', $node);
      $translations = $handler->getTranslations()->data;

      // Remove the link for the current language.
      unset($links->links[$GLOBALS['language_content']->language]);

      // Remove links to unavailable translations.
      foreach ($links->links as $langcode => $link) {
        if (!isset($translations[$langcode]) || !entity_translation_access('node', $translations[$langcode])) {
          unset($links->links[$langcode]);
        }
      }

      $node->content['links']['translation'] = array(
        '#theme' => 'links',
        '#links' => $links->links,
        '#attributes' => array('class' => 'links inline'),
      );
    }
  }
}

/**
 * Implements hook_form_FORM_ID_alter().
 *
 * Provide settings into the node content type form to choose for entity
 * translation metadata and comment filtering.
 */
function entity_translation_form_node_type_form_alter(&$form, &$form_state) {
  $type = $form['#node_type']->type;

  $form['workflow']['language_content_type']['#options'][ENTITY_TRANSLATION_ENABLED] = t('Enabled, with entity translation');
  $form['workflow']['language_content_type']['#description'] .= ' ' . t('If <em>entity translation</em> is enabled it will be possible to provide a different version of the same content for each available language.');

  $form['display']['entity_translation_node_metadata'] = array(
    '#type' => 'radios',
    '#title' => t('Translation post information'),
    '#description' => t('Whether the translation authoring information should be hidden, shown, or replace the node\'s authoring information.'),
    '#default_value' => variable_get("entity_translation_node_metadata_$type", ENTITY_TRANSLATION_METADATA_HIDE),
    '#options' => array(t('Hidden'), t('Shown'), t('Replacing post information')),
  );

  if (isset($form['comment'])) {
    $form['comment']['entity_translation_comment_filter'] = array(
      '#type' => 'checkbox',
      '#title' => t('Filter comments per language'),
      '#default_value' => variable_get("entity_translation_comment_filter_$type", FALSE),
      '#description' => t('Show only comments whose language matches content language.'),
    );
  }
}

/**
 * Implements hook_preprocess_node().
 *
 * Alter node template variables to show/replace entity translation metadata.
 */
function entity_translation_preprocess_node(&$variables) {
  $node = $variables['node'];
  $submitted = variable_get("node_submitted_{$node->type}", TRUE);
  $mode = variable_get("entity_translation_node_metadata_{$node->type}", ENTITY_TRANSLATION_METADATA_HIDE);

  if ($submitted && $mode != ENTITY_TRANSLATION_METADATA_HIDE) {
    global $language_content, $user;

    $handler = entity_translation_get_handler('node', $node);
    $translations = $handler->getTranslations();
    $langcode = $language_content->language;

    if (isset($translations->data[$langcode]) && $langcode != $translations->original) {
      $translation = $translations->data[$langcode];
      $date = format_date($translation['created']);
      $name = FALSE;

      if ($node->uid != $translation['uid']) {
        $account = $user->uid != $translation['uid'] ? user_load($translation['uid']) : $user;
        $name = theme('username', array('account' => $account));
      }

      switch ($mode) {
        case ENTITY_TRANSLATION_METADATA_SHOW:
          $variables['date'] .= ' (' . t('translated on <em>!date</em>', array('!date' => $date)) . ')';
          if ($name) {
            $variables['name'] .= ' (' . t('translated by !name', array('!name' => $name)) . ')';
          }
          break;

        case ENTITY_TRANSLATION_METADATA_REPLACE:
          $variables['date'] = $date;
          if ($name) {
            $variables['name'] = $name;
          }
          break;
      }
    }
  }
}

/**
 * Implements hook_query_TAG_alter().
 *
 * Filter out node comments by content language.
 *
 * @todo Find a way to track node comment statistics per language.
 */
function entity_translation_query_comment_filter_alter(QueryAlterableInterface $query) {
  $node = $query->getMetaData('node');
  if (variable_get("entity_translation_comment_filter_{$node->type}", FALSE)) {
    $query->where("language = :language OR language = :language_none", array(':language' => $GLOBALS['language_content']->language, ':language_none' => LANGUAGE_NONE));
  }
}
