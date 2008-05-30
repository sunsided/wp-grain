<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

	Template Name: Mosaic Page
	Kudos to Johannes Jarolim (http://johannes.jarolim.com) for the support
*/


	get_header();
	
	$mosaic_count_per_page = $GrainOpt->get(GRAIN_MOSAIC_COUNT);

	// get the page data
	the_post();

?>
<div id="content-archives" class="narrowcolumn">

	<div id="archive-list">

		<h2 class="pagetitle"><?php the_title(); ?></h2>

				<div id="navigation-top" class="navigation">
					<div class="alignleft"><?php grain_mosaic_ppl(__("&laquo; previous page", "grain"), grain_mocaic_current_page(), $mosaic_count_per_page) ?></div>
					<div class="alignright"><?php grain_mosaic_npl(__("next page &raquo;", "grain"), grain_mocaic_current_page(), $mosaic_count_per_page) ?></div>
				</div>

		<?php

			$offset = (grain_mocaic_current_page()-1) * $mosaic_count_per_page;
			$posts = get_posts('numberposts='.$mosaic_count_per_page.'&offset='.$offset);
			$previousYear = '0000';
			$years_enabled = $GrainOpt->is(GRAIN_MOSAIC_DISPLAY_YEARS);
			foreach($posts as $post): 
			
			?>
			
<div class="archive-post"><?php

			if( $years_enabled ) {
					$currentYear = mysql2date('Y', $post->post_date);
					if ($currentYear != $previousYear) {
						echo '<div class="year"><h2>' . $currentYear . '</h2></div>'.PHP_EOL;
						$previousYear = $currentYear;
					}
			}
			
			$image = NULL;
			if (class_exists(YapbImage)) $image = YapbImage::getInstanceFromDb($post->ID);
			
			// display
			echo '<div class="mosaic-photo">';
			do_action(GRAIN_ARCHIVE_BEFORE_THUMB);
			echo grain_mimic_ygi_archive($image, $post);
			do_action(GRAIN_ARCHIVE_AFTER_THUMB);					
			echo '</div>';
		?>
		</div>
<?php
		
			endforeach;

		?>
				<div id="navigation-bottom" class="navigation">
					<div class="alignleft"><?php grain_mosaic_ppl(__("&laquo; previous page", "grain"), grain_mocaic_current_page(), $mosaic_count_per_page) ?></div>
					<div class="alignright"><?php grain_mosaic_npl(__("next page &raquo;", "grain"), grain_mocaic_current_page(), $mosaic_count_per_page) ?></div>
				</div>
	</div>
</div>


<?php get_sidebar(); ?>
<?php get_footer(); ?>