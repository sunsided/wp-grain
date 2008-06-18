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
		var $option_defs;
		
		/**
		 * Meta field key
		 * @access private
		 * @var string Constant string containing the name of the post's "meta" field containing Grain'S post options
		 * @since 0.3
		*/
		var $metaOptionKey = "GRAIN_POST_OPTIONS";
		
		/**
		 * Initializes the class
		 * @access private
		 * @since 0.3
		*/
		function GrainPostOption() {
			$this->option_defs = array();
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
			$keyName = internalDefineOptionName($keyName);
			$this->option_defs[$keyName] = array( 
						"FIELD" => $dbFieldName, 
						"TYPE" => "STR", 
						"FILTER" => $callFilter, 
						"DEFAULT" => $defaultValue, 
						"HTML" => $canBeHTML,
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
			$keyName = internalDefineOptionName($keyName);
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
			$keyName = internalDefineOptionName($keyName);
			$this->option_defs[$keyName] = array( 
						"FIELD" => $dbFieldName, 
						"TYPE" => "BOOL", 
						"FILTER" => FALSE, 
						"DEFAULT" => $defaultValue, 
					);
		}
		
			
	} // GrainPostOption

/* Load options */

	@require_once(TEMPLATEPATH . '/func/postoptionskeys.php');

?>