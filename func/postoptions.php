<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	GrainPostOption class
	
	@package Grain Theme for WordPress
	@subpackage Post Options
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* Options class */

	/**
	 * An instance of the Grain options class
	 * @global GrainOption $GrainOpt
	 * @name $GrainOpt
	 */
	$GrainPostOpt = new GrainPostOption();

	/**
	 * GrainPostOption class
	 *
	 * This class manages the configuration options for Grain.
	 *
	 * @since 0.3
	 */
	class GrainPostOption {
		
		/**
		 * Option definitions
		 * @access private
		 * @var array An associative array containing the definitions for Grain's configuration options
		 * @since 0.3
		*/
		var $option_defs = array();
		
		/**
		 * Metadata key
		 * @access private
		 * @var string A string containing the key for the "Custom Fields" field
		 * @since 0.3
		*/
		var $metaOptionKey = "_GRAIN_POST_OPTIONS";
		
		/**
		 * Cached post metadata
		 * @access private
		 * @var array An array that caches the metadata for posts
		 * @since 0.3
		*/
		var $pageCache = array();
		
		/**
		 * Initializes the class
		 * @access private
		 * @since 0.3
		*/
		function GrainPostOption() {
		}
		
		/**
		 * internalDefineOptionName() - Corrects and defines post option key
		 *
		 * The input is transformed so that it is upper case and starts with
		 * "GRAIN_POSTOPT_" and then define()'d.
		 *
		 * @since 0.3
		 * @access private
		 * @param string $keyName 			The option's key.
		 * @return string new option name
		 */
		function internalDefineOptionName($keyName) 
		{		
			$keyName = strtoupper($keyName);
			if( substr($keyName, 0, strlen("GRAIN_POSTOPT_")) != "GRAIN_POSTOPT_" ) 
			{
				if( substr($keyName, 0, strlen("GRAIN_")) == "GRAIN_" ) {
					$keyName = "GRAIN_POSTOPT_" . substr($keyName, strlen("GRAIN_"));
				}
				else 
				{
					$keyName = "GRAIN_POSTOPT_" . $keyName;
				}
			}
			
			define( $keyName, $keyName );
			return $keyName;
		}
		
		/**
		 * defineStringOpt() - Defines an option with a value of a string type
		 *
		 * When an option is created, a global define will be set. This define is always
		 * the upper case version of the $keyName value. Options are always addressed using
		 * this define.
		 * Note that while the $keyName should be descriptive, the $dbFieldName value should
		 * be as short as possible and must be unique (within the Grain option definitions).
		 *
		 * @since 0.3
		 * @param string $keyName 			The option's key.
		 * @param string $dbFieldName 		The name of the field as stored in the database. Keep it short, but understandable.
		 * @param string $defaultValue 		Optional. The default value. (Defaults to NULL)
		 * @param bool $canBeHTML 			Optional. Set to TRUE if HTML tags are allowed in this option's value.
		 * @param bool $callFilter 			Optional. Set to FALSE if no call to apply_filter() shall be raised when retrieving this option.
		 */
		function defineStringOpt($keyName, $dbFieldName, $defaultValue=NULL, $canBeHTML = FALSE, $callFilter=TRUE) 
		{		
			$keyName = $this->internalDefineOptionName($keyName);
			$this->option_defs[$keyName] = array( 
						"FIELD" => $dbFieldName, 
						"TYPE" => "STR", 
						"FILTER" => $callFilter, 
						"DEFAULT" => $defaultValue, 
						"HTML" => $canBeHTML
					);
		}
		
		/**
		 * defineValueOpt() - Defines an option with a value of a numeric (integer) type
		 *
		 * When an option is created, a global define will be set. This define is always
		 * the upper case version of the $keyName value. Options are always addressed using
		 * this define.
		 * Note that while the $keyName should be descriptive, the $dbFieldName value should
		 * be as short as possible and must be unique (within the Grain option definitions).
		 *
		 * Note that by default zero is considered to be a valid value, while negative numbers
		 * aren't. When a negative value is set for a field where negative values are not allowed,
		 * it will be mapped to it's default. Same thing for zero.
		 * Always check for negative values when retrieving the value!
		 *
		 * @since 0.3
		 * @param string $keyName 			The option's key.
		 * @param string $dbFieldName 		The name of the field as stored in the database. Keep it short, but understandable.
		 * @param int $defaultValue 		Optional. The default value. (Defaults to -1)
		 * @param bool $allowNegative 		Optional. Set to TRUE if negative values are allowed.
		 * @param bool $allowZero 			Optional. Set to FALSE if zero is not a valid value.
		 */
		function defineValueOpt($keyName, $dbFieldName, $defaultValue=-1, $allowNegative=FALSE, $allowZero=TRUE)
		{
			$keyName = $this->internalDefineOptionName($keyName);
			$this->option_defs[$keyName] = array( 
						"FIELD" => $dbFieldName, 
						"TYPE" => "INT", 
						"FILTER" => FALSE, 
						"DEFAULT" => $defaultValue, 
						"ALLOW_NEGATIVE" => $allowNegative,
						"ALLOW_ZERO" => $allowZero
					);
		}
		
		/**
		 * defineFlagOpt() - Defines an option with a value of a boolean type
		 *
		 * When an option is created, a global define will be set. This define is always
		 * the upper case version of the $keyName value. Options are always addressed using
		 * this define.
		 * Note that while the $keyName should be descriptive, the $dbFieldName value should
		 * be as short as possible and must be unique (within the Grain option definitions).
		 *
		 * @since 0.3
		 * @param string $keyName 			The option's key.
		 * @param string $dbFieldName 		The name of the field as stored in the database. Keep it short, but understandable.
		 * @param bool $defaultValue 		Optional. The default value. (Defaults to FALSE)
		 */
		function defineFlagOpt($keyName, $dbFieldName, $defaultValue=FALSE) 
		{
			$keyName = $this->internalDefineOptionName($keyName);
			$this->option_defs[$keyName] = array( 
						"FIELD" => $dbFieldName, 
						"TYPE" => "BOOL", 
						"FILTER" => FALSE, 
						"DEFAULT" => $defaultValue
					);
		}
		
		/**
		 * exists() - Checks whether an option definition exists
		 *
		 * @since 0.3
		 * @access private
		 * @param string $keyName 			The option's key.
		 * @return bool True if the option definition is set
		 */
		function exists($keyName) 
		{
			return !empty($this->option_defs) && array_key_exists($keyName, $this->option_defs );
		}
		
		/**
		 * is_defined() - Alias for exists()
		 *
		 * @since 0.3
		 * @access private
		 * @uses exists()
		 * @param string $keyName 			The option's key.
		 * @return bool True if the option definition is set
		 */
		function is_defined($keyName) 
		{
			return $this->exists($keyName);
		}
			
		/**
		 * has_options() - Checks if a post has post options assigned
		 *
		 * If no $postID is given, the current post is assumed.
		 *
		 * @since 0.3
		 * @param int $postID 				Optional. The ID of the post to check
		 * @return bool TRUE if the post has Grain post options assigned
		 */
		function has_options($postID=NULL) 
		{
			// sanity check
			if( $postID <= 0 || empty($postID))  {
				global $post;
				$postID = $post->ID;
			}
			
			// sanity check, part 2
			if( empty($postID) ) return FALSE;
			
			// get the metadata for the post and checks if our key exists
			$keys = get_post_custom_keys($postID);
			return in_array($this->metaOptionKey, $keys);
		}

		/**
		 * get_options() - Gets the options for a post
		 *
		 * If no $postID is given, the current post is assumed.
		 *
		 * @since 0.3
		 * @access private
		 * @param int $postID 				Optional. The ID of the post to check
		 * @return array An associative array of the meta options keys to their values
		 */
		function get_options($postID=NULL) 
		{		
			// sanity check
			if( $postID <= 0 || empty($postID))  {
				global $post;
				$postID = $post->ID;
			}

			// sanity check, part 2
			if( empty($postID) ) return array();
			
			// return cached data, if any
			if( array_key_exists($postID, $this->pageCache) ) return $this->pageCache[$postID];
			
			// get and cache the data
			$data = get_post_meta($postID, $this->metaOptionKey, TRUE);					
			$this->pageCache[$postID] = $data;
			return $data;
		}
		
		/**
		 * load_options() - Alias for get_otions()
		 *
		 * If no $postID is given, the current post is assumed.
		 *
		 * @since 0.3
		 * @access private
		 * @uses get_options()
		 * @param int $postID 				Optional. The ID of the post to check
		 * @return array An associative array of the meta options keys to their values
		 */
		function load_options($postID=NULL) 
		{	
			return $this->get_options($postID);
		}
		
		/**
		 * save_options() - Writes the options for the current post to the database
		 *
		 * If no $postID is given, the current post is assumed. The options are
		 * taken from the internal cache.
		 *
		 * @since 0.3
		 * @access private
		 * @param int $postID 				Optional. The ID of the post to check
		 */
		function save_options($postID=NULL) 
		{		
			// sanity check
			if( $postID <= 0 || empty($postID))  {
				global $post;
				$postID = $post->ID;
			}

			// sanity check, part 2
			if( empty($postID) ) return;
			
			// check if we have values
			if( !array_key_exists($postID, $this->pageCache) ) 
			{
				// no values, so delete the key
				delete_post_meta($postID, $this->metaOptionKey);
			}
			else 
			{
				// values found; First, get them
				$values = $this->pageCache[$postID];
				
				// TODO: Strip invalid values here
				
				// are there values left?
				if( count($values) == 0 ) 
				{
					// if not, delete the key
					delete_post_meta($postID, $this->metaOptionKey);
				}
				else {
					// write them
					add_post_meta($postID, $this->metaOptionKey, $values, true) or 
						update_post_meta($postID, $this->metaOptionKey, $values);
				}
			}
		}
		
		/**
		 * value_isEnabled() - Tests wheter a value is considered being "TRUE" when interpreted as a boolean
		 *
		 * @since 0.3
		 * @access private
		 * @param mixed $value 			The value to test
		 * @return bool FALSE or TRUE, depending on the value.
		 */
		function value_isEnabled($value) 
		{				
			if( empty($value) ) return FALSE;
			else if( $value === FALSE ) return FALSE;
			else if( $value === 0 ) return FALSE;
			
			// treat as string
			$value = strtolower($value);
			if( $value == '' ) return FALSE;
			else if( $value == 'off' ) return FALSE;
			else if( $value == 'false' ) return FALSE;
			else if( $value == 'no' ) return FALSE;
			else if( $value == '-' ) return FALSE;
			else if( $value == 'n' ) return FALSE;
			else if( $value == 'f' ) return FALSE;
			return TRUE;
		}
		
		/**
		 * set() - Set an option's value
		 *
		 * This function sets the value for a given option. Depending on the options'
		 * defined value type, the value will be casted to match that type.
		 *
		 * This function throws an exception if the option definition was not set.
		 *
		 * @since 0.3
		 * @param string $keyName 			The option's key.
		 * @param mixed $value 				The option's value.
		 * @param int $postID				Optional. The post for which to set the value
		 * @param bool $skipLoadOptions 	Optional. Set to TRUE if you know that the options have already been loaded.
		 */
		function set($keyName, $value, $postID = NULL, $skipLoadOptions=FALSE) 
		{
			// sanity check
			if( $postID <= 0 || empty($postID))  {
				global $post;
				$postID = $post->ID;
			}

			// sanity check, part 2
			if( empty($postID) ) throw (new ErrorException("Post option could not be written because no valid post ID was set."));
			if(!$this->exists($keyName)) throw (new ErrorException("Post option key ".$keyName." was unknown."));
			
			// load the options so that we can merge the new value with the old ones
			if( !$skipLoadOptions ) $this->load_options($postID);
			
			// get option and value
			$option = $this->option_defs[$keyName];

			// typecast			
			if($option["TYPE"] == "BOOL") {
				$value = $this->value_isEnabled(strip_tags($value));
			}
			else if($option["TYPE"] == "INT") {
				$value = intval(strip_tags($value));
			}
			else {
				if($option["HTML"]) {
					$value = stripslashes($value);
				}
				else {
					$value = strip_tags(stripslashes($value));
				}
			}
			
			// apply value
			$field = $option["FIELD"];
			$this->pageCache[$postID][$field] = $value;
		}
				
		/**
		 * get() - Gets an option's value
		 *
		 * This function throws an exception if the option definition was not set.
		 *
		 * @since 0.3
		 * @param string $keyName 			The option's key.
		 * @param int $postID				Optional. The post for which to set the value
		 * @param bool $doFilter 			Optional. States wheter the value should be filtered by WordPress. (Defaults to TRUE)
		 * @param bool $skipLoadOptions 	Optional. Set to TRUE if you know that the options have already been loaded.
		 * @return mixed The option's value or it's default, if the option was not set.
		 */
		function get($keyName, $postID = NULL, $doFilter=TRUE, $skipLoadOptions=FALSE) 
		{	
			// sanity check
			if( $postID <= 0 || empty($postID))  {
				global $post;
				$postID = $post->ID;
			}
		
			// sanity check, part 2
			if( empty($postID) ) throw (new ErrorException("Post option could not be written because no valid post ID was set."));
			if(!$this->exists($keyName)) throw (new ErrorException("Post option key \"".$keyName."\" was unknown."));
			
			// load the options so that we can merge the new value with the old ones
			if( !$skipLoadOptions ) $this->load_options($postID);
			
			// get option and value
			$option = $this->option_defs[$keyName];			
			$existed = @array_key_exists($option["FIELD"], $this->pageCache[$postID]);
			$value = @$this->pageCache[$postID][$option["FIELD"]];
			
			// apply default, if necessary
			if(!$existed) {
				$value = $option["DEFAULT"];
			}
			
			// filter
			if($option["FILTER"] && $doFilter) {
				$value = apply_filters($keyName, $value);
			}
		
			// cast, if necessary
			if( $option["TYPE"] == "BOOL" ) {
				$value = $this->value_isEnabled($value);
			}
			else if( $option["TYPE"] == "INT" ) {
				$value = intval($value);
				if( @$option["ALLOW_NEGATIVE"] !== TRUE && $value < 0 ) $value = intval($option["DEFAULT"]);
				if( @$option["ALLOW_ZERO"] !== TRUE && $value == 0 ) $value = intval($option["DEFAULT"]);
			}
		
			// return
			return $value;
		}
		
		/**
		 * getDefault() - Gets the default value for an option
		 *
		 * This function throws an exception if the option definition was not set.
		 *
		 * @since 0.3
		 * @param string $keyName 			The option's key.
		 * @return mixed The default value for an option.
		 */
		function getDefault($keyName) 
		{	
			if(!$this->exists($keyName)) throw (new ErrorException("Option key ".$keyName." was unknown."));
			
			// get option and value
			$option = $this->option_defs[$keyName];			
			return $option["DEFAULT"];
		}
			
	} // GrainPostOption

/* Load options */

	@require_once(TEMPLATEPATH . '/func/postoptionskeys.php');

?>