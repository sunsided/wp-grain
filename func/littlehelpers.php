<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Various helper functions
	
	@package Grain Theme for WordPress
	@subpackage Helper functions
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* little helpers */
	
	if(!defined("PHP_EOL")) define("PHP_EOL", '\n');

	/**
	 * grain_htmlentities() - Entity encodes a string
	 *
	 * @since 0.3
	 * @param string $string A string
	 * @return string entity encoded string
	 */
	function grain_htmlentities($string) 
	{
		return htmlentities($string, ENT_QUOTES, GRAIN_CONTENT_CHARSET, FALSE);
	}

	/**
	 * grain_rmlinebreaks() - Removes breaks from a string
	 *
	 * This is a really naive approach to remove BR tags from a string
	 *
	 * @since 0.3
	 * @param string $string A string
	 * @return string Input string without BR tags
	 */
	function grain_rmlinebreaks($string) 
	{
		if( function_exists("str_ireplace") ) return str_ireplace(array('<br />', '<br/>', '<br>'), '', $string);		
		return preg_replace("#<br\s*/?>#i", "", $string);
	}

	/**
	 * The count of published posts
	 *
	 * This value will be set upon a call to grain_getpostcount()
	 *
	 * @see grain_getpostcount()
	 * @global string $__grain_post_count
	 * @name $__grain_post_count
	 * @access private
	 */
	$__grain_post_count = -1;
	
	/**
	 * grain_getpostcount() - Gets the count of published posts
	 *
	 * Kudos to: <a href="http://perishablepress.com/press/2006/08/28/display-total-number-of-posts/">http://perishablepress.com/press/2006/08/28/display-total-number-of-posts/</a>
	 *
	 * @since 0.3
	 * @uses $wpdb	WordPress Database object
	 * @return int Count of published posts
	 */
	function grain_getpostcount() 
	{
		global $wpdb; global $__grain_post_count;
		if( $__grain_post_count < 0 ) $__grain_post_count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post';");
		return $__grain_post_count;
	}
	
	/**
	 * The count of published pages
	 *
	 * This value will be set upon a call to grain_getpagecount()
	 *
	 * @see grain_getpagecount()
	 * @global string $__grain_page_count
	 * @name $__grain_page_count
	 * @access private
	 */
	$__grain_page_count = -1;
	
	/**
	 * grain_getpagecount() - Gets the count of published pages
	 *
	 * Kudos to: <a href="http://perishablepress.com/press/2006/08/28/display-total-number-of-posts/">http://perishablepress.com/press/2006/08/28/display-total-number-of-posts/</a>
	 *
	 * @since 0.3
	 * @uses $wpdb	WordPress Database object
	 * @return int Count of published posts
	 */
	function grain_getpagecount() 
	{
		global $wpdb; global $__grain_page_count;
		if( $__grain_page_count < 0 ) $__grain_page_count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'page';");
		return $__grain_page_count;
	}

	/**
	 * grain_is_yapb_installed() - Checks if YAPB is installed
	 *
	 * @since 0.3
	 * @uses $yapb	Global YAPB object
	 * @return bool TRUE if YAPB was found, FALSE otherwise
	 */
	function grain_is_yapb_installed() 
	{
		global $yapb;
		return isset($yapb) && !empty($yapb);
	}
	
	/**
	 * grain_set_ispopup() - Sets the GRAIN_IS_POPUP define
	 *
	 * @since 0.3
	 * @access private
	 * @see grain_ispopup()
	 * @param bool $popup	Optional. FALSE if this is not a popup. (Defaults to TRUE)
	 */
	function grain_set_ispopup($popup=true) {
		define("GRAIN_IS_POPUP", $popup);
	}
	
	/**
	 * grain_ispopup() - Checks if this is a popup
	 *
	 * This function tests the GRAIN_IS_POPUP define.
	 *
	 * @since 0.3
	 * @return bool TRUE if this function was called on a comments popup
	 */
	function grain_ispopup() {
		if(defined("GRAIN_IS_POPUP")) return GRAIN_IS_POPUP;
		return false;	
	}
	
	/**
	 * grain_start_buffering() - Starts the output buffering
	 *
	 * @since 0.3
	 * @access private
	 */
	function grain_start_buffering() 
	{
		// enable output buffering
		$can_compress = extension_loaded('zlib') && !ini_get('zlib.output_compression');
		if( $can_compress ) 
			@ob_start("ob_gzhandler");
		else 
			ob_start();
			
		ob_implicit_flush(FALSE);
	}
	
	/**
	 * grain_finish_buffering() - Stops buffering and sends the output buffer, cleating it
	 *
	 * @since 0.3
	 * @access private
	 */
	function grain_finish_buffering() 
	{
		// send the content length
		if(!headers_sent()) header("Content-Length: ".ob_get_length());
		
		// flush it
		ob_end_flush();
	}

	/**
	 * grain_set_expires_header() - Sets the "Expires" header if it is not already set
	 *
	 * The current behavior is to set the expires header to the last modification date
	 * plus one day (or so), assuming that this photoblog will publish one post a day.
	 *
	 * @since 0.3
	 * @access private
	 * @uses get_lastpostmodified() 	Gets the last modification date
	 */
	function grain_set_expires_header() 
	{
		if(headers_sent()) return;
	
		// set the time
		$expires_after_hours = 12;
		$expires_after_seconds = 3600 * $expires_after_hours;
	
		// caching
		$stamp = get_lastpostmodified('GMT');
		$expires_stamp = date("D, d M Y H:i:s +0000", strtotime ($stamp) + $expires_after_seconds ). " GMT";
	
		header("Expires: $expires_stamp");
	
		// check if it already exists
		$headers = headers_list();	
		foreach($headers as $header) {
			if( substr(strtolower($header), 0, 8) == "expires:" ) return;
		}
		
		// add the header
		header("Expires: $expires_stamp");
	}
	
	
	// load the FirePHP extension
	if( defined("GRAIN_THEME_VERSION_DEVBUILD") && GRAIN_THEME_VERSION_DEVBUILD && file_exists(TEMPLATEPATH . '/lib/FirePHPCore/fb.php') )
	{
		@require_once(TEMPLATEPATH . '/lib/FirePHPCore/fb.php');
		
		/**
		 * grain_log() - Wrapper around the FirePHP fb() logging function 
		 *
		 * @since 0.3
		 * @uses fb() 	FirePHP logging function
		 */
		function grain_log() {
			global $GrainOpt;
			if(!$GrainOpt->is(GRAIN_DEBUG_LOGGING)) return;
			$args = func_get_args();
			return call_user_func_array('fb',$args);
		}
	}
	else {
		// send it to oblivion
		function grain_log() {}
	}

?>