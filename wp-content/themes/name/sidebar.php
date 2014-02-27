
<aside class="sidebar three columns">


	<section class="social">
		<a href="#twitter"><img src="<?php bloginfo("template_directory"); ?>/images/twitter.png" alt="twitter" width="50" height="49" /></a>
		<a href="#facebook"><img src="<?php bloginfo("template_directory"); ?>/images/facebook.png" alt="facebook" width="50" height="49" /></a>
		<a href="#instagram"><img src="<?php bloginfo("template_directory"); ?>/images/instagram.png" alt="instagram" width="50" height="49" /></a>
	</section>

	<section class="newsletter">
		<form action="#">
			<p>Subscribe to the newsletter.</p>
			<input type="email" placeholder="Enter your email address" />
			<input type="submit" value="Join" />
		</form>
	</section>

	<section class="twitter-feed">
		<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Twitter')) ;?>
	</section>


	<section class="instagram-feed">
		<?php include_once("inc/instagram.php") ?>
	</section>


	<section>
		<?php get_search_form(); ?>
	</section>

	<ul class="cats">
		<?php wp_list_categories()?>
	</ul>

	<ul class="archive">
		 <?php wp_get_archives(); ?>
	</ul>

</aside>

