<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

	Template Name: Mosaic Page
	Kudos to Johannes Jarolim (http://johannes.jarolim.com) for the support
*/
	
	// set sidebar class
	$has_sidebar = $GrainOpt->is(GRAIN_SDBR_ON_MOSAIC);
	$contentarea_class = "has-sidebar";
	if (!$has_sidebar) $contentarea_class = "no-sidebar";

	// get header
	get_header();

	// get some values
	$mosaic_count_per_page = $GrainOpt->get(GRAIN_MOSAIC_COUNT);
	$left_nav  = grain_get_mosaic_ppl(__("&laquo; previous page", "grain"), grain_mocaic_current_page(), $mosaic_count_per_page);
	$right_nav = grain_get_mosaic_npl(__("next page &raquo;", "grain"), grain_mocaic_current_page(), $mosaic_count_per_page);
	$has_navigation = !empty($left_nav) && !empty($right_nav);

	// get the page data
	the_post();

?>
<div id="content-archives" class="<?php echo $has_sidebar?"narrowcolumn":"widecolumn" ?>">

	<div id="archive-list" class="<?php echo $has_navigation?"has-navigat":"no-navigation"; ?>">

		<h2 class="pagetitle"><?php the_title(); ?></h2>

				<div id="navigation-top" class="navigation">
					<div class="alignleft"><?php echo $left_nav; ?></div>
					<div class="alignright"><?php echo $right_nav; ?></div>
				</div>
		<?php

			$isLimited = ($mosaic_count_per_page > 0);
			if( !$isLimited ) $mosaic_count_per_page = 0;
			$offset = $isLimited ? ((grain_mocaic_current_page()-1) * $mosaic_count_per_page) : 0;
			
			// get_post options
			$posts = grain_get_mosaic_posts($mosaic_count_per_page, $offset);
			
			$previousYear = '0000';
			$years_enabled = $GrainOpt->is(GRAIN_MOSAIC_DISPLAY_YEARS);
		?>
<div id="archive-posts">
		<?php
			echo '<!--'.@implode(",",grain_get_phpthumb_options(NULL, NULL)).'-->';
			
			$skipEmpty = $GrainOpt->is(GRAIN_MOSAIC_SKIP_EMPTY);
			foreach($posts as $post): 

				if( $years_enabled ) {
					$currentYear = mysql2date('Y', $post->post_date);
					if ($currentYear != $previousYear) {
						echo '<div id="year-'.$currentYear.'" class="year"><h2>' . $currentYear . '</h2></div>'.PHP_EOL;
						$previousYear = $currentYear;
					}
				}
			
			?>
			
<div id="post-<?php echo $post->ID; ?>" class="archive-post"><?php
				
				$image = NULL;
				if (class_exists(YapbImage)) $image = YapbImage::getInstanceFromDb($post->ID);
				if( empty($image) && $skipEmpty ) continue;
				
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
</div>
		<div id="navigation-bottom" class="navigation">
			<div class="alignleft"><?php echo $left_nav; ?></div>
			<div class="alignright"><?php echo $right_nav; ?></div>
		</div>
	</div>
</div>


<?php 
	if($has_sidebar) get_sidebar(); 
	get_footer(); ?>