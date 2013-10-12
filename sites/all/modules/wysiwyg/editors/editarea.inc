<?php
  // $Id: editarea.inc,v 1.5 2009/06/09 00:18:12 sun Exp $

  /**
   * @file
   * Editor integration functions for EditArea.
   */

  /**
   * Plugin implementation of hook_editor().
   */
function wysiwyg_editarea_editor() 
{
	$editor['editarea'] = array(
		'title' => 'EditArea',
		'vendor url' => 'http://www.cdolivet.com',
		'download url' => 'http://sourceforge.net/project/showfiles.php?group_id=164008',
		'library path' => wysiwyg_get_path('editarea') . '/edit_area',
		'libraries' => array(
			'' => array(
				'title' => 'Source',
				'files' => array('edit_area_full.js'),
				)
			),
		'version callback' => 'wysiwyg_editarea_version',
		'settings callback' => 'wysiwyg_editarea_settings',
		'plugin callback' => 'wysiwyg_editarea_plugins',
		'versions' => array(
			'0.8.1.1' => array(
				'js files' => array('editarea.js'),
				),
			),
		);
	return $editor;
}

/**
 * Detect editor version.
 *
 * @param $editor
 *   An array containing editor properties as returned from hook_editor().
 *
 * @return
 *   The installed editor version.
 */
function wysiwyg_editarea_version($editor) 
{
	$changelog = $editor['library path'] . '/../change_log.txt';
	$changelog = fopen($changelog, 'r');
	$line = fgets($changelog);
	if (preg_match('/[*]{4} v ([0-9\.]+) [*]{4}/', $line, $version)) {
		fclose($changelog);
		return $version[1];
	}
	fclose($changelog);
}

/**
 * Return runtime editor settings for a given wysiwyg profile.
 *
 * @param $editor
 *   A processed hook_editor() array of editor properties.
 * @param $config
 *   An array containing wysiwyg editor profile settings.
 * @param $theme
 *   The name of a theme/GUI/skin to use.
 *
 * @return
 *   A settings array to be populated in
 *   Drupal.settings.wysiwyg.configs.{editor}
 */
function wysiwyg_editarea_settings($editor, $config, $theme) 
{
	// Whoever is guilty for adding this horrible CSS-file-without-filepath
	// override "feature" to Drupal core... stand in the corner!
//  drupal_add_css($editor['library path'] . '/skins/' . $theme . '/style.css', 'theme');

	$settings = array(
		'root' => base_path() . $editor['library path'] . '/',
		'nameSpace' => $theme,
		'markupSet' => array(),
		);

	// Add configured buttons or all available.
	$default_buttons = array(
		'bold' => array(
			'name' => t('Bold'),
			'className' => 'editarea-bold',
			'key' => 'B',
			'openWith' => '(!(<strong>|!|<b>)!)',
			'closeWith' => '(!(</strong>|!|</b>)!)',
			),
		'italic' => array(
			'name' => t('Italic'),
			'className' => 'editarea-italic',
			'key' => 'I',
			'openWith' => '(!(<em>|!|<i>)!)',
			'closeWith' => '(!(</em>|!|</i>)!)',
			),
		'stroke' => array(
			'name' => t('Strike-through'),
			'className' => 'editarea-stroke',
			'key' => 'S',
			'openWith' => '<del>',
			'closeWith' => '</del>',
			),
		'image' => array(
			'name' => t('Image'),
			'className' => 'editarea-image',
			'key' => 'P',
			'replaceWith' => '<img src="[![Source:!:http://]!]" alt="[![Alternative text]!]" />',
			),
		'link' => array(
			'name' => t('Link'),
			'className' => 'editarea-link',
			'key' => 'K',
			'openWith' => '<a href="[![Link:!:http://]!]"(!( title="[![Title]!]")!)>',
			'closeWith' => '</a>',
			'placeHolder' => 'Your text to link...',
			),
		// @todo
		// 'cleanup' => array('name' => t('Clean-up'), 'className' => 'editarea-cleanup', 'replaceWith' => 'function(editarea) { return editarea.selection.replace(/<(.*?)>/g, "") }'),
		'preview' => array(
			'name' => t('Preview'),
			'className' => 'editarea-preview',
			'call' => 'preview',
			),
		);

	if (!empty($config['buttons'])) {
		foreach ($config['buttons'] as $plugin) {
			foreach ($plugin as $button => $enabled) {
				if (isset($default_buttons[$button])) {
					$settings['markupSet'][] = $default_buttons[$button];
				}
			}
		}
	}
	else {
		$settings['markupSet'] = $default_buttons;
	}

	return $settings;
}

/**
 * Return internal plugins for this editor; semi-implementation of hook_wysiwyg_plugin().
 */
function wysiwyg_editarea_plugins($editor) {
	return array(
		'default' => array(
/*      'buttons' => array(
 'bold' => t('Bold'), 'italic' => t('Italic'),
 'stroke' => t('Strike-through'),
 'image' => t('Image'),
 'link' => t('Link'),
 // 'cleanup' => t('Clean-up'),
 'preview' => t('Preview'),
 ),
*/      'internal' => TRUE,
			),
		);
}


