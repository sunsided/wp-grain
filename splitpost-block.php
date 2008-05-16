<?php 

	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

	// print_r($post);

	$content = explode('<!--more-->', $post->post_content);
	$quick_info = &$content[0];
	
	
	echo '<div id="special-frame">';
	
	
	
	echo '</div>		<!-- <div id="special-frame"> -->';
	
	// inject navigation bar
	grain_inject_navigation_menu(GRAIN_IS_BODY_AFTER);

	// inject widget sidebar
	grain_inject_sidebar_below();

	?>

</div> <!-- id content -->

	
<?php

/*******************************************************************************************************************/

	// recheck for extended info mode, since info enforcement could be enabled
	if( $extended_mode && grain_comments_enabled() ) {

		include( TEMPLATEPATH.'/comments.php'); 
	
	}
	
/*******************************************************************************************************************/	
	
	// inject widget sidebar
	grain_inject_sidebar_footer();
	

// comments_template();

?>