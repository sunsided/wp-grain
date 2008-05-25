<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
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

	$no_HTML = __("No HTML here", "grain");
	$HTML_allowed = __("HTML allowed here", "grain");

/* Menu building functions */

	// this object holds the values that are allowed to be saved from the currently loaded page

	function grain_admin_start_page() 
	{
		$_SESSION["__grain_admin_options"] = array();
	}

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

	function grain_admin_longline($optionName, $fieldName, $cssClass, $title, $quickInfo, $descriptionLine = NULL ) 
	{
		grain_admin_line($optionName, $fieldName, "longline", $cssClass, $title, $quickInfo, $descriptionLine );
	}

	function grain_admin_shortline($optionName, $fieldName, $cssClass, $title, $quickInfo, $descriptionLine = NULL ) 
	{
		grain_admin_line($optionName, $fieldName, "shortline", $cssClass, $title, $quickInfo, $descriptionLine );
	}

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

	function grain_admin_checkbox($optionName, $fieldName, $cssClass, $title, $quickInfo, $descriptionLine = NULL ) 
	{
		global $GrainOpt, $HTML_allowed, $no_HTML;
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

	function grain_admin_combobox($optionName, $fieldName, $cssClass, $values, $title, $quickInfo = NULL, $descriptionLine = NULL ) 
	{
		global $GrainOpt, $HTML_allowed, $no_HTML;
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
	
	function grain_admin_pageselector($optionName, $fieldName, $cssClass, $title, $quickInfo = NULL, $descriptionLine = NULL ) 
	{
		$pages = get_pages();
		$pageList = array();
		foreach($pages as $page) {		
			$pageList[$page->ID] = $page->post_title;
		}
		grain_admin_combobox($optionName, $fieldName, $cssClass, $pageList, $title, $quickInfo, $descriptionLine);
	
	}
	
	function grain_admin_sizeboxes($optionName1, $fieldName1, $optionName2, $fieldName2, $cssClass, $title, $unit, $descriptionLine = NULL ) 
	{
		global $GrainOpt, $HTML_allowed, $no_HTML;
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

/* known menus */

	$knownPagesList = array( "copyright", "general", "styling", "datetime", "navigation" );
	$knownPage = in_array( $_GET['page'], $knownPagesList );

	@require_once(TEMPLATEPATH . '/admin/page.copyright.php');
	@require_once(TEMPLATEPATH . '/admin/page.general.php');
	@require_once(TEMPLATEPATH . '/admin/page.styling.php');
	@require_once(TEMPLATEPATH . '/admin/page.datetime.php');
	@require_once(TEMPLATEPATH . '/admin/page.navigation.php');

/* top level menu */

	function grain_admin_createmenus() 
	{	
		$baseTitle = __("Configure Grain", "grain") . ' &raquo; ';

		add_theme_page(	$baseTitle . __("Copyright Settings", "grain"), 		
						__("Copyright Settings", "grain"), 
						'edit_themes', 
						'copyright', 
						'grain_adminpage_copyright');
		
		add_theme_page( $baseTitle . __("General Settings", "grain"), 			
						__("General Settings", "grain"), 
						'edit_themes', 
						'general', 
						'grain_adminpage_general');
		
		add_theme_page( $baseTitle . __("Navigation Settings", "grain"), 		
						__("Navigation Bar", "grain"), 
						'edit_themes', 
						'navigation', 
						'grain_adminpage_navigation');
		
		add_theme_page( $baseTitle . __("Styling and Layout", "grain"), 		
						__("Styling and Layout", "grain"), 
						'edit_themes', 
						'styling', 
						'grain_adminpage_styling');
		
		add_theme_page( $baseTitle . __("Date and Time Settings", "grain"), 	
						__("Date and Time", "grain"), 
						'edit_themes', 
						'datetime', 
						'grain_adminpage_datetime');
		
		
		// shortcut to yapb
		if(grain_is_yapb_installed() ) 
		{
			$yapb_page = 'http://' . get_bloginfo('url').'/wp-admin/options-general.php?page=Yapb.class.php';
			add_theme_page( __("Yet Another Photoblog (Plugin Options)", "grain"), 
							__("YAPB", "grain"), 
							'edit_plugins', 
							$yapb_page);
		}
				
		// do the logic
		grain_admin_dologic();

	}

/* functions */

	function grain_admin_dologic() 
	{
		global $GrainOpt, $knownPage, $_SESSION;
	
		if ( $knownPage ) 
		{
			if ( 'save' == $_REQUEST['action'] ) 
			{
				// loop all values
				$allowed_options = $_SESSION["__grain_admin_options"];
				$transmitted = $_REQUEST;
				
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

	function grain_admin_pagestyle() {
		echo "<link rel='stylesheet' href='".GRAIN_TEMPLATE_DIR."/admin/admin.css' type='text/css' />";
	}

	function grain_admin_inject_yapb_msg() 
	{
		if( !grain_is_yapb_installed() )
		?>
			<div id="errormessage" class="error"><p><strong><?php _e("The YAPB plugin could not be found.", "grain"); ?></strong> <a title="<?php _e("Yet Another Photoblog", "grain"); ?>" target="_blank" href="'GRAIN_YAPB_URL.'"><?php _e("Click for more information", "grain"); ?></a></a></p></div>	
		<?php
	}

?>