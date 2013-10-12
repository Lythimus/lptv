<?php
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$target = 'asdfsadfsdf';

                dpm('begin node');
                $node = new stdClass();
                $node->type = 'lp';
                node_object_prepare($node);
		$node->title = $target;
                $node->created = time();
                $node->status = 1;
                $node->language = LANGUAGE_NONE;
                $node->body[$node->language][0]['value']   = '';
                $node->body[$node->language][0]['summary'] = '';
                $node->body[$node->language][0]['format']  = 'filtered_html';
                //$node->path = array('alias' => html($target));
//              $node = node_submit($node);
//              dpm('node submitted');
                node_save($node);
                dpm('node saved');
//              content_insert($node);
//              dpm('content inserted');
//              unset($node);
//              dpm('unset content');
//              $options['ids']= key($node->nid);
//              dpm('now a match');
//$node->field_lp_list['und'] = new array('nid', )
// drupal_set_message(t('%title does not match an existing '.$typename, array('%title' => $options['string'])));
?>
