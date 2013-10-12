<?php
include_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$block = module_invoke('block' ,'block_view', 7);
print $block['content'];
?>
