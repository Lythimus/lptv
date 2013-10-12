<?php
/**
 * @file views-view-list.tpl.php
 * Default simple view template to display a list of rows.
 *
 * - $title : The title of this group of rows.  May be empty.
 * - $options['type'] will either be ul or ol.
 * @ingroup views_templates
 */
?>
<div id="holder"></div>
<table id="graphix">
<tbody>
    <?php foreach ($rows as $id => $row): ?>
<tr>
      <?php print $row; ?>
</tr>
    <?php endforeach; ?>


