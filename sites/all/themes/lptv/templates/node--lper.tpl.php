<? global $base_url;
function format_time($t,$f=':') // t = seconds, f = separator 
{
  return sprintf("%001d%s%02d%s%02d", floor($t/3600), $f, ($t/60)%60, $f, $t%60);
}
 ?>
<article<?php print $attributes; ?>>
  <?php print $user_picture; ?>
  <?php print render($title_prefix); ?>
  <?php if (!$page && $title): ?>
  <header>
    <h2<?php print $title_attributes; ?>><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
  </header>
  <?php endif; ?>
  <?php print render($title_suffix); ?>
  <?php if ($display_submitted): ?>
  <footer class="submitted"><?php print $date; ?> -- <?php print $name; ?></footer>
  <?php endif; ?>  
  
  <div<?php print $content_attributes; ?>><div id="lper-game-tiles">
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);

$query = db_query("
 SELECT * from {node}
 LEFT JOIN {field_data_field_lp_lper}
 ON {node}.nid = {field_data_field_lp_lper}.entity_id
 WHERE {node}.type = 'lp' AND
 {field_data_field_lp_lper}.field_lp_lper_nid = ".$node->nid." AND
 {node}.status = 1
");
foreach ($query as $record) {
 $lp = node_load($record->nid);
 foreach ($lp->field_game['und'] as $gameNid) {
	$game = node_load($gameNid['nid']);
	if (isset($game->field_image['und']))
		$gameImage = image_style_url('lper_lp_tile', $game->field_image['und'][0]['uri']);
	else if (isset($game->field_thumbnail['und']))
		$gameImage = image_style_url('lper_lp_tile', $game->field_thumbnail['und'][0]['uri']);
	else
		$gameImage = NULL;
	if (isset($game->field_platform['und']))
		$gamePlatform = $game->field_platform['und'][0]['value'];
        if (isset($lp->field_language['und']))
                $lpLanguage = $lp->field_language['und'][0]['value'];
        if (isset($lp->field_video_clips['und']))
                $lpVideoClips = $lp->field_video_clips['und'][0]['value'];
        if (isset($lp->field_game_audio['und']))
                $lpGameAudio = $lp->field_game_audio['und'][0]['value'];
        if (isset($lp->field_voice_acting['und']))
                $lpVoiceActing = $lp->field_voice_acting['und'][0]['value'];
        if (isset($lp->field_edited['und']))
                $lpEdited = $lp->field_edited['und'][0]['value'];
        if (isset($lp->field_walkthrough['und']))
                $lpWalkthrough = $lp->field_walkthrough['und'][0]['value'];
        if (isset($lp->field_interrupts_game['und']))
                $lpInterruptsGame = $lp->field_interrupts_game['und'][0]['value'];
        if (isset($lp->field_custing_vulgarity['und']))
                $lpCursingVulgarity = $lp->field_cursing_vulgarity['und'][0]['value'];
        if (isset($lp->field_commercials['und']))
                $lpCommercials = $lp->field_commercials['und'][0]['value'];
        if (isset($lp->field_status['und']))
                $lpStatus = $lp->field_status['und'][0]['value'];
	$gameProperties = implode(', ', array($lpLanguage, $lpVideoClips, $lpGameAudio, $lpVoiceActing, $lpEdited, $lpWalkthrough, $lpInterruptsGame, $lpCommercials, $lpStatus), true); // list of prorpeties
	if (isset($lp->field_lp_length['und'])) 
		$lpLength = format_time($lp->field_lp_length['und'][0]['value']);
	else
		$lpLength = "";
	print '<a class="game" href="'.$base_url.'/'.drupal_lookup_path('alias', 'node/'.$lper->nid).'">';
        if (isset($gameImage)) print '<img src="'.$gameImage.'" />';
        print '<div class="overlay">
		<div class="gameTitle">'.$game->title.'</div>
		<div class="lpLength">'.$lpLength.'</div>
		<div class="gameProperties">'.$gameProperties.'</div>
	</div>
        </a>';

 }
} 
    ?>
  
</div>
<div id="lper-description">
<?php if (!empty($content['field_thumbnail'])) print render($content['field_thumbnail']);
	print $title;
	// if (!empty($content['field_date'])) print '<span class="date-display-interval">'.date('Y', time() - strtotime($node->field_date['und'][0]['value'])).' years</span>';
	if (!empty($content['field_date'])) print str_replace(' ago', ' old', render($content['field_date']));
	if (!empty($content['field_country'])) print render($content['field_country']);
	if (!empty($content['field_website'])) print render($content['field_website']);
	print render($content['body']); ?>
</div>
  
  <div class="clearfix">
    <?php if (!empty($content['links'])): ?>
      <nav class="links node-links clearfix"><?php print render($content['links']); ?></nav>
    <?php endif; ?>

    <?php print render($content['comments']); ?>
  </div>
</article>
