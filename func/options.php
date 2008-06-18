<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	GrainOption class
	
	@package Grain Theme for WordPress
	@subpackage Grain Options
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* Options class */

	// define the main option key
	define( 'GRAIN_OPTIONS_KEY', 'grain_theme' );
	define( 'GRAIN_OPTION_KEY', GRAIN_OPTIONS_KEY );

	/**
	 * An instance of the Grain options class
	 * @global GrainOption $GrainOpt
	 * @name $GrainOpt
	 */
	$GrainOpt = new GrainOption();

	/**
	 * GrainOption class
	 *
	 * This class manages the configuration options for Grain.
	 *
	 * @since 0.3
	 */
	class GrainOption {
		
		/**
		 * Option definitions
		 * @access private
		 * @var array An associative array containing the definitions for Grain's configuration options
		 * @since 0.3
		*/
		var $option_defs;
		
		/**
		 * Current configuration options
		 * @access private
		 * @var array An associative array of the configuration option keys mapped to their values
		 * @since 0.3
		*/
		var $options;
		
		/**
		 * Initializes the class
		 * @access private
		 * @since 0.3
		*/
		function GrainOption() {
			$this->option_defs = array();
			$this->loadOptions();
		}
		
		/**
		 * internalDefineOptionName() - Corrects and defines post option key
		 *
		 * The input is transformed so that it is upper case and starts with
		 * "GRAIN_" and then define()'d.
		 *
		 * @since 0.3
		 * @access private
		 * @param string $keyName 			The option's key.
		 * @return string new option name
		 */
		function internalDefineOptionName($keyName) 
		{		
			$keyName = strtoupper($keyName);
			if( substr($keyName, 0, strlen("GRAIN_")) != "GRAIN_" ) 
			{
				$keyName = "GRAIN_" . $keyName;
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
		 * @param string $dbFieldName 		The name of the field as stored in the database. Keep it short.
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
						"HTML" => $canBeHTML,
						"ALLOW_NEGATIVE" => FALSE,
						"ALLOW_ZERO" => FALSE
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
		 * @param string $dbFieldName 		The name of the field as stored in the database. Keep it short.
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
						"HTML" => FALSE,
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
		 * @param string $dbFieldName 		The name of the field as stored in the database. Keep it short.
		 * @param bool $defaultValue 		Optional. The default value. (Defaults to FALSE)
		 */
		function defineFlagOpt($keyName, $dbFieldName, $defaultValue=FALSE) 
		{
			$keyName = $this->internalDefineOptionName($keyName);
			$this->option_defs[$keyName] = array( 
						"FIELD" => $dbFieldName, 
						"TYPE" => "BOOL", 
						"FILTER" => FALSE, 
						"DEFAULT" => $defaultValue, 
						"HTML" => FALSE, 
						"ALLOW_NEGATIVE" => FALSE,
						"ALLOW_ZERO" => FALSE
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
		 * isempty() - Checks whether an option is empty
		 *
		 * This function throws an exception if the option definition was not set.
		 *
		 * @see is_set()
		 * @since 0.3
		 * @access private
		 * @param string $keyName 			The option's key.
		 * @return bool True if the option value is empty
		 */
		function isempty($keyName) 
		{
			if( !$this->exists($keyName) ) {
				throw new ErrorException("Option key ".$keyName." was unknown.");
			}

			$option = $this->option_defs[$keyName];
			return empty($this->options[$option["FIELD"]]);
		}
		
		/**
		 * is_set() - Checks whether an option is set
		 *
		 * If you know that an option exists (this should be the case most of the time)
		 * and want to know wheter it's value is considered empty, the isempty() function
		 * is there for you.
		 *
		 * @see isempty()
		 * @since 0.3
		 * @access private
		 * @param string $keyName 			The option's key.
		 * @return bool True if the option value is set
		 */
		function is_set($keyName) 
		{
			if( !$this->exists($keyName) ) return FALSE;
			$option = $this->option_defs[$keyName];
			return array_kex_exists($option["FIELD"], $this->options);
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
			if(!$this->exists($keyName)) {
				throw new ErrorException("Option key ".$keyName." was unknown.");
			}
			
			// get option and value
			$option = $this->option_defs[$keyName];			
			return $option["DEFAULT"];
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
		 * get() - Gets an option's value
		 *
		 * This function throws an exception if the option definition was not set.
		 *
		 * @since 0.3
		 * @param string $keyName 			The option's key.
		 * @param bool $doFilter 			Optional. States wheter the value should be filtered by WordPress. (Defaults to TRUE)
		 * @return mixed The option's value or it's default, if the option was not set.
		 */
		function get($keyName, $doFilter=TRUE) 
		{	
			if(!$this->exists($keyName)) {
				throw new ErrorException("Option key ".$keyName." was unknown.");
			}
			
			// get option and value
			$option = $this->option_defs[$keyName];			
			$existed = @array_key_exists($option["FIELD"], $this->options);
			$value = @$this->options[$option["FIELD"]];
			
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
		 * getFormatted() - Gets an attribute_escape()'d representation of an option's value
		 *
		 * This function throws an exception if the option definition was not set.
		 *
		 * @since 0.3
		 * @param string $keyName 			The option's key.
		 * @param bool $doFilter 			Optional. States wheter the value should be filtered by WordPress. (Defaults to FALSE)
		 * @return mixed The option's value or it's default, if the option was not set.
		 */
		function getFormatted($keyName, $doFilter=FALSE) 
		{
			$value = $this->get($keyName, $doFiler);
			return attribute_escape($value);
		}
		
		/**
		 * getForCheckbox() - Gets an option's value to be used in a checkbox selection check
		 *
		 * This function throws an exception if the option definition was not set.
		 *
		 * @since 0.3
		 * @param string $keyName 			The option's key.
		 * @return mixed The option's value or it's default, if the option was not set.
		 */
		function getForCheckbox($keyName) 
		{
			if(!$this->exists($keyName)) {
				throw new ErrorException("Option key ".$keyName." was unknown.");
			}
			
			// get option and value
			$option = $this->option_defs[$keyName];		
			$key = $option["FIELD"];
			$array = $this->options;
			$keyExists = !empty($array) && array_key_exists($key, $array);
			if(empty($this->options) || !$keyExists) return $option["DEFAULT"];
			return $this->options[$option["FIELD"]];
		}
				
		/**
		 * getYesNo() - Gets an option's value to be used in a boolean check
		 *
		 * This function throws an exception if the option definition was not set or if the option
		 * was no flag (boolean).
		 *
		 * @since 0.3
		 * @uses getForCheckbox()			Get's the value and throws if the option was not defined.
		 * @param string $keyName 			The option's key.
		 * @return mixed The option's value or it's default, if the option was not set.
		 */
		function getYesNo($keyName) 
		{
			if($this->option_defs[$keyName]["TYPE"] != "BOOL") {
				throw new ErrorException("Type for ".$keyName." was no bool");	
			}
			return $this->getForCheckbox($keyName);
		}
		
		/**
		 * is() - Alias for getYesNo()
		 *
		 * This function throws an exception if the option definition was not set or if the option
		 * was no flag (boolean).
		 *
		 * @since 0.3
		 * @see getYesNo()
		 * @uses getYesNo()					Get's the value
		 * @param string $keyName 			The option's key.
		 * @return mixed The option's value or it's default, if the option was not set.
		 */
		function is($keyName) 
		{
			return $this->getYesNo($keyName);
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
		 */
		function set($keyName, $value) 
		{
			if(!$this->exists($keyName)) {
				throw new ErrorException("Option key ".$keyName." was unknown.");
			}
			
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

			// print $option["FIELD"] . " (".$option["HTML"].") << " . $value . PHP_EOL;
			
			// apply value
			$this->options[$option["FIELD"]] = $value;
		}
			
		/**
		 * loadOptions() - Loads the options from the WordPress database
		 *
		 * @since 0.3
		 * @uses get_option()
		 */
		function loadOptions() 
		{
			$this->options = get_option( GRAIN_OPTIONS_KEY );
		}
			
		/**
		 * writeOptions() - Writes the options to the WordPress database
		 *
		 * @since 0.3
		 * @uses update_option()
		 */
		function writeOptions() 
		{
			update_option( GRAIN_OPTIONS_KEY, $this->options );
		}
			
	} // GrainOpt

/* Load options */

	@require_once(TEMPLATEPATH . '/func/optionskeys.php');

?>