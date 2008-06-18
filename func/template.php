<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Template functions
	
	@package Grain Theme for WordPress
	@subpackage Template
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* Required files */
	
	@require_once(TEMPLATEPATH . '/func/options.php');

/* Additional filters */

	@require_once(TEMPLATEPATH . '/func/filters.php');

/* Template functions */
	
	if( !function_exists("is_private") ) {
	
		/**
		 * is_private() - Checks whether the current post is private
		 *
		 * @deprecated
		 * @return bool TRUE if the current post is marked as private
		 */
		function is_private() 
		{
			global $post;
			if( empty($post) ) return false;
			return $post->post_status == "private";
		}	
	}
	
	/**
	 * grain_inject_photopage_error() - Injects an error message that happened on a photo page
	 *
	 * This function is called when no alternative ("whoops") image was found, or if it
	 * was disabled.
	 *
	 * The GRAIN_PHOTO_PAGE_ERROR_TITLE filter is applied and the GRAIN_PHOTO_PAGE_ERROR
	 * is raised after the HTML markup was injected.
	 *
	 * @param string $message The message to show
	 */
	function grain_inject_photopage_error($message) 
	{
		// set and filter
		$html = '<div id="photo-page-error"><h2 class="errormessage">'.$message.'</h2></div>';
		$html = apply_filters(GRAIN_PHOTO_PAGE_ERROR_TITLE, $html);
		
		// display error
		echo $html;
		do_action(GRAIN_PHOTO_PAGE_ERROR);
	}
	
	/**
	 * grain_inject_error_searchform() - Injects an error message that happened while using the search
	 *
	 * This function is called when a search ended without success, i.e. the user tried to
	 * visit an URL or searched for something that didn't exist.
	 *
	 * The GRAIN_SEARCHFORM_ERROR_TITLE and GRAIN_SEARCHFORM_ERROR_MESSAGE filters are applied 
	 * and the GRAIN_AFTER_SEARCH_FORM is raised after the HTML markup was injected.
	 *
	 * @param string $message The message to show
	 */
	function grain_inject_error_searchform() 
	{	
		global $s, $post;	// will be (possibly) used in searchform.php
	
		// get the message
		$title = __("Not found.", "grain");
		$message = __("Sorry, but you are looking for something that isn't here.", "grain");
			
		// display title and form
		echo '<div id="photo-page-error">';
		echo apply_filters(GRAIN_SEARCHFORM_ERROR_TITLE, '<h2 class="errortitle">'.$title.'</h2>');
		echo apply_filters(GRAIN_SEARCHFORM_ERROR_MESSAGE, '<div class="errormessage">'.$message.'</div>');
		include (TEMPLATEPATH . '/searchform.php');
		echo '</div>';
		do_action(GRAIN_AFTER_SEARCH_FORM);
	}	
	
	/**
	 * grain_get_copyright_string() - Gets a string with the copyright message
	 *
	 * @since 0.3
	 * @param bool $extended Optional. Set to TRUE if markup can be used (i.e. in the footer)
	 * @return string The copyright message
	 */
	function grain_get_copyright_string($extended = FALSE) 
	{
		global $GrainOpt;
		
		// get values
		$copysign = $extended ? '&copy;' : '(C)';
		$years = grain_copyright_years_ex();
		$key = $extended ? GRAIN_COPYRIGHT_HOLDER_HTML : GRAIN_COPYRIGHT_HOLDER;
		$copyrightString = $GrainOpt->get($key);
		
		// compose the string
		$string = $copysign;
		if(!empty($years)) $string .= ' '.$years;
		if(!empty($copyrightString)) $string .= ' '.$copyrightString;
		
		// return
		return $string;
	}
	
	/**
	 * grain_embed_copyright() - Injects the copyright message
	 *
	 * @since 0.3
	 * @param bool $html Optional. Set to TRUE if markup can be used (i.e. in the footer)
	 */
	function grain_embed_copyright($html = FALSE) 
	{
		echo grain_get_copyright_string($html);
	}
	
	/**
	 * grain_copyright_years() - Gets the copyright years
	 *
	 * If you need to make sure that the returned string contains no HTML markup,
	 * use * @see grain_copyright_years_ex() instead.
	 * The GRAIN_COPYRIGHT_YEARS is applied.
	 *
	 * @since 0.3
	 * @see grain_copyright_years_ex()
	 * @return string A string containing the copyright years
	 */
	function grain_copyright_years() 
	{	
		global $GrainOpt;
		
		// get options
		$start_year = $GrainOpt->get(GRAIN_COPYRIGHT_START_YEAR);
		$end_year = $GrainOpt->get(GRAIN_COPYRIGHT_END_YEAR);
		$end_year_offset = $GrainOpt->get(GRAIN_COPYRIGHT_END_OFFSET);
				
		// add offset
		$end_year = ($end_year + $end_year_offset);

		// setup delimiter
		$year_delimiter = '<span id="copyright-year-delimiter">-</span>';
				
		// compose value
		$value = $end_year . $year_delimiter . $start_year;
		
		// test special cases
		if( $end_year > $start_year ) $value = $start_year . $year_delimiter . $end_year;
		else if( $start_year == $end_year ) $value = $start_year;

		// apply filters and return		
		return apply_filters(GRAIN_COPYRIGHT_YEARS, $value);
	}
	
	/**
	 * grain_copyright_years_html() - Alias for grain_copyright_years()
	 *
	 * If you need to make sure that the returned string contains no HTML markup,
	 * use * @see grain_copyright_years_ex() instead.
	 * The GRAIN_COPYRIGHT_YEARS is applied.
	 *
	 * @since 0.3
	 * @see grain_copyright_years()
	 * @see grain_copyright_years_ex()
	 * @return string A string containing the copyright years
	 */
	function grain_copyright_years_html() 
	{	
		return grain_copyright_years();
	}
	
	/**
	 * grain_copyright_years_ex() - Gets the copyright years without HTML markup
	 *
	 * The difference to grain_copyright_years() is that no HTML markup is returned here.
	 * The GRAIN_COPYRIGHT_YEARS_EX is applied.
	 *
	 * @since 0.3
	 * @see grain_copyright_years()
	 * @return string A string containing the copyright years
	 */
	function grain_copyright_years_ex() 
	{			
		global $GrainOpt;
		
		// get options
		$start_year = $GrainOpt->get(GRAIN_COPYRIGHT_START_YEAR);
		$end_year = $GrainOpt->get(GRAIN_COPYRIGHT_END_YEAR);
		$end_year_offset = $GrainOpt->get(GRAIN_COPYRIGHT_END_OFFSET);
		
		// add offset
		$end_year = ($end_year + $end_year_offset);

		// setup delimiter -- NO HTML CODE HERE!
		$year_delimiter = '-';
		
		// compose value
		$value = $end_year . $year_delimiter . $start_year;
		if( $end_year > $start_year ) $value = $start_year . $year_delimiter . $end_year;
		if( $start_year == $end_year ) $value = $start_year;

		return apply_filters(GRAIN_COPYRIGHT_YEARS_EX, $value);
	}
	
	/**
	 * grain_copyright_years_plain() - Alias for grain_copyright_years_ex()
	 *
	 * The difference to grain_copyright_years() is that no HTML markup is returned here.
	 * The GRAIN_COPYRIGHT_YEARS_EX is applied.
	 *
	 * @since 0.3
	 * @see grain_copyright_years()
	 * @see grain_copyright_years_ex()
	 * @return string A string containing the copyright years
	 */
	function grain_copyright_years_plain() 
	{			
		return grain_copyright_years_ex();
	}
	
/* Eye candy helper */	

	/**
	 * grain_get_gravatar_uri() - Gets the URL for a gravatar image for the current commenter
	 *
	 * @since 0.2
	 * @param string $rating		Optional. The minimum rating of the gravatar ("G", ...)
	 * @param int $size			Optional. The size of the gravatar (in pixels)
	 * @param string $default	Optional. URL to a default image if the user had no Gravatar
	 * @param int $border		Optional. Border size (?)
	 * @return string An URL to the Gravatar image of the current commenter
	 */
	function grain_get_gravatar_uri($rating = NULL, $size = NULL, $default = NULL, $border = NULL) {
		global $comment;
		$uri = "http://www.gravatar.com/avatar.php?gravatar_id=".md5(trim(strtolower($comment->comment_author_email)));
		if($rating !== NULL) 	$uri .= "&amp;rating=".strtolower($rating);
		if($size !== NULL) 		$uri .="&amp;size=".intval($size);
		if($default !== NULL)	$uri .= "&amp;default=".urlencode(trim(strtolower($default)));
		if($border !== NULL)	$uri .= "&amp;border=".intval($border);
		return $uri;
	}

/* Helper: Popups */

	/**
	 * grain_thumbnail_title() - Gets a HTML entity title value for use with the internal "simple" tooltip system
	 *
	 * @since 0.3
	 * @see grain_compose_post_tooltips()
	 * @param string $title		The title of the tooltip
	 * @param string $text		The text of the tooltip
	 * @return string A string to be used in a HTML entities' "title" attribute
	 */
	function grain_thumbnail_title($title, $text) {
		$title = htmlspecialchars($title);
		$text = htmlspecialchars($text);
		
		// replace breaking characters
		$search  = array( '[', ']' );
		$replace = array( '(', ')' );
		$title = str_replace($search, $replace, $title);
		$text = str_replace($search, $replace, $text);
		
		// generate tooltip text
		return 'cssbody=[tooltip-text-prev] cssheader=[tooltip-title-prev] header=['.$title.'] body=['.$text.']';
	}

	/**
	 * grain_compose_post_tooltips() - Gets HTML entity title values for use with the current tooltip system
	 *
	 * @since 0.3
	 * @uses grain_thumbnail_title()	To generate tooltips using the "simple" system
	 * @param string $message_left		Text for the next post
	 * @param string $message_right		Text for the right post
	 * @param string $addon				Optional. Title for the tooltip
	 * @return array Associative array of the tooltips for the previous and next post
	 */
	function grain_compose_post_tooltips($message_left, $message_right, $addon=NULL ) {
		global $GrainOpt, $post;
		
		if(!$GrainOpt->getYesNo(GRAIN_EYECANDY_USE_MOOTIPS)) {
			$title_prev = grain_thumbnail_title($post->post_title. $addon, $message_left);
			$title_next = grain_thumbnail_title($post->post_title. $addon, $message_right);
		}
		else
		{
			$title_prev = $post->post_title. ' :: '.$message_left;
			$title_next = $post->post_title. ' :: '.$message_right;
		}
		
		// return 
		return array( "prev" => $title_prev, "next" => $title_next );
	}

/* Mosaic related */

	/*
		Thanks for the support to Johannes Jarolim (http://johannes.jarolim.com)
	*/

	function grain_mocaic_current_page() {
		global $wp_query;
		if( $wp_query->query_vars['paged'] != '' ) return $wp_query->query_vars['paged'];
		return 1;
	}

	/**
	 * grain_mosaic_ppl() - Injects a link to the previous mosaic/archive page
	 *
	 * @since 0.1.2
	 * @see grain_get_mosaic_ppl()
	 * @param string $title				Text of the link
	 * @param int $page					Current page number
	 * @param int $count_per_page		Number of entries per page
	 */
	function grain_mosaic_ppl( $title, $page, $count_per_page ) {
		echo grain_get_mosaic_ppl( $title, $page, $count_per_page );
	}

	/**
	 * grain_get_mosaic_ppl() - Gets a link to the previous mosaic/archive page
	 *
	 * @since 0.3
	 * @param string $title				Text of the link
	 * @param int $page					Current page number
	 * @param int $count_per_page		Number of entries per page
	 * @return string HTML markup for the link
	 */
	function grain_get_mosaic_ppl( $title, $page, $count_per_page ) {
		if( $count_per_page <= 0 ) return;
		global $wpdb, $tableposts;

		$query = "SELECT count(ID) as c ".
			"FROM $tableposts ".
			"WHERE post_status = 'publish'";
		$count   = $wpdb->get_results($query);

		if( $count[0]->c <= $page*$count_per_page ) return;
		return '<a class="prev-page" rel="prev" href="'.get_pagenum_link($page+1).'">'.$title.'</a>';
	}

	/**
	 * grain_mosaic_npl() - Injects a link to the next mosaic/archive page
	 *
	 * @since 0.1.2
	 * @see grain_get_mosaic_npl()
	 * @param string $title				Text of the link
	 * @param int $page					Current page number
	 * @param int $count_per_page		Number of entries per page
	 */
	function grain_mosaic_npl( $title, $page, $count_per_page ) {
		echo grain_get_mosaic_npl( $title, $page, $count_per_page );
	}
	
	/**
	 * grain_get_mosaic_npl() - Gets a link to the next mosaic/archive page
	 *
	 * @since 0.3
	 * @param string $title				Text of the link
	 * @param int $page					Current page number
	 * @param int $count_per_page		Number of entries per page
	 * @return string HTML markup for the link
	 */
	function grain_get_mosaic_npl( $title, $page, $count_per_page ) {
		if( $page <= 1 ) return;
		if( $count_per_page <= 0 ) return;
		return '<a class="next-page" rel="next" href="'.get_pagenum_link($page-1).'">'.$title.'</a>';
	}
	
/* Date and time related */

	/**
	 * grain_filter_dt() - Filters a date/time format string before it is passed to the PHP date() function
	 *
	 * This function allows for easy character escaping using curly braces
	 *
	 * @param string $format				The format
	 * @return string The filtered format
	 */
	function grain_filter_dt($format) {	
		$escape_mode = 0;
		$is_escaped = 0;
		$output = '';
		for($i = 0; $i<strlen($format); ++$i) {
			$char = $format[$i];

			// something => something
			// {something} => \s\o\m\e\t\h\i\n\g
			// \{ something => \{ something
			// { \{ something} => \{ \s\o\m\e\t\h\i\n\g
			
			// if char is an escape character
			if( $char == '\\' ) {
				$is_escaped = 1;		// set escape flag
				$output .= $char;		// output character
				continue;				// loop
			}		
			else {
				// if we are in local escaping mode, just output the character
				if($is_escaped) {
					$output .= $char;	// output character
					$is_escaped = 0;	// unflag local escaping
					continue;
				}
				else {
					// if we are in escape mode we got the close tag
					if( $escape_mode && $char == '}' ) {
						$escape_mode = 0;
						continue;
					}
					// else if we are not in escape mode and got the open tag
					elseif( !$escape_mode && $char == '{' ) {
						$escape_mode = 1;
						continue;
					}
					// else we are in escape mode
					elseif( $escape_mode ) {
						$output .= '\\'.$char;
						continue;
					}
					// or else we are not in escape mode
					else {
						$output .= $char;
						continue;
					}
				}
			}		
		}
		
		return $output;
	}

?>