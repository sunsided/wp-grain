<?php
/*
Template Name: Links
*/
?>

<?php get_header(); ?>

<div id="content" class="narrowcolumn">

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
		<h2><?php the_title(); ?></h2>
			<div class="entry">
				<?php the_content('<p class="serif"><strong>Den ganzen Beitrag lesen...</strong></p>'); ?>

				<?php link_pages('<p><strong>Seiten:</strong> ', '</p>', 'number'); ?>

				<?php get_links_list(); ?>
	
			</div>
		</div>
	  <?php endwhile; endif; ?>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
