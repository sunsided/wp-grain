<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Media RSS Feed
	
	@package Grain Theme for WordPress
	@subpackage Plugin Suite
	@since Feeds
*/

	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

	// e.g. http://192.168.0.13:82/?feed=mediarss
	add_feed("mediarss", "grain_do_feed_mediarss");

	/**
	 * grain_do_feed_mediarss() - Outputs a media RSS feed
	 *
	 * @since 0.3
	 * @global $GrainOpt	Grain options
	 * @uses $wpdb	WordPress Database object
	 */	
	function grain_do_feed_mediarss() {
		global $GrainOpt, $wpdb;
		
		// get last changed date
		$dates = $wpdb->get_row("SELECT max(post_modified_gmt) AS a, max(post_date_gmt) AS b FROM $wpdb->posts LIMIT 1", ARRAY_N);
		$date = max(strtotime($dates[0]), strtotime($dates[1]));

		// Send headers
		header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);
		header("Last-Modified: " . gmdate("D, d M Y H:i:s", $date) . " GMT");
		
		// get the posts
		$posts = grain_get_mediarss_posts();

		echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'" standalone="yes"?>';
?>
<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss" xmlns:atom="http://www.w3.org/2005/Atom">
	<channel>
<?php 
		// loop through all posts
		foreach($posts as $post):

			// get the image
			$image = NULL;
			if (class_exists(YapbImage)) $image = YapbImage::getInstanceFromDb($post->ID);
			if( empty($image)  ) continue;

			// get some other variables
			$post_title = $post->post_title;
			$post_link = get_permalink($post->ID);
			$thumb_url = grain_get_mediarss_image_URL($post, $image, TRUE);
			$full_url = grain_get_mediarss_image_URL($post, $image, FALSE);
			if( substr($full_url, 0, 1) == "/" ) $full_url = get_bloginfo("url") . $full_url;
?>
		<item>
			<title><?php echo $post_title; ?></title>
			<link><?php echo $post_link; ?></link>
			<media:thumbnail url="<?php echo $thumb_url; ?>"/>
			<media:content url="<?php echo $full_url; ?>"/>
		</item>
<?php	
		endforeach;

?>

	</channel>
</rss><?php
	}

	
?>