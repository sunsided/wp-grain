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

/*
Template Name: Archives
*/

?>

<?php get_header(); ?>

<div id="content-archives" class="narrowcolumn">

<h2 class="pagetitle"><?php the_title(); ?></h2>

<h2><?php _e("Archive cloud"); ?></h2>
<div id="tag-cloud">
<?php
	$smallest = 0.75;
	$largest = 2;
	$unit = "em";
	$exclude = "";

	grain_weighted_categories($smallest, $largest, $unit, $exclude);
?>
</div>

<h2><?php _e("Archives by years"); ?></h2>
			
<ul>
	<?php
		$years = $wpdb->get_col("SELECT DISTINCT YEAR(post_date) FROM $wpdb->posts WHERE post_status = 'publish' ORDER BY post_date DESC");
		foreach($years as $year) :
	?>
	<li><a href="<?php echo get_year_link($year); ?> "><?php echo $year; ?></a></li>

	<?php endforeach; ?>
</ul>

<h2><?php _e("Archives by months"); ?></h2>
  <ul>
    <?php wp_get_archives('type=monthly'); ?>
  </ul>

<h2><?php _e("Archives by categories"); ?></h2>
  <ul>
     <?php wp_list_cats(); ?>
  </ul>

</div>	

<?php get_sidebar(); ?>

<?php get_footer(); ?>
