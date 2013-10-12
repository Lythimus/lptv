<?php
/**
 * @file
 * Rate widget theme
 */

if ($display_options['description']) {
  print '<div class="rate-description">' . $display_options['description'] . '</div>';
}

if ($info) {
  print '<div class="rate-info">' . $info . '</div>';
}


print theme('item_list', array('items' => $buttons));

