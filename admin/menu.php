<?php 
/*
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Administration menu options
	
	@package Grain Theme for WordPress
	@subpackage Administration Menu
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));
	session_start();
	
/* Options */

	@require_once(TEMPLATEPATH . '/func/options.php');
	define(GRAIN_ADMINPAGE_LOADED, true);

/* Hooks */

	add_action('admin_head', 'grain_admin_pagestyle');	
	add_action('admin_menu', 'grain_admin_createmenus');

/* Some translations */

	/**
	 * A string containing a localized text stating that HTML is not allowed for a field.
	 * @global string $no_HTML
	 * @name $no_HTML
	 */
	$no_HTML = __("No HTML here", "grain");
	
	/**
	 * A string containing a localized text stating that HTML is allowed for a field.
	 * @global string $HTML_allowed
	 * @name $HTML_allowed
	 */
	$HTML_allowed = __("HTML allowed here", "grain");

/* Menu building functions */

	// this object holds the values that are allowed to be saved from the currently loaded page

	/**
	 * grain_admin_start_page() - Prepares the admin option array
	 *
	 * This function prepares the internal array of options that are allowed to be changed
	 * from an options page.
	 * A call to this function must be made before any administration options are displayed.
	 *
	 * @since 0.3
	 * @access private
	 * @uses $_SESSION["__grain_admin_options"] Current options store
	 */
	function grain_admin_start_page() 
	{
		$_SESSION["__grain_admin_options"] = array();
	}

	/**
	 * grain_admin_line() - General function to inject a text input field
	 *
	 * @since 0.3
	 * @uses $GrainOpt Grain options
	 * @global $HTML_allowed 			used to tell that HTML is allowed
	 * @global $no_HTML 					used to tell that HTML is not allowed
	 * @uses $_SESSION["__grain_admin_options"] Current options store
	 *
	 * @param string $optionName 		The internal option key
	 * @param string $fieldName 		The name/ID of the HTML field in the form
	 * @param string $lineCSS 			General CSS class to be assigned to the input element
	 * @param string $cssClass 			Additional CSS classes to be assigned to the input element
	 * @param string $title 				The readable name of the option
	 * @param string $quickInfo 		A short description of the option
	 * @param string $descriptionLine 	Optional. A detailed description of the option
	 */
	function grain_admin_line($optionName, $fieldName, $lineCSS, $cssClass, $title, $quickInfo, $descriptionLine = NULL ) 
	{
		global $GrainOpt, $HTML_allowed, $no_HTML;
		$_SESSION["__grain_admin_options"][$fieldName] = $optionName;
		
		$value = htmlentities($GrainOpt->get($optionName, FALSE));

		// get css class
		$classes = $lineCSS;
		if(!empty($cssClass)) $classes .= ' '.$cssClass;
		
		// begin option line
		echo '<div id="'.$fieldName.'_line" class="optionline '.$lineCSS.'">';
		
		// write input
		echo '	<label id="'.$fieldName.'_label" class="'.$classes.' leftbound" for="'.$fieldName.'">'.$title.'</label>';
		echo '	<input class="'.$classes.'" type="text" name="'.$fieldName.'" id="'.$fieldName.'" value="'.$value.'" />';
		
		// quickinfo
		if( !empty($quickInfo) ) {
			$class = "quickinfo";
			if( $quickInfo == $HTML_allowed ) {
				$class .= " html-allowed";
				$quickInfo = "<span title=\"".$HTML_allowed."\">(HTML &radic;)</span>";
			}
			else if( $quickInfo == $no_HTML ) {
				$class .= " no-html";
				$quickInfo = "<strike title=\"".$no_HTML."\">(HTML)</strike>";
			}

			echo '	<span class="'.$class.'" id="'.$fieldName.'_info">'.$quickInfo.'</span>';
		}
		
		// description line
		if( !empty($descriptionLine) ) {
			echo '	<div class="description input_pad" id="'.$fieldName.'_desc">'.$descriptionLine.'</div>';
		}
		
		// end option line
		echo '</div>';
	}

	/**
	 * grain_admin_longline() - Injects a long text input field
	 *
	 * @since 0.3
	 * @uses grain_admin_line() Create the input element
	 *
	 * @param string $optionName 		The internal option key
	 * @param string $fieldName 		The name/ID of the HTML field in the form
	 * @param string $cssClass 			Additional CSS classes to be assigned to the input element
	 * @param string $title 				The readable name of the option
	 * @param string $quickInfo 		A short description of the option
	 * @param string $descriptionLine 	Optional. A detailed description of the option
	 */
	function grain_admin_longline($optionName, $fieldName, $cssClass, $title, $quickInfo, $descriptionLine = NULL ) 
	{
		grain_admin_line($optionName, $fieldName, "longline", $cssClass, $title, $quickInfo, $descriptionLine );
	}
	
	/**
	 * grain_admin_shortline() - Injects a short text input field
	 *
	 * @since 0.3
	 * @uses grain_admin_line() Create the input element
	 *
	 * @param string $optionName 		The internal option key
	 * @param string $fieldName 		The name/ID of the HTML field in the form
	 * @param string $cssClass 			Additional CSS classes to be assigned to the input element
	 * @param string $title 				The readable name of the option
	 * @param string $quickInfo 		A short description of the option
	 * @param string $descriptionLine 	Optional. A detailed description of the option
	 */
	function grain_admin_shortline($optionName, $fieldName, $cssClass, $title, $quickInfo, $descriptionLine = NULL ) 
	{
		grain_admin_line($optionName, $fieldName, "shortline", $cssClass, $title, $quickInfo, $descriptionLine );
	}

	/**
	 * grain_admin_infoline() - Injects a descriptive text block
	 *
	 * @since 0.3
	 *
	 * @param string $cssClass 			An additional CSS class to be assigned to the text block. If not needed, set to NULL.
	 * @param string $text	 			The text block to be displayed
	 */
	function grain_admin_infoline($cssClass, $text) 
	{		
		// begin option line
		if(empty($cssClass)) {
			echo '<div class="infoline">'.$text.'</div>';
		}
		else
		{
			echo '<div class="infoline $cssClass">'.$text.'</div>';
		}
	}

	/**
	 * grain_admin_checkbox() - Injects a checkbox input field
	 *
	 * @since 0.3
	 * @uses $GrainOpt Grain options
	 * @uses $_SESSION["__grain_admin_options"] Current options store
	 *
	 * @param string $optionName 		The internal option key
	 * @param string $fieldName 		The name/ID of the HTML field in the form
	 * @param string $cssClass 			Additional CSS classes to be assigned to the input element
	 * @param string $title 				The readable name of the option
	 * @param string $quickInfo 		A short description of the option
	 * @param string $descriptionLine 	Optional. A detailed description of the option
	 */
	function grain_admin_checkbox($optionName, $fieldName, $cssClass, $title, $quickInfo, $descriptionLine = NULL ) 
	{
		global $GrainOpt;
		$_SESSION["__grain_admin_options"][$fieldName] = $optionName;
		
		$value = $GrainOpt->getYesNo($optionName, FALSE);
		
		// get css class
		$classes = "checkbox";
		if(!empty($cssClass) && ($classes != $cssClass)) $classes .= ' '.$cssClass;
		
		// begin option line
		echo '<div id="'.$fieldName.'_line" class="optionline '.$lineCSS.'">'.PHP_EOL;
		
		// write input
		echo '	<label id="'.$fieldName.'_label" class="'.$classes.' leftbound" for="'.$fieldName.'_field">'.$title.'</label>'.PHP_EOL;	
		
		// write hidden field
		echo '	<input type="hidden" name="'.$fieldName.'" value="0" />'.PHP_EOL;
		
		// write real field
		if($value)
			echo '	<input type="checkbox" class="'.$classes.'" name="'.$fieldName.'" id="'.$fieldName.'_field" checked="checked" value="1" />'.PHP_EOL;
		else
			echo '	<input type="checkbox" class="'.$classes.'" name="'.$fieldName.'" id="'.$fieldName.'_field" value="1" />'.PHP_EOL;
		
		// quickinfo
		if( !empty($quickInfo) ) {
			echo '	<label for="'.$fieldName.'_field" class="checkbox_text" id="'.$fieldName.'_info">'.$quickInfo.'</label>'.PHP_EOL;
		}
		
		// description line
		if( !empty($descriptionLine) ) {
			echo '	<div class="description input_pad" id="'.$fieldName.'_desc">'.$descriptionLine.'</div>'.PHP_EOL;
		}
		
		// end option line
		echo '</div>'.PHP_EOL;
	}

	/**
	 * grain_admin_multiline() - Injects a multiline text input field
	 *
	 * @since 0.3
	 * @uses $GrainOpt Grain options
	 * @global $HTML_allowed 			used to tell that HTML is allowed
	 * @global $no_HTML 					used to tell that HTML is not allowed
	 * @uses $_SESSION["__grain_admin_options"] Current options store
	 *
	 * @param string $optionName 		The internal option key
	 * @param string $fieldName 		The name/ID of the HTML field in the form
	 * @param string $cssClass 			Additional CSS classes to be assigned to the input element
	 * @param string $title 				The readable name of the option
	 * @param string $descriptionLine 	Optional. A detailed description of the option
	 */
	function grain_admin_multiline($optionName, $fieldName, $cssClass, $title, $descriptionLine = NULL ) 
	{
		global $GrainOpt, $HTML_allowed, $no_HTML;
		$_SESSION["__grain_admin_options"][$fieldName] = $optionName;
		
		$value = htmlentities($GrainOpt->get($optionName, FALSE));

		// get css class
		$classes = "multiline";
		if(!empty($cssClass)) $classes .= ' '.$cssClass;
		
		// begin option line
		echo '<div id="'.$fieldName.'_line" class="optionline '.$lineCSS.'">'.PHP_EOL;
		
		// write input
		echo '	<label id="'.$fieldName.'_label" class="'.$classes.' leftbound" for="'.$fieldName.'">'.$title.'</label>'.PHP_EOL;	
		echo '	<textarea class="'.$classes.'" cols="95" wrap="off" rows="10" name="'.$fieldName.'" id="'.$fieldName.'">'.$value.'</textarea>'.PHP_EOL;
				
		// description line
		if( !empty($descriptionLine) ) {
			echo '	<div class="description input_pad" id="'.$fieldName.'_desc">'.$descriptionLine.'</div>';
		}
		
		// end option line
		echo '</div>';
	}

	/**
	 * grain_admin_combobox() - General function to inject a combobox input field
	 *
	 * This function is used to create a combobox. It is important to notice that the $values parameter
	 * is an associative array that maps option values to their readable names, i.e.
	 * <code>
	 * $values = array( 0 => "zero things", 1 => "one thing" );
	 * </code>
	 *
	 * @since 0.3
	 * @uses $GrainOpt Grain options
	 * @uses $_SESSION["__grain_admin_options"] Current options store
	 *
	 * @param string $optionName 		The internal option key
	 * @param string $fieldName 		The name/ID of the HTML field in the form
	 * @param string $cssClass 			Additional CSS classes to be assigned to the input element
	 * @param array  $values 			An associative array value ==> key of the values to be displayed
	 * @param string $title 				The readable name of the option
	 * @param string $quickInfo 		Optional. A short description of the option
	 * @param string $descriptionLine 	Optional. A detailed description of the option
	 */
	function grain_admin_combobox($optionName, $fieldName, $cssClass, $values, $title, $quickInfo = NULL, $descriptionLine = NULL ) 
	{
		global $GrainOpt;
		$_SESSION["__grain_admin_options"][$fieldName] = $optionName;
		
		$optionvalue = $GrainOpt->get($optionName, FALSE);

		// get css class
		$classes = "combobox";
		if(!empty($cssClass)) $classes .= ' '.$cssClass;
		
		// begin option line
		echo '<div id="'.$fieldName.'_line" class="optionline '.$lineCSS.'">'.PHP_EOL;
		
		// write input
		echo '	<label id="'.$fieldName.'_label" class="'.$classes.' leftbound" for="'.$fieldName.'">'.$title.'</label>'.PHP_EOL;	
		
		// loop all entries
		if(!empty($values))
			echo '	<select class="'.$classes.'" name="'.$fieldName.'" id="'.$fieldName.'">'.PHP_EOL;
		else
			echo '	<select disabled="disabled" class="'.$classes.'" name="'.$fieldName.'" id="'.$fieldName.'">'.PHP_EOL;
		foreach ($values as $fieldvalue => $text) 
		{
			if( $fieldvalue == $optionvalue )
				echo '		<option value="'.$fieldvalue.'" selected="selected">'.$text.'</option>'.PHP_EOL;
			else
				echo '		<option value="'.$fieldvalue.'">'.$text.'</option>'.PHP_EOL;
		}
		echo '	</select>'.PHP_EOL;
		
		// quickinfo
		if( !empty($quickInfo) ) {
			echo '	<span class="'.$class.'" id="'.$fieldName.'_info">'.$quickInfo.'</span>';
		}
		
		// description line
		if( !empty($descriptionLine) ) {
			echo '	<div class="description input_pad" id="'.$fieldName.'_desc">'.$descriptionLine.'</div>'.PHP_EOL;
		}
		
		// end option line
		echo '</div>'.PHP_EOL;
	}
	
	/**
	 * grain_admin_pageselector() - Injects a selection combobox for static pages
	 *
	 * @since 0.3
	 * @uses grain_admin_combobox() Creates the combobox
	 * @uses get_pages() Gets the static pages
	 *
	 * @param string $optionName 		The internal option key
	 * @param string $fieldName 		The name/ID of the HTML field in the form
	 * @param string $cssClass 			Additional CSS classes to be assigned to the input element
	 * @param string $title 				The readable name of the option
	 * @param string $quickInfo 		Optional. A short description of the option
	 * @param string $descriptionLine 	Optional. A detailed description of the option
	 */
	function grain_admin_pageselector($optionName, $fieldName, $cssClass, $title, $quickInfo = NULL, $descriptionLine = NULL ) 
	{
		$pages = get_pages();
		$pageList = array();
		foreach($pages as $page) {		
			$pageList[$page->ID] = $page->post_title;
		}
		grain_admin_combobox($optionName, $fieldName, $cssClass, $pageList, $title, $quickInfo, $descriptionLine);
	
	}
	
	/**
	 * grain_admin_sizeboxes() - Injects two short text input boxes to allow the user to input a size
	 *
	 * This option is used to enter sizes, e.g. width and height of an image.
	 *
	 * @since 0.3
	 * @uses grain_admin_combobox() Creates the combobox
	 * @uses $_SESSION["__grain_admin_options"] Current options store
	 *
	 * @param string $optionName1 		The internal option key for the first value
	 * @param string $fieldName1 		The name/ID of the HTML field in the form for the first value
	 * @param string $optionName2 		The internal option key for the second value
	 * @param string $fieldName2 		The name/ID of the HTML field in the form for the second value
	 * @param string $cssClass 			Additional CSS classes to be assigned to the input element
	 * @param string $title 				The readable name of the option
	 * @param string $unit		 		A text describing the unit that will be displayed next to the boxes, e.g. "px"
	 * @param string $descriptionLine 	Optional. A detailed description of the option
	 */
	function grain_admin_sizeboxes($optionName1, $fieldName1, $optionName2, $fieldName2, $cssClass, $title, $unit, $descriptionLine = NULL ) 
	{
		global $GrainOpt;
		$_SESSION["__grain_admin_options"][$fieldName1] = $optionName1;
		$_SESSION["__grain_admin_options"][$fieldName2] = $optionName2;
		
		$value1 = $GrainOpt->get($optionName1, FALSE);
		$value2 = $GrainOpt->get($optionName2, FALSE);

		// get css class
		$classes = "pixelbox";
		if(!empty($cssClass)) $classes .= ' '.$cssClass;
		
		// begin option line
		echo '<div id="'.$fieldName.'_line" class="optionline '.$lineCSS.'">';
		
		// write input
		echo '	<label id="'.$fieldName.'_label" class="'.$classes.' leftbound" for="'.$fieldName.'">'.$title.'</label>';
		
		echo '	<input maxlength="4" class="'.$classes.'" type="text" name="'.$fieldName1.'" id="'.$fieldName1.'" value="'.$value1.'" />';
		echo '	<span class="separator">'.__("&times;", "grain").'</span>';
		echo '	<input maxlength="4" class="'.$classes.'" type="text" name="'.$fieldName2.'" id="'.$fieldName2.'" value="'.$value2.'" />';
		
		// quickinfo
		if( !empty($unit) ) {
			echo '	<span class="'.$class.'" id="'.$fieldName.'_info">'.$unit.'</span>';
		}
		
		// description line
		if( !empty($descriptionLine) ) {
			echo '	<div class="description input_pad" id="'.$fieldName.'_desc">'.$descriptionLine.'</div>';
		}
		
		// end option line
		echo '</div>';
	}

	/**
	 * grain_admin_spacer() - Injects a vertical spacer
	 *
	 * @since 0.3
	 */
	function grain_admin_spacer() 
	{
		echo '<div class="spacer"></div>';
	}

/* known menus */

	/**
	 * An array of recognized option pages
	 * @global array $knownPagesList
	 * @name $knownPagesList
	 */
	$knownPagesList = array( "copyright", "general", "styling", "datetime", "navigation" );
	
	/**
	 * A boolean to determine whether or not we have recognized a "page" request.
	 * @global bool $knownPage
	 * @name $knownPage
	 */
	$knownPage = in_array( $_GET['page'], $knownPagesList );

	@require_once(TEMPLATEPATH . '/admin/page.copyright.php');
	@require_once(TEMPLATEPATH . '/admin/page.general.php');
	@require_once(TEMPLATEPATH . '/admin/page.styling.php');
	@require_once(TEMPLATEPATH . '/admin/page.datetime.php');
	@require_once(TEMPLATEPATH . '/admin/page.navigation.php');

/* top level menu */

	/**
	 * grain_admin_createmenus() - Hooks the option pages into the WordPress backend
	 *
	 * @since 0.2
	 * @uses add_theme_page() 			Adds a page to WordPress' themes/presentation page
	 * @uses add_options_page()			Adds a page to WordPress' options page
	 * @uses grain_admin_dologic()		Performs the request
	 */
	function grain_admin_createmenus() 
	{	
		$basePageTitle = __("Configure Grain", "grain");
		$baseTitle = $basePageTitle . ' &raquo; ';
	
		// add an options shortcut
		$grain_page = 'themes.php?page=general';
		add_options_page( $basePageTitle,
						"Grain", 
						'edit_themes',
						$grain_page );

		// Add the top menu page
		add_menu_page( $basePageTitle, 
						"Grain", 
						'edit_themes',
						$grain_page );
	
		// add theme pages
		add_theme_page( $baseTitle . __("General Settings", "grain"), 			
						__("General Settings", "grain"), 
						'edit_themes', 
						'general', 
						'grain_adminpage_general');
		
		add_theme_page( $baseTitle . __("Navigation Settings", "grain"), 		
						__("Navigation", "grain"), 
						'edit_themes', 
						'navigation', 
						'grain_adminpage_navigation');
		
		add_theme_page( $baseTitle . __("Styling and Layout", "grain"), 		
						__("Styling and Layout", "grain"), 
						'edit_themes', 
						'styling', 
						'grain_adminpage_styling');
		
		add_theme_page(	$baseTitle . __("Copyright Settings", "grain"), 		
						__("Copyright Settings", "grain"), 
						'edit_themes', 
						'copyright', 
						'grain_adminpage_copyright');		
		
		add_theme_page( $baseTitle . __("Date and Time Settings", "grain"), 	
						__("Date and Time", "grain"), 
						'edit_themes', 
						'datetime', 
						'grain_adminpage_datetime');
		
		
		// shortcut to yapb
		if( grain_is_yapb_installed() ) 
		{
			// http://192.168.0.123/wp-admin/options-general.php?page=Yapb.class.php
			$yapb_page = 'options-general.php?page=Yapb.class.php';
			add_theme_page( __("Yet Another Photoblog (Plugin Options)", "grain"), 
							__("YAPB", "grain"), 
							'edit_plugins', 
							$yapb_page);
		}

		// do the logic
		grain_admin_dologic();

	}

	/**
	 * grain_admin_dologic() - Shows the admin pages and processes requests
	 *
	 * @since 0.2
	 * @global $GrainOpt 		Gets/sets the options
	 * @global $knownPage 		To determine if the page is recognized
	 */
	function grain_admin_dologic() 
	{
		global $GrainOpt, $knownPage, $_SESSION;
	
		if ( $knownPage ) 
		{
			if ( 'save' == $_REQUEST['action'] ) 
			{
				// loop all values
				$allowed_options = $_SESSION["__grain_admin_options"];
				$transmitted = $_POST;
				
				// print_r($transmitted);
				
				foreach($transmitted as $field => $value) 
				{
					// check against registered options
					if(array_key_exists($field, $allowed_options) ) 
					{
						// the field was registered, we may save the value now
						// get the option first
						$related_option = $allowed_options[$field];
						
						// get option descriptor
						$definition = $GrainOpt->option_defs[$related_option];
						
						// set the value - set() takes care of the rest
						$GrainOpt->set($related_option, $value);
					}
				}

				// die();

				// ... and write
				$GrainOpt->writeOptions();

				wp_redirect("themes.php?page=".$_GET['page']."&saved=true");
				die;
			
			} // save
			
		} // known page
		
	}

	/**
	 * grain_admin_pagestyle() - Injects the CSS stylesheet used for the admin pages
	 *
	 * @since 0.3
	 */
	function grain_admin_pagestyle() {
		echo "<link rel='stylesheet' href='".GRAIN_TEMPLATE_DIR."/admin/admin.css' type='text/css' />";
	}

	/**
	 * grain_admin_inject_yapb_msg() - Injects a warning if YAPB is not installed
	 * 
	 * @since 0.3
	 * @uses grain_is_yapb_installed() Determine if the YAPB plugin is installed
	 */
	function grain_admin_inject_yapb_msg() 
	{
		if( !grain_is_yapb_installed() ) 
		{
			?>
			<div id="errormessage" class="error"><p><strong><?php _e("The YAPB plugin could not be found.", "grain"); ?></strong> <a title="<?php _e("Yet Another Photoblog", "grain"); ?>" target="_blank" href="<?php echo GRAIN_YAPB_URL; ?>"><?php _e("Click for more information", "grain"); ?></a></p></div>	
			<?php
		}
	}

?>