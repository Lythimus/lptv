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
  
  <div<?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
      print views_embed_view('lp_other_lpers', 'lp_other_lpers', $node->field_game['und'][0]['nid']);
      //dpm($node);

	if (isset($_GET['video'])) {
		$latestVideo = $node->field_lp_list['und'][intval($_GET['video'])]['node']->title;
		//dpm($latestVideo);
		//dpm($_GET['video']);
		$lpUrl = field_view_field('node', $node, 'field_lp_list');
		$lpUrl = preg_replace('&feature=youtube_gdata', '', $lpUrl[intval($_GET['video'])]['#title']);
		//dpm($lpUrl);
		//dpm($node); 
		//dpm(render($latestVideo)); ?>
		<div id="lp-video-container">
		<iframe class="youtube-player" type="text/html" width="640" height="385" src="<?php print str_replace('/watch?v=', '/embed/',  str_replace('&feature=youtube_gdata', '', $latestVideo)); ?>" frameborder="0"></iframe>
		</div>
		<script type="text/javascript"> 
		/*
		jwplayer("lp-video-container").setup({
			height: 270,
			width: 480,
			file: "https://www.youtube.com/watch?v=jiBlindky7Q",
			provider: "youtube",
			modes: [
				{ type: 'html5' },
				{ type: 'flash', src: "player.swf" },
				{ type: 'download' }
			]
			// start: "computed",
			//title: "computed",
			//description: "computed",
			//levels: "computed",
			//image: 'preview.jpg'
		}); 
		*/
		</script>
		<?php
		//print '<iframe width="640" height="480" src="'.$lpUrl.'?rel=0" frameborder="0" allowfullscreen></iframe>';
		// print render($content);
	}
    ?>
    <?php if (!empty($content['rate_edited'])): ?>
      <nav class="links node-links clearfix">
      <?php 
        print render($content['rate_edited']); 
        print render($content['rate_voice_acting']);
        print render($content['rate_humorous']);
        print render($content['rate_video_clips']);
        print render($content['rate_speedrun']);
        print render($content['rate_cursing_vulgarity']);
        print render($content['rate_interrupts_game']);
        print render($content['rate_completed']);
        print render($content['rate_commercials']);
        print render($content['rate_walkthrough']);
        print render($content['rate_game_audio']);
      ?></nav><br class="clearfix" />
    <?php endif; ?>
    <div class="video-extras clearfix">
    <?php if (!empty($content['body'])) { ?>
      <div class="video-description">
      <h3>Video Description</h3>
      <?php print render($content['body']).'</div>';
    } 
    print render(views_embed_view('recommendations', 'recommended_lps'));
    print render(views_embed_view('recommendations', 'similar'));
?>
    </div>
  </div>
  
  <div class="clearfix">
    <?php print render($content['comments']); ?>
  </div>
</article>
