<?php
/*
Template Name: Mosaic Page
*/
 get_header();


/*
	Thanks for the support to Johannes Jarolim (http://johannes.jarolim.com)
*/

$mosaic_count_per_page = grain_mosaic_count();

?>
<div id="content-archives" class="narrowcolumn">

	<div id="archive-list">

		<h2 class="pagetitle"><?php the_title(); ?></h2>

				<div class="navigation">
					<div class="alignleft"><?php grain_mosaic_ppl(__("&laquo; previous page", "grain"), grain_mocaic_current_page(), $mosaic_count_per_page) ?></div>
					<div class="alignright"><?php grain_mosaic_npl(__("next page &raquo;", "grain"), grain_mocaic_current_page(), $mosaic_count_per_page) ?></div>
				</div>

		<?php

			$offset = (grain_mocaic_current_page()-1) * $mosaic_count_per_page;
			$posts = get_posts('numberposts='.$mosaic_count_per_page.'&offset='.$offset);
			$previousYear = '0000';
			$years_enabled = grain_mosaic_years();
			foreach($posts as $post): 
			
			?><div class="archive-post"><?php

			if( $years_enabled ) {
					$currentYear = mysql2date('Y', $post->post_date);
					if ($currentYear != $previousYear) {
						echo '<div class="year"><h2>' . $currentYear . '</h2></div>';
						$previousYear = $currentYear;
					}
			}
				if (!is_null($image = YapbImage::getInstanceFromDb($post->ID))) {
					echo '<div class="mosaic-photo">';
					
					// display
					do_action(GRAIN_ARCHIVE_BEFORE_THUMB);
					echo grain_mimic_ygi_archive($image, $post);
					do_action(GRAIN_ARCHIVE_AFTER_THUMB);
					
					// close div
					echo '</div>';
				}
		?></div><?php
		
			endforeach;

		?>
				<div class="navigation">
					<div class="alignleft"><?php grain_mosaic_ppl(__("&laquo; previous page", "grain"), grain_mocaic_current_page(), $mosaic_count_per_page) ?></div>
					<div class="alignright"><?php grain_mosaic_npl(__("next page &raquo;", "grain"), grain_mocaic_current_page(), $mosaic_count_per_page) ?></div>
				</div>
	</div>
</div>


<?php get_sidebar(); ?>
<?php get_footer(); ?>