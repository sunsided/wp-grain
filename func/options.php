<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* Options class */

	// define the main option key
	define( 'GRAIN_OPTIONS_KEY', 'grain_theme' );
	define( 'GRAIN_OPTION_KEY', GRAIN_OPTIONS_KEY );

	// Singleton
	$GrainOpt = new GrainOption();

	// Optionsklasse
	class GrainOption {
		
		// die Optionen
		var $option_defs;
		var $options;
		
		function GrainOption() {
			$this->option_defs = array();
			$this->loadOptions();
		}
		
		// defines a string option
		function defineStringOpt($keyName, $dbFieldName, $defaultValue=NULL, $canBeHTML = FALSE, $callFilter=TRUE) 
		{		
			define( $keyName, $keyName );
			$this->option_defs[$keyName] = array( 
						"FIELD" => $dbFieldName, 
						"TYPE" => "STR", 
						"FILTER" => $callFilter, 
						"DEFAULT" => $defaultValue, 
						"HTML" => $canBeHTML,
					);
		}
		
		// defines an integer option
		function defineValueOpt($keyName, $dbFieldName, $defaultValue=-1)
		{
			define( $keyName, $keyName );
			$this->option_defs[$keyName] = array( 
						"FIELD" => $dbFieldName, 
						"TYPE" => "INT", 
						"FILTER" => FALSE, 
						"DEFAULT" => $defaultValue, 
						"HTML" => FALSE,
					);
		}
		
		// defines a boolean option
		function defineFlagOpt($keyName, $dbFieldName, $defaultValue=FALSE) 
		{
			define( $keyName, $keyName );
			$this->option_defs[$keyName] = array( 
						"FIELD" => $dbFieldName, 
						"TYPE" => "BOOL", 
						"FILTER" => FALSE, 
						"DEFAULT" => $defaultValue, 
						"HTML" => FALSE, 
					);
		}
		
		// checks if an options exists
		function exists($keyName) 
		{
			return !empty($this->option_defs) && array_key_exists($keyName, $this->option_defs );
		}
		
		// checks if an option is empty (without applying filters or defaults)
		function isempty($keyName) 
		{
			if( !$this->exists($keyName) ) {
				throw new ErrorException("Option key ".$keyName." was unknown.");
			}

			$option = $this->option_defs[$keyName];
			return empty($this->options[$option["FIELD"]]);
		}
		
		function getDefault($keyName) 
		{	
			if(!$this->exists($keyName)) {
				throw new ErrorException("Option key ".$keyName." was unknown.");
			}
			
			// get option and value
			$option = $this->option_defs[$keyName];			
			return $option["DEFAULT"];
		}
		
		function value_isEnabled($value) 
		{				
			if( empty($value) ) return FALSE;
			else if( $value === FALSE ) return FALSE;
			else if( $value === 0 ) return FALSE;
			else if( $value === '' ) return FALSE;
			else if( $value === 'off' ) return FALSE;
			else if( $value === 'false' ) return FALSE;
			else if( $value === 'no' ) return FALSE;
			else if( $value === '-' ) return FALSE;
			else if( $value === 'n' ) return FALSE;
			else if( $value === 'f' ) return FALSE;
			return TRUE;
		}
		
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
			}
		
			// return
			return $value;
		}
		
		function getFormatted($keyName, $skipFilter=FALSE) 
		{
			$value = $this->get($keyName, $skipFiler);
			return attribute_escape($value);
		}
		
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
				
		function getYesNo($keyName) 
		{
			if($this->option_defs[$keyName]["TYPE"] != "BOOL") {
				throw new ErrorException("Type for ".$keyName." was no bool");	
			}
			return $this->getForCheckbox($keyName);
		}
		
		function is($keyName) 
		{
			return $this->getYesNo($keyName);
		}
		
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
			
		function loadOptions() 
		{
			$this->options = get_option( GRAIN_OPTIONS_KEY );
		}
			
		function writeOptions() 
		{
			update_option( GRAIN_OPTIONS_KEY, $this->options );
		}
			
	} // GrainOpt

/* Load options */

	@require_once(TEMPLATEPATH . '/func/optionskeys.php');

?>