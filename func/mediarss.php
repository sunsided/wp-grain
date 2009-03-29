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

	define("GRAIN_MEDIARSS_MODE_DEFAULT", 	1);
	define("GRAIN_MEDIARSS_MODE_MOSAIC", 	2);
	define("GRAIN_MEDIARSS_MODE_ARCHIVE", 	3);

	// e.g. http://127.0.0.1/wordpress/?feed=mediarss
	global $GrainOpt;
	if( $GrainOpt->is(GRAIN_FTR_MEDIARSS) ) {
		add_feed("mediarss", "grain_do_feed_mediarss");
	}
	else {
		add_action("rss2_ns", "grain_inject_mrss_ns");
		add_action("rss2_item", "grain_inject_mrss_item");
	}

	/**
	 * grain_get_mrss_url() - Generates the URL of the MediaRSS feed
	 *
	 * @since 0.3.1
	 */	
	function grain_get_mrss_url($type=GRAIN_MEDIARSS_MODE_DEFAULT, $xml_safe=false, $page=-1) {
		// prepare ampersand
		$amp = "&";
		if( $xml_safe ) $amp = "&amp;";
		
		// create URL
		$url = get_bloginfo('url') . "/?feed=mediarss";
		
		// are we on the mosaic page?
		if( (GRAIN_ON_MOSAIC_PAGE && !GRAIN_ON_FEED) || (GRAIN_ON_FEED && $type == GRAIN_MEDIARSS_MODE_MOSAIC) ) {
			if( $page <= 0 ) $page = grain_get_current_page();
			$url .= $amp . "type=".GRAIN_MEDIARSS_MODE_MOSAIC;//.$amp . "page=$page";
		}
		elseif( (GRAIN_ON_ARCHIVE_PAGE && !GRAIN_ON_FEED) || (GRAIN_ON_FEED && $type == GRAIN_MEDIARSS_MODE_ARCHIVE) ) {
			if( $page <= 0 ) $page = grain_get_current_page();
			$url .= $amp . "type=".GRAIN_MEDIARSS_MODE_ARCHIVE;//.$amp . "page=$page";
		}
		else 
		{
			 //$amp . "type=".GRAIN_MEDIARSS_MODE_DEFAULT;
		}
		if( $page > 1 )	$url .= $amp."page=$page";
		
		return $url;
	}

	/**
	 * grain_inject_mrss_ns() - Injects the MRSS namespace
	 *
	 * @since 0.3
	 */	
	function grain_inject_mrss_ns() {
		echo 'xmlns:media="http://search.yahoo.com/mrss/"'.PHP_EOL;
	}
	
	/**
	 * grain_inject_mrss_item() - Injects media:* values into the RSS2 feed
	 *
	 * @since 0.3
	 */	
	function grain_inject_mrss_item() {
		global $post;
	
		// get the image
		$image = NULL;
		if (class_exists(YapbImage)) $image = YapbImage::getInstanceFromDb($post->ID);
		if( empty($image)  ) continue;

		// image sizes
		$width = 0; $height = 0;
		$thumb_width = 0; $thumb_height = 0;
		
		// get image urls
		$thumb_url = grain_get_mediarss_image_URL($post, $image, TRUE, $thumb_width, $thumb_height);
		$full_url = grain_get_mediarss_image_URL($post, $image, FALSE, $width, $height);
		if( substr($full_url, 0, 1) == "/" ) $full_url = get_bloginfo("url") . $full_url;
		
		// get the list of tahs
		$posttags = get_the_tags();
		$taglist = array();
		if ($posttags) {
			foreach($posttags as $tag) {
				$taglist[] = $tag->name;
			}
		}
		$taglist = @implode(", ", $taglist);
		
		// mime type
		$mime_type = "";
		if( substr($image->uri, -4, 4) == ".jpg" ) $mime_type = "image/jpeg";
		else if( substr($image->uri, -5, 5) == ".jpeg" ) $mime_type = "image/jpeg";
		else if( substr($image->uri, -4, 4) == ".jpe" ) $mime_type = "image/jpeg";
		else if( substr($image->uri, -5, 5) == ".jfif" ) $mime_type = "image/jpeg";
		else if( substr($image->uri, -4, 4) == ".png" ) $mime_type = "image/png";
		else if( substr($image->uri, -4, 4) == ".gif" ) $mime_type = "image/gif";
	
				
		$filesize = filesize(realpath(ABSPATH.".".$image->uri));					
		?>
<?php if(!empty($taglist)): ?>
			<media:keywords><?php echo $taglist; ?></media:keywords>
<?php endif; ?>
			<media:thumbnail 
				url="<?php echo $thumb_url; ?>"
				width="<?php echo $thumb_width; ?>" 
				height="<?php echo $thumb_height; ?>"
				/>
<?php /* if(!empty($filesize)): ?>
			<enclosure 
				url="<?php echo $full_url; ?>"
				length="<?php echo $filesize; ?>"
				type="<?php echo $mime_type; ?>"
				/>
<?php endif; */ 
if( grain_posttype($post->ID) == GRAIN_POSTTYPE_PHOTO ):
?>
			<media:content 
				url="<?php echo $full_url; ?>"
				width="<?php echo $width; ?>"
				height="<?php echo $height; ?>"
<?php 	if(!empty($filesize)): ?>				
				fileSize="<?php echo $filesize; ?>" 
<?php endif; ?>
<?php if(!empty($mime_type)): ?>				
				type="<?php echo $mime_type; ?>"
<?php endif; ?>
				/>		
		<?php
endif; // GRAIN_POSTTYPE_PHOTO
		
	}

	/**
	 * grain_do_feed_mediarss() - Outputs a media RSS feed
	 *
	 * @since 0.3
	 * @global $GrainOpt	Grain options
	 * @uses $wpdb	WordPress Database object
	 */	
	function grain_do_feed_mediarss() {
		global $GrainOpt, $wpdb, $wp_query;
		
		// caching
		$stamp = get_lastpostmodified('GMT');
		$etag = '"'.$stamp.'"';
		$modified_date = mysql2date('D, d M Y H:i:s +0000', $stamp, false);
		$if_modified_since = preg_replace('/;.*$/', '', @$_SERVER['HTTP_IF_MODIFIED_SINCE']);
		$if_none_match = stripslashes(@$_SERVER['HTTP_IF_NONE_MATCH']);
		$etag_list = explode(",", $if_none_match);
		
		// Send headers
		header("Last-Modified: " . $modified_date);
		header('ETag: '.$etag, false);

		// if the content hasn't changed, don't deliver it
		foreach($etag_list as $etag_list_item) {
			if (trim($etag_list_item) == $etag) {
				header("HTTP/1.0 304 Not Modified");
				header("X-Grain-NotModifiedReason: ETag");
				die();
			}
		}
		
		// if the content hasn't changed, don't deliver it
		if ($if_modified_since == $modified_date) {
			header("HTTP/1.0 304 Not Modified");
			header("X-Grain-NotModifiedReason: Modified-Date");
			die();
		}

		$language = get_bloginfo('language');

		// further information
		header("Cache-Control: public, must-revalidate");
		header("Pragma: public");
		header('Content-Type: text/xml; charset=' . GRAIN_CONTENT_CHARSET, true);		
		
		// enable output buffering
		grain_start_buffering();
		
		// get some flags
		$is_cc = $GrainOpt->is(GRAIN_COPYRIGHT_CC_ENABLED);
		$copyright = grain_get_copyright_string(FALSE);
		$cc_license_url = grain_get_cc_license_url();
		
		// get the feed type
		$type = @$_REQUEST["type"];
		if( empty($type) ) $type = GRAIN_MEDIARSS_MODE_DEFAULT;
		$type = intval($type);
		
		// get the number of posts per page
		$count_per_page = grain_get_mediarss_post_per_page();
		if( $type == GRAIN_MEDIARSS_MODE_MOSAIC ) $count_per_page = $GrainOpt->get(GRAIN_MOSAIC_COUNT);
		elseif( $type == GRAIN_MEDIARSS_MODE_ARCHIVE ) $count_per_page = $wp_query->query_vars["posts_per_page"];
		
		$count = grain_getpostcount();
		$page_count = ceil($count / $count_per_page);
		
		// get the offset from the request
		$page = @$_REQUEST["page"];
		if( empty($page) ) $page = 1;
		$page = intval($page);
	
		
		// get the posts
		$posts = grain_get_mediarss_posts($page, $count_per_page);

		echo '<?xml version="1.0" encoding="'.GRAIN_CONTENT_CHARSET.'" standalone="yes"?>'; 
?>
 <!-- displaying page <?php echo $page; ?> of <?php echo $page_count; ?> -->
<rss version="2.0" 
	<?php grain_inject_mrss_ns(); ?>
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
<?php if($is_cc): ?>
	xmlns:creativeCommons="http://backend.userland.com/creativeCommonsRssModule"
<?php endif; 
	?>
	>
	<channel>
<?php if( $page > 1): ?>
		<atom:link href="<?php echo grain_get_mrss_url($type, true, $page-1); ?>" rel="previous" type="application/rss+xml" /><?php endif; ?>
		
		<atom:link href="<?php echo grain_get_mrss_url($type, true, $page); ?>" rel="self" type="application/rss+xml" />
<?php if( $page < $page_count): ?>
		<atom:link href="<?php echo grain_get_mrss_url($type, true, $page+1); ?>" rel="next" type="application/rss+xml" /><?php endif; ?>

		<title><?php bloginfo('name'); ?> Media RSS Feed</title>
		<link><?php bloginfo("url"); ?></link>
		
		<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', get_lastpostmodified('GMT'), false); ?></pubDate>
		<description><?php bloginfo('description'); ?></description>
		<docs>http://blogs.law.harvard.edu/tech/rss</docs>
		<generator>Grain <?php echo GRAIN_THEME_VERSION; ?></generator>
<?php if( !empty($language) ): ?>
		<language><?php echo $language; ?></language>
<?php
		endif;
		
		// loop through all posts
		global $post;
		foreach($posts as $the_post):
			$post = $the_post;
			//the_post();

	
			/*
			stdClass Object
			(
				[ID] => 14
				[post_author] => 1
				[post_date] => 2008-06-19 07:49:01
				[post_date_gmt] => 2008-06-19 05:49:01
				[post_content] => dieses Bild ist ... groß
				[post_title] => großes Bild
				[post_category] => 0
				[post_excerpt] => excerpt. bla bla.
				[post_status] => publish
				[comment_status] => open
				[ping_status] => open
				[post_password] => 
				[post_name] => groses-bild
				[to_ping] => 
				[pinged] => 
				[post_modified] => 2008-06-20 01:50:29
				[post_modified_gmt] => 2008-06-19 23:50:29
				[post_content_filtered] => 
				[post_parent] => 0
				[guid] => http://192.168.0.13:82/?p=14
				[menu_order] => 0
				[post_type] => post
				[post_mime_type] => 
				[comment_count] => 0
				[image] => YapbImage Object
					(
						[id] => 11
						[post_id] => 14
						[uri] => /wp-content/uploads/2008/06/8932506.jpg
						[width] => 2268
						[height] => 1512
						[_thumbInfoCache] => Array
							(
							)

					)

			)
			*/

			// get the image
			$image = NULL;
			if (class_exists(YapbImage)) $image = YapbImage::getInstanceFromDb($post->ID);
			if( empty($image)  ) continue;

			// image sizes
			$width = 0; $height = 0;
			$thumb_width = 0; $thumb_height = 0;
			
			// get image urls
			$thumb_url = grain_get_mediarss_image_URL($post, $image, TRUE, $thumb_width, $thumb_height);			
			$full_url = grain_get_mediarss_image_URL($post, $image, FALSE, $width, $height);
			if( substr($full_url, 0, 1) == "/" ) $full_url = get_bloginfo("url") . $full_url;
			
			// get the list of tahs
			$posttags = get_the_tags();
			$taglist = array();
			if ($posttags) {
				foreach($posttags as $tag) {
					$taglist[] = $tag->name;
				}
			}
			$taglist = @implode(", ", $taglist);
			
			// mime type
			$mime_type = "";
			if( substr($image->uri, -4, 4) == ".jpg" ) $mime_type = "image/jpeg";
			else if( substr($image->uri, -5, 5) == ".jpeg" ) $mime_type = "image/jpeg";
			else if( substr($image->uri, -4, 4) == ".jpe" ) $mime_type = "image/jpeg";
			else if( substr($image->uri, -5, 5) == ".jfif" ) $mime_type = "image/jpeg";
			else if( substr($image->uri, -4, 4) == ".png" ) $mime_type = "image/png";
			else if( substr($image->uri, -4, 4) == ".gif" ) $mime_type = "image/gif";
		
					
			$filesize = filesize(realpath(ABSPATH.".".$image->uri));					
			
?>
		<item>
			<title><?php the_title_rss() ?></title>
			<media:title type="html"><![CDATA[<?php echo htmlentities($post->post_title, ENT_QUOTES, GRAIN_CONTENT_CHARSET, FALSE); ?>]]></media:title>
			<link><?php the_permalink_rss() ?></link>
			<guid isPermaLink="false"><?php the_guid(); ?></guid>
			<pubDate><?php echo mysql2date('D, d M Y H:i:s +0000', !empty($post->post_modified_gmt) ? $post->post_modified_gmt : $post->post_date_gmt, false); ?></pubDate>
			<comments><?php comments_link(); ?></comments>
			<wfw:commentRss><?php echo get_post_comments_feed_link(); ?></wfw:commentRss>
<?php if(!empty($post->post_excerpt)): ?>	
			<description><![CDATA[<?php echo $post->post_excerpt; ?>]]></description>
<?php endif; ?>
<?php if(!empty($taglist)): ?>
			<media:keywords><?php echo $taglist; ?></media:keywords>
<?php endif; ?>
<?php if(!$is_cc): ?>
			<media:copyright><?php echo $copyright; ?></media:copyright>
<?php else: ?>			
			<creativeCommons:license><?php echo $cc_license_url; ?></creativeCommons:license>
<?php endif; ?>
			<media:thumbnail 
				url="<?php echo $thumb_url; ?>"
				width="<?php echo $thumb_width; ?>" 
				height="<?php echo $thumb_height; ?>"
				/>
<?php if(!empty($filesize)): ?>
			<enclosure 
				url="<?php echo $full_url; ?>"
				length="<?php echo $filesize; ?>"
				type="<?php echo $mime_type; ?>"
				/>
<?php endif; 
if( grain_posttype($post->ID) == GRAIN_POSTTYPE_PHOTO ):
?>
			<media:content 
				url="<?php echo $full_url; ?>"
				width="<?php echo $width; ?>"
				height="<?php echo $height; ?>"
<?php if(!empty($filesize)): ?>				
				fileSize="<?php echo $filesize; ?>" 
<?php endif; ?>
<?php if(!empty($mime_type)): ?>				
				type="<?php echo $mime_type; ?>"
<?php endif; ?>
				/>
<?php
endif; // GRAIN_POSTTYPE_PHOTO
?>				
		</item>
<?php	
		endforeach;

?>

	</channel>
</rss><?php
		
		// flush it
		grain_finish_buffering();

	}

	
?>