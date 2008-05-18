<?php 
/*     
	This file is part of Grain Theme for WordPress.

    Grain Theme for WordPress is free software: you can redistribute it 
	and/or modify it under the terms of the GNU General Public License 
	as published by the Free Software Foundation, either version 3 of 
	the License, or (at your option) any later version.

    Grain Theme for WordPress is distributed in the hope that it will 
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
	of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Grain Theme for WordPress.  
	If not, see <http://www.gnu.org/licenses/>.
	
	------------------------------------------------------------------
	File version: $Id$
*/


	get_header(); 
?>

	<div id="content-archives" class="narrowcolumn">

	<?php if (have_posts()) : ?>

		<h2 class="pagetitle"><?php _e("Search results", "grain"); ?></h2>

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
