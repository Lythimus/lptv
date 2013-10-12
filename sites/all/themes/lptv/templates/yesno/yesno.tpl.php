<?php
/**
 * @file
 * Rate widget theme
 */

if ($info) {
  print '<div class="rate-info">' . $info . '</div>';
}

if ($display_options['description']) {
  print '<div class="rate-description">' . $display_options['description'] . '</div>';
}

print theme('item_list', array('items' => $buttons));

