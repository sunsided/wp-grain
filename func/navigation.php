<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	GrainNavigation class
	
	@package Grain Theme for WordPress
	@subpackage Navigation
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* Options class */

	// define the main option key
	define( 'GRAIN_OPTIONS_KEY', 'grain_theme' );
	define( 'GRAIN_OPTION_KEY', GRAIN_OPTIONS_KEY );

	/**
	 * An instance of the Grain navigation class
	 * @global GrainNavigation $GrainNav
	 * @name $GrainNav
	 */
	$GrainNav = new GrainNavigation();

	/**
	 * GrainNavigation class
	 *
	 * @since 0.3
	 */
	class GrainNavigation {
		
		/**
		 * Initializes the class
		 * @access private
		 * @since 0.3
		*/
		function GrainNavigation() {
		}
		
			
	} // GrainNavigation
	
	/**
	 * GrainNavigationItem class
	 *
	 * @since 0.3
	 */
	class GrainNavigationItem {
		
		/**
		 * States whether the current page is a content page
		 * @access private
		 * @var bool TRUE if this page is considered a content page
		 * @since 0.3
		*/
		var $isContentPage;
		
		/**
		 * The number of published posts
		 * @access private
		 * @var int The number of published posts
		 * @since 0.3
		*/
		var $postCount;
		
		/**
		 * The number of comments on the current post
		 * @access private
		 * @var int The number of comments on the current post
		 * @since 0.3
		*/
		var $commentCount;
		
		/**
		 * Constructor. Initializes the class.
		 * @access private
		 * @uses is_single()
		 * @uses is_home()
		 * @uses grain_getpostcount()
		 * @uses grain_getcommentcount()
		 * @since 0.3
		*/
		function GrainNavigationItem() {
			$this->isContentPage = is_single() || is_home();
			$this->postCount = grain_getpostcount();
			$this->commentCount = grain_getcommentcount();
		}
		
		/**
		 * Gets the database key
		 * @access private
		 * @since 0.3
		 * @return string the database key
		*/
		function getKey() {
			throw (new ErrorException("Not implemented"));
		}
		
		/**
		 * Gets the displayable text
		 * @access private
		 * @since 0.3
		 * @return string the text
		*/
		function getDisplayText() {
			throw (new ErrorException("Not implemented"));
		}
		
		/**
		 * Gets the target URL
		 * @access private
		 * @since 0.3
		 * @return string the URL
 		 */
		function getURL() {
			throw (new ErrorException("Not implemented"));
		}
			
		/**
		 * Gets the link class
		 * 
		 * The link class can be <pre>postlink</pre>, <pre>pagelink</pre>, <pre>infolink</pre> or <pre>userlink</pre>
		 *
		 * @access private
		 * @since 0.3
		 * @return string the link class
		*/
		function getLinkClass() {
			throw (new ErrorException("Not implemented"));
		}
			
	} // GrainNavigationItem
	
	// $item = new GrainNavigationItem();

?>