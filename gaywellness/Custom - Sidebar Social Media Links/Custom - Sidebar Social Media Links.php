<?php
/*
*decode any encode value in order to display it correctly
*/
foreach ($user as $key => $value) {
	$user[$key] = rawurldecode($value);
}


	if (( $user['youtube'] != "" ||$user['google_plus'] != "" || $user['instagram'] != "" || $user['facebook'] != "" || $user['twitter'] != "" || $user['linkedin'] != "" || $user['pinterest'] != "" || $user['blog'] != "") && $subscription['social_link'] == 1) { ?>
	<div class="module">
		
		<div class="col-sm-12 member_social_icons" style="text-align: center"><div style="display: inline-block">
			<h3>Social Media

			</h3>
			<?php if ($user['facebook']!="" && $subscription['social_link'] == 1) { ?>
			<a class="network-icon facebook weblink" <?php if ($subscription['nofollow_links'] == 1){ ?>rel="nofollow"<?php } ?> href="<?php echo formatSocialMediaLink($user['facebook'],'http://www.facebook.com/')?>" target="_blank" title="Facebook"><i class="fa fa-facebook"></i></a>
			<?php }
			if ($user['twitter'] != "" && $subscription['social_link'] == 1) { ?>
			<a class="network-icon twitter weblink" <?php if ($subscription['nofollow_links'] == 1){ ?>rel="nofollow"<?php } ?> href="<?php echo formatSocialMediaLink($user['twitter'], 'http://www.twitter.com/')?>" target="_blank" title="Twitter"><i class="fa fa-twitter"></i></a>
			<?php }
			if ($user['linkedin'] != "" && $subscription['social_link'] == 1) { ?>
			<a class="network-icon linkedin weblink" <?php if ($subscription['nofollow_links'] == 1){ ?>rel="nofollow"<?php } ?> href="<?php echo formatSocialMediaLink($user['linkedin'],'http://www.linkedin.com/in/')?>" target="_blank" title="LinkedIn"><i class="fa fa-linkedin"></i></a>
			<?php }
			if ($user['google_plus'] != "" && $subscription['social_link'] == 1) { ?>
			<a class="network-icon googleplus weblink" <?php if ($subscription['nofollow_links'] == 1){ ?>rel="nofollow"<?php } ?> rel="publisher" href="<?php echo formatSocialMediaLink($user['google_plus'],'http://plus.google.com/')?>" target="_blank" title="Google"><i class="fa fa-google-plus"></i></a>
			<?php }
			if ($user['youtube'] != "" && $subscription['social_link'] == 1) { ?>
			<a class="network-icon youtube weblink" <?php if ($subscription['nofollow_links'] == 1){ ?>rel="nofollow"<?php } ?> href="<?php echo formatSocialMediaLink($user['youtube'],'http://www.youtube.com/')?>" target="_blank" title="YouTube"><i class="fa fa-youtube"></i></a>
			<?php }
			if ($user['instagram'] != "" && $subscription['social_link'] == 1) { ?>
			<a class="network-icon instagram weblink" <?php if ($subscription['nofollow_links'] == 1){ ?>rel="nofollow"<?php } ?> href="<?php echo formatSocialMediaLink($user['instagram'],'http://www.instagram.com/')?>" target="_blank" title="Instagram"><i class="fa fa-instagram"></i></a>
			<?php }
			if ($user['pinterest'] != "" && $subscription['social_link'] == 1) { ?>
			<a class="network-icon pinterest weblink"<?php if ($subscription['nofollow_links'] == 1){ ?>rel="nofollow"<?php } ?> href="<?php echo formatSocialMediaLink($user['pinterest'],'http://www.pinterest.com/')?>" target="_blank" title="Pinterest"><i class="fa fa-pinterest-p"></i></a>
			<?php }
			if ($user['blog'] != "" && $subscription['social_link'] == 1) { ?>
			<a class="network-icon blog weblink" <?php if ($subscription['nofollow_links'] == 1){ ?>rel="nofollow"<?php } ?> href="<?php echo $user['blog']?>" target="_blank" title="Blog"><i class="fa fa-rss"></i></a>
			<?php } ?>
		</div></div>
<br><br></div>
<?php } ?>