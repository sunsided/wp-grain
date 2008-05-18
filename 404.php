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

	<div id="content" class="narrowcolumn">

		<h2 class="center"><?php _e("Error 404 - Not found!", "grain"); ?></h2>
		<p><?php _e("Would you like to search for something?", "grain"); ?></p>
		<?php include (TEMPLATEPATH . "/searchform.php"); ?>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
