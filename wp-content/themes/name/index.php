<?php get_header(); ?>

<div class="nine columns" role="main">




	<?php if(!is_paged()) : ?>

		<?php $bio_id = get_ID_by_slug("bio"); ?>

		<div class="home-bio clearfix">
			<?php $home_bio_image = get_field("image", $bio_id); ?>
			<img src="<?php echo $home_bio_image["sizes"]["medium"]; ?>" alt="<?php bloginfo('name'); ?> " />
			<div class="content">
				<?php the_field("excerpt", $bio_id); ?>
			</div>
		</div>

	<?php endif; ?>


  <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>

      <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
        <header>
        	<div class="post-cats">
	        	 Posted Under <?php the_category(', ') ?>
        	</div>

        	<div class="post-pub">
	        	<time datetime="<?php the_time('Y-m-d')?>"><?php the_time('F jS, Y') ?></time><?php $pub = get_field("publication_name"); if(!empty($pub)) : ?> | Originally Published In <a href="<?php echo the_field("url"); ?>" title="<?php the_title(); ?>"><?php echo $pub; ?></a><?php endif; ?>
        	</div>

          <h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

        </header>
        <?php the_excerpt(); ?>

        <a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" class="read-more">Read More &raquo;</a>


      </article>

    <?php endwhile; ?>

    <nav>
      <div><?php next_posts_link('&laquo; Older Entries') ?></div>
      <div><?php previous_posts_link('Newer Entries &raquo;') ?></div>
    </nav>

  <?php else : ?>

    <h2>Not Found</h2>
    <p>Sorry, but you are looking for something that isn't here.</p>
    <?php get_search_form(); ?>

  <?php endif; ?>
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>


