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