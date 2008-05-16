<?php 
/*     
	This file is part of Grain Theme for WordPress.

    Grain Theme for WordPress is free software: you can redistribute it 
	and/or modify it under the terms of the GNU General Public License 
	as published by the Free Software Foundation, either version 3 of 
	the License, or (at your option) any later version.

    Foobar is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Grain Theme for WordPress.  
	If not, see <http://www.gnu.org/licenses/>.
	
	------------------------------------------------------------------
	File version: $Id$	
*/


	get_header(); 
?>

	<div id="content" class="narrowcolumn">

		<?php if (have_posts()) : ?>

		 <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>

	<?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="pagetitle"><?php

			$string = __("Archive for category %CATEGORY.", "grain");
			$string = str_replace('%CATEGORY', '<span class="name">'.single_cat_title('', false).'</span>', $string);
			echo $string;

		?></h2>
	
	<?php /* If this is a tag archive */ } elseif (is_tag()) { ?>
		<h2 class="pagetitle"><?php

			$string = __("Archive for tag %TAG.", "grain");
			$string = str_replace('%TAG', '<span class="name">'.single_cat_title('', false).'</span>', $string);
			echo $string;

		?></h2>
	
 	<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle"><?php
		
			$string = __("Daily archive for %DATE.", "grain");
			$string = str_replace('%DAY', apply_filters('the_time', get_the_time( 'l' ), 'l'), $string);
			$format = grain_filter_dt(grain_dtfmt_dailyarchive());
			$string = str_replace('%DATE', apply_filters('the_time', get_the_time( $format ), $format), $string);
			echo $string;
			
		?></h2>
		
	 <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle"><?php
		
			$string = __("Monthly archive for %DATE.", "grain");
			$format = grain_filter_dt(grain_dtfmt_monthlyarchive());
			$string = str_replace('%DATE', apply_filters('the_time', get_the_time( $format ), $format), $string);
			echo $string;
		?></h2>

		<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle"><?php
			$string = __("Yearly archive for %YEAR.", "grain");
			$string = str_replace('%YEAR', apply_filters('the_time', get_the_time( 'Y' ), 'Y'), $string);
			$string = str_replace('%DATE', apply_filters('the_time', get_the_time( 'Y' ), 'Y'), $string);
			echo $string;
			?></h2>
		
	  <?php /* If this is a search */ } elseif (is_search()) { ?>
		<h2 class="pagetitle"><?php echo __("Search results", "grain"); ?></h2>

	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle"><?php echo __("Archive of authors", "grain"); ?></h2>

		<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle"><?php echo __("Blog archive", "grain"); ?></h2>

		<?php } ?>


		<div class="navigation">
			<div class="alignleft"><?php next_posts_link(__("&laquo; next page", "grain")) ?></div>
			<div class="alignright"><?php previous_posts_link(__("previous page &raquo;", "grain")) ?></div>
		</div>

		<div id="archive-list">
		<?php while (have_posts()) : the_post(); 
		?><div class="archive-post"><?php 
				
				if (!is_null($image = YapbImage::getInstanceFromDb($post->ID))) {
					echo '<div class="archive-photo">';
				
					// display
					do_action(GRAIN_ARCHIVE_BEFORE_THUMB);
					echo grain_mimic_ygi_archive($image, $post);	
					do_action(GRAIN_ARCHIVE_AFTER_THUMB);
				
					echo '</div>';
				}
			?></div><?php 
			endwhile; ?>
		</div>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link(__("&laquo; next page", "grain")) ?></div>
			<div class="alignright"><?php previous_posts_link(__("previous page &raquo;", "grain")) ?></div>
		</div>
	
	<?php else : ?>

		<h2 class="center"><?php _e("Not found", "grain"); ?></h2>
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>
		
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
