<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/

	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* functions */

	function grain_weighted_categories($smallest=10, $largest=48, $unit="pt", $exclude='')
	{
		/*
		Based on Weighted Categories 1.2 (http://hitormiss.org/archives/2004/12/20/weighted-categories-list-in-wordpress/)
		by Matt Kingston (http://www.hitormiss.org/)
		*/

		$cats = get_categories('orderby=name&order=asc&hide_empty=true');

		foreach ($cats as $cat)
		{
			$catlink = get_category_link($cat->cat_ID);
			$catname = $cat->cat_name;
			$count = $cat->category_count;
			$counts{$catname} = $count;
			$catlinks{$catname} = $catlink;
		}
		$spread = max($counts) - min($counts); 
		if ($spread <= 0) { $spread = 1; };
		$fontspread = $largest - $smallest;
		$fontstep = $spread / $fontspread;
		if ($fontspread <= 0) { $fontspread = 1; }
		foreach ($counts as $catname => $count)
		{
			$catlink = $catlinks{$catname};
			print "<a href=\"$catlink\" title=\"$count entries\" style=\"font-size: ".
			($smallest + ($count/$fontstep))."$unit;\">$catname</a> \n";
		}
	}

	function grain_randompost( $message ) {
		
		/*
		Based on RandomPost 0.1 (http://wordpress.gaw2006.de/wordpress-plugin-random-post.html)
		by Dominik Menke (http://dominik.gaw2006.de/)
		*/

		global $wpdb, $tableposts, $post;

		$query = "SELECT count(ID) as c
			FROM $tableposts
			WHERE post_status = 'publish' AND ID <> ".$post->ID;
		$cnt   = $wpdb->get_results($query);
		$cnt   = rand(0, $cnt[0]->c);

		$query = "SELECT ID, post_title
			FROM $tableposts
			WHERE post_status = 'publish'
			LIMIT $cnt, 1";

		$post = $wpdb->get_results($query);

		return '<a id="random-post" rel="bookmark" href="'. get_permalink($post[0]->ID) .'" title="'.$post[0]->post_title.'">'.$message.'</a>';
	}

	function grain_mostcommented( $limit = 10 ) {
		
		/*
		Based on WP-MostCommentedPosts 0.1 (http://ja.rafi.pl/2006/05/01/wp-most-commented-posts/)
		by Rafal "RAFi" Krawczyk (http://ja.rafi.pl/)
		*/

		global $wpdb, $post;
		
		$query = "SELECT 
				$wpdb->posts.ID,
				post_title, 
				post_name, 
				post_date,
				COUNT($wpdb->comments.comment_post_ID) AS 'comment_total' 
			FROM $wpdb->posts 
			LEFT JOIN $wpdb->comments ON $wpdb->posts.ID = $wpdb->comments.comment_post_ID 
			WHERE
				comment_approved = '1' AND
				post_date_gmt < '".gmdate("Y-m-d H:i:s")."' AND 
				post_status = 'publish' AND 
				post_password = '' 
			GROUP BY $wpdb->comments.comment_post_ID 
			ORDER BY comment_total 
			DESC LIMIT $limit";
		$mostcommenteds = $wpdb->get_results( $query );

		$i = 0;
		foreach ($mostcommenteds as $post) {
			$post_title = htmlspecialchars(stripslashes($post->post_title));
			$comment_total = (int) $post->comment_total;
			//echo '<li id="most-commented-'.(++$i).'"><a title="'.$comment_total.' Kommentare" href="'.get_permalink().'">'.$post_title.'</a><span class="post-comment-count">'.$comment_total.'</span></li>';
			echo '<li id="most-commented-'.(++$i).'"><a href="'.get_permalink().'">'.$post_title.'</a><span class="post-comment-count">'.$comment_total.'</span></li>';
		}
	}

?>