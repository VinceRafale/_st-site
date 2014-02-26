<?php

get_header(); ?>

<div class="nine columns" role="main">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
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
  <?php endwhile; endif; ?>

</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
