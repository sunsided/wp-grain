<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Post Screen Hooks
	
	@since 0.3 (R113)
	@package Grain Theme for WordPress
	@subpackage Post Options
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));
	
	// load the post option class
	@require_once(TEMPLATEPATH . '/func/postoptions.php');

/* functions */

	/**
	 * The internal "Write Post" screen hook class
	 * @global GrainPostscreenHook $__GrainPostscreenHook 
	 * @name $__GrainPostscreenHook 
	 * @access private
	 * @since 0.3
	 */
	$__GrainPostscreenHook = new GrainPostscreenHook();

	/**
	 * GrainPostscreenHook class
	 *
	 * This class creates the options on the Write Post screen
	 *
	 * @since 0.3
	 */
	class GrainPostscreenHook {

		/**
		 * Determines if the option box is closed by default
		 * @var bool $closedByDefault
		 */
		var $closedByDefault = TRUE;

		/**
		 * A string containing a localized text stating that HTML is not allowed for a field.
		 * @var string $no_HTML
		 */
		var $no_HTML;
		
		/**
		 * A string containing a localized text stating that HTML is allowed for a field.
		 * @var string $HTML_allowed
		 */
		var $HTML_allowed;

		/**
		 * An array mapping types to names
		 * @var array $typeNameMap
		 */
		var $typeNameMap;

		/**
		 * GrainPostscreenHook() - Constructor
		 *
		 * @since 0.3
		 * @access private
		 */
		function GrainPostscreenHook() 
		{
			$this->no_HTML 			= __("No HTML here", "grain");
			$this->HTML_allowed 	= __("HTML allowed here", "grain");
		
			// create type/name map
			$this->typeNameMap = array(
					GRAIN_POSTTYPE_PHOTO		 => "photoblog",
					GRAIN_POSTTYPE_SPLITPOST	 => "splitpost"
				);
		
			// hook it
			if( grain_is_yapb_installed() ) {
				add_filter('edit_form_advanced', 	array(&$this, 'inject_editform_options'));		
				add_action('save_post', 			array(&$this, 'on_save_post'), 100, 2);
				add_action('admin_head', 			array(&$this, 'admin_pagestyle'));	
			}
			
			// open in dev build
			if( GRAIN_THEME_VERSION_DEVBUILD ) $this->closedByDefault = FALSE;
		}
		
		/**
		 * on_save_post() - Callback when a post is being saved
		 *
		 * @since 0.3
		 * @access private
		 */
		function on_save_post($post_ID, $post) 
		{
			global $GrainPostOpt;
			//echo "<pre>";print_r($_REQUEST);echo "</pre>";
			// auf $_POST kieken
			
			// load the options for the current post
			$GrainPostOpt->load_options($post_ID);
			
			// now loop through all fields in the POST request and check if we can handle them
			$transmitted = $_POST;
			foreach($transmitted as $field => $value) 
			{
				// check if we know the field
				$field = strtoupper($field);
				if( !$GrainPostOpt->is_defined($field) ) continue;
				
				// set the value.
				$GrainPostOpt->set($field, $value, $post_ID);
			}
			
			// at the end, write the value
			$GrainPostOpt->save_options($post_ID);
		}

		/**
		 * inject_editform_options() - Injects a options box on the "write post" screen
		 *
		 * @since 0.3
		 * @uses inject_editform_js() To inject JavaScripts needed by the options
		 */
		function inject_editform_options() 
		{
			global $post;
			
			// Get the content
			$boxTitle = __("Grain: Options For This Post", "grain");
			
			if( GRAIN_THEME_VERSION_DEVBUILD ) {
				if( $post->ID == 0 ) 
					$info = "(new post)";
				else
					$info = "(#$post->ID)";
				$boxTitle .= " ".'<span style="font-size: small; font-family: monospace;">'.$info.'</span>';
			}
			
			// Shall be box be closed?
			$closed = $this->closedByDefault;
			
			// Get the options
			$optionRowsMarkup = $this->generate_option_rows_markup("left");
			if( empty($optionRowsMarkup) ) {
				$optionRowsMarkup = '<span class="grain_notice">'.__("There a currently no advanced options for this post.", "grain").'</span>';
				$closed = TRUE; // close the box, since there is nothing to do anyway
			}
			
			// Get CSS classes
			$cssClasses = array();
			$cssClasses[] = "postbox";
			if( $closed ) $cssClasses[] = "closed";
			$cssClasses = implode(" ", $cssClasses);
			
			// Build the markup ...
			$optionBox  = "<div id=\"graindiv\" class=\"$cssClasses\">";
			$optionBox .= "	<h3>$boxTitle</h3>";
			$optionBox .= "	<div id=\"grainoptions\" class=\"inside\">";
			$optionBox .= "		$optionRowsMarkup";
			$optionBox .= "	</div>";
			$optionBox .= "</div>";
						
			// Inject it
			$this->inject_editform_js($optionBox);
}

		/**
		 * generate_option_rows_markup() - Generates the markup for all options
		 *
		 * @since 0.3
		 * @uses generate_options() To generate the individual option markups
		 * @return string A string of all options
		 */
		function generate_option_rows_markup() 
		{
			$optionfields = $this->generate_options();
			if( count($optionfields) == 0 ) return NULL;
			
			$rows = array(); $i=0;
			foreach($optionfields as $field => $options) {
			
				$display = "none";
				if( $field == "general" ) $display = "block";
			
				$rows[] = "<div style=\"display:$display;\" id=\"grainopt-set-".$field."\"><fieldset>";
				$rows[] = "<legend>".$options["label"]."</legend>";
			
				if(!empty($options) && !empty($options["fields"]))
				{
					foreach($options["fields"] as $option) {
						if( empty($option) ) continue;
				
						// build the markup
						$markup  = '<div id="option-'.(++$i).'" class="grain_option_row">';
						$markup .= $option;
						$markup .= "</div>";
						
						// append
						$rows[] = $markup;
					}
				}
				
				$rows[] = "</fieldset></div>";
			}
			
			// Return the rows
			return implode("", $rows);
		}
		
		/**
		 * generate_options() - Generates the markup for all options
		 *
		 * @since 0.3
		 * @global $GrainOpt Grain option store
		 * @return array An array of strings containing the markup for the individual options
		 */
		function generate_options() 
		{
			global $GrainOpt;
		
			// Prepare
			$optionMarkups = array();
			$optionMarkups["general"]["label"] = __("General options", "grain");

			// Post type
			$values = array(
					GRAIN_POSTTYPE_PHOTO		 => "the uploaded photo",
					GRAIN_POSTTYPE_SPLITPOST	 => "user defined content"
					);
			$optionMarkups["general"]["fields"][] = $this->get_postopt_combobox(GRAIN_POSTOPT_POSTTYPE, NULL, $values, __("I want Grain to show", "grain"), NULL, __('See <a target="_blank" href="http://wp-grain.wiki.sourceforge.net/Post+Types">this wiki page</a> for more information about post types.', "grain") );
			
			// Photoblog-specific options
			$optionMarkups["photoblog"]["label"] = __("Photoblog post options", "grain");
			
			// Generate the options
			if( $GrainOpt->is(GRAIN_EXIF_VISIBLE) ) $optionMarkups["photoblog"]["fields"][] = $this->get_postopt_checkbox(GRAIN_POSTOPT_HIDE_EXIF, NULL, __("Hide EXIF data", "grain"));
			
			// return the options
			return $optionMarkups;
		}
		

		/**
		 * get_postopt_checkbox() - Gets the HTML markup for a post options checkbox
		 *
		 * @since 0.3
		 * @param string 	$postOption			The related post option
		 * @param string 	$additionalCSS		A list of additional CSS classes. May be NULL
		 * @param string 	$shortDesc				A short description of the field. This will be shown next to the checkbox.
		 * @param string 	$title					The title of the field
		 */
		function get_postopt_checkbox($postOption, $additionalCSS, $shortDesc, $title=NULL) 
		{
			global $GrainPostOpt;
		
			// Sanity check
			if(empty($postOption))  throw (new ErrorException("Post option key must not be empty."));
			if(!$GrainPostOpt->is_defined($postOption))  throw (new ErrorException("Post option key \"$postOption\" was undefined."));
		
			// Get a field name, if unset
			$htmlFieldName = strtolower($postOption);
			$htmlFieldID = strtolower($postOption);
			if( empty($title) ) $title = $shortDesc;
		
			// Get the field's value
			$value = $GrainPostOpt->getDefault($postOption);
			global $post;
			if( !empty($post) && !empty($post->ID) ) $value = $GrainPostOpt->get($postOption, $post->ID, FALSE);
		
			// Is the field checked?
			$checked = NULL;
			if( $value ) $checked = ' checked="checked" ';
		
			// Generate the markup
			$markup  = '<span id="'.$htmlFieldID.'_option">';
			$markup .= '<input type="hidden"   id="'.$htmlFieldID.'_zerovalue" name="'.$htmlFieldName.'"                      value="0" />';
			$markup .= '<input type="checkbox" id="'.$htmlFieldID.'"           name="'.$htmlFieldName.'" class="'.$additionalCSS.'" value="1" title="'.$title.'" '.$checked.'/>';
			$markup .= '<label id="'.$htmlFieldID.'_label" class="'.$additionalCSS.'" for="'.$htmlFieldID.'">'.$shortDesc.'</label>';
			$markup .= '</span>';
			
			// return the markup
			return $markup;
		}
		
		/**
		 * get_postopt_line() - General function to inject a text input field
		 *
		 * @since 0.3
		 *
		 * @param string $optionName 		The internal option key
		 * @param string $lineCSS 			General CSS class to be assigned to the input element
		 * @param string $cssClass 			Additional CSS classes to be assigned to the input element
		 * @param string $title 				The readable name of the option
		 * @param string $quickInfo 		A short description of the option
		 * @param string $descriptionLine 	Optional. A detailed description of the option
		 */
		function get_postopt_line($optionName, $lineCSS, $cssClass, $title, $quickInfo, $descriptionLine = NULL ) 
		{
			global $GrainPostOpt;
		
			// Sanity check
			if(empty($optionName))  throw (new ErrorException("Post option key must not be empty."));
			if(!$GrainPostOpt->is_defined($optionName))  throw (new ErrorException("Post option key \"$postOption\" was undefined."));
			
			$value = htmlentities($GrainPostOpt->get($optionName, FALSE));

			// Get a field name, if unset
			$htmlFieldName = strtolower($optionName);
			$htmlFieldID = strtolower($optionName);
			if( empty($title) ) $title = $quickInfo;

			// get css class
			$classes = $lineCSS;
			if(!empty($cssClass)) $classes .= ' '.$cssClass;
			
			// begin option line
			$markup  = '<div id="'.$htmlFieldName.'_line" class="optionline '.$lineCSS.'">';
			
			// add input handler
			$handler = "";
			if( @$GrainOpt->option_defs[$optionName]["TYPE"] == "INT" ) {
				$handler = 'onKeyPress="return grain_numbersonly(this, event)"';
				if( empty($quickInfo ) || $quickInfo == $this->no_HTML ) $quickInfo = __("(numbers only)", "grain");
			}
			
			// write input
			$markup .= '	<label class="'.$classes.' leftbound" 	id="'.$htmlFieldID.'_label" for="'.$htmlFieldName.'">'.$title.'</label>';
			$markup .= '	<input class="'.$classes.'" 			id="'.$htmlFieldID.'" type="text" '.$handler.' name="'.$htmlFieldName.'" value="'.$value.'" />';
			
			// quickinfo
			if( !empty($quickInfo) ) {
				$class = "quickinfo";
				if( $quickInfo == $this->HTML_allowed ) {
					$class .= " html-allowed";
					$quickInfo = "<span title=\"".$this->HTML_allowed."\">(HTML &radic;)</span>";
				}
				else if( $quickInfo == $this->no_HTML ) {
					$class .= " no-html";
					$quickInfo = "<strike title=\"".$this->no_HTML."\">(HTML)</strike>";
				}

				$markup .= '	<span class="'.$class.'" id="'.$htmlFieldName.'_info">'.$quickInfo.'</span>';
			}
			
			// description line
			if( !empty($descriptionLine) ) {
				$markup .= '	<div class="description input_pad" id="'.$htmlFieldID.'_desc">'.$descriptionLine.'</div>';
			}
			
			// end option line
			$markup .= '</div>';
			return $markup;
		}
		
		/**
		 * get_postopt_longline() - Injects a long text input field
		 *
		 * @since 0.3
		 * @uses get_postopt_line() Create the input element
		 *
		 * @param string $optionName 		The internal option key
		 * @param string $cssClass 			Additional CSS classes to be assigned to the input element
		 * @param string $title 				The readable name of the option
		 * @param string $quickInfo 		A short description of the option
		 * @param string $descriptionLine 	Optional. A detailed description of the option
		 */
		function get_postopt_longline($optionName, $cssClass, $title, $quickInfo, $descriptionLine = NULL ) 
		{
			$this->get_postopt_line($optionName, "longline", $cssClass, $title, $quickInfo, $descriptionLine );
		}
		
		/**
		 * get_postopt_shortline() - Injects a short text input field
		 *
		 * @since 0.3
		 * @uses get_postopt_line() Create the input element
		 *
		 * @param string $optionName 		The internal option key
		 * @param string $cssClass 			Additional CSS classes to be assigned to the input element
		 * @param string $title 				The readable name of the option
		 * @param string $quickInfo 		A short description of the option
		 * @param string $descriptionLine 	Optional. A detailed description of the option
		 */
		function get_postopt_shortline($optionName, $cssClass, $title, $quickInfo, $descriptionLine = NULL ) 
		{
			$this->get_postopt_line($optionName, "shortline", $cssClass, $title, $quickInfo, $descriptionLine );
		}		

		/**
		 * get_postopt_combobox() - General function to inject a combobox input field
		 *
		 * This function is used to create a combobox. It is important to notice that the $values parameter
		 * is an associative array that maps option values to their readable names, i.e.
		 * <code>
		 * $values = array( 0 => "zero things", 1 => "one thing" );
		 * </code>
		 *
		 * @since 0.3
		 *
		 * @param string $optionName 		The internal option key
		 * @param string $cssClass 			Additional CSS classes to be assigned to the input element
		 * @param array  $values 			An associative array value ==> key of the values to be displayed
		 * @param string $title 				The readable name of the option
		 * @param string $quickInfo 		Optional. A short description of the option
		 * @param string $descriptionLine 	Optional. A detailed description of the option
		 */
		function get_postopt_combobox($optionName, $cssClass, $values, $title, $quickInfo = NULL, $descriptionLine = NULL ) 
		{
			global $GrainPostOpt, $post;
		
			// Sanity check
			if(empty($optionName))  throw (new ErrorException("Post option key must not be empty."));
			if(!$GrainPostOpt->is_defined($optionName))  throw (new ErrorException("Post option key \"$postOption\" was undefined."));
			
			$optionvalue = ($post->ID == 0) ? $GrainPostOpt->getDefault($optionName, FALSE) : $GrainPostOpt->get($optionName, FALSE);

			// Get a field name, if unset
			$htmlFieldName = strtolower($optionName);
			$htmlFieldID = strtolower($optionName);
			if( empty($title) ) $title = $quickInfo;

			// get css class
			$classes = "combobox";
			if(!empty($cssClass)) $classes .= ' '.$cssClass;
			
			// begin option line
			$markup  = '<div id="'.$htmlFieldID.'_line" class="optionline '.$classes.'">'.PHP_EOL;
			
			// write input
			$markup .= '	<label id="'.$htmlFieldID.'_label" class="'.$classes.' leftbound" for="'.$htmlFieldName.'">'.$title.'</label>'.PHP_EOL;	
			
			// loop all entries
			if(!empty($values))
				$markup .= '	<select class="'.$classes.'" name="'.$htmlFieldName.'" id="'.$htmlFieldID.'">'.PHP_EOL;
			else
				$markup .= '	<select disabled="disabled" class="'.$classes.'" name="'.$htmlFieldName.'" id="'.$htmlFieldID.'">'.PHP_EOL;
			if( !empty($values) ) 
			{
				foreach ($values as $fieldvalue => $text) 
				{
					if( $fieldvalue == $optionvalue )
						$markup .= '		<option id="'.$htmlFieldID.'-'.md5($fieldvalue).'" value="'.$fieldvalue.'" selected="selected">'.$text.'</option>'.PHP_EOL;
					else
						$markup .= '		<option id="'.$htmlFieldID.'-'.md5($fieldvalue).'" value="'.$fieldvalue.'">'.$text.'</option>'.PHP_EOL;
				}
			}
			$markup .= '	</select>'.PHP_EOL;
			
			// quickinfo
			if( !empty($quickInfo) ) {
				$markup .= '	<span class="'.$classes.'" id="'.$htmlFieldID.'_info">'.$quickInfo.'</span>';
			}
			
			// description line
			if( !empty($descriptionLine) ) {
				$markup .= '	<div class="description input_pad" id="'.$htmlFieldID.'_desc">'.$descriptionLine.'</div>'.PHP_EOL;
			}
			
			// end option line
			$markup .= '</div>'.PHP_EOL;
			return $markup;
		}
		
		/**
		 * admin_pagestyle() - Injects the CSS used for the write post screen
		 *
		 * @since 0.3
		 * @access private
		 */
		function admin_pagestyle() {
		?>
		<style type="text/css"><!--
			#grain_postopt_posttype option {
				padding-left: 20px;
				height: 20px;
			}
		
			#grain_postopt_posttype-cfcd208495d565ef66e7dff9f98764da {
				background: transparent url(<?php echo GRAIN_TEMPLATE_DIR; ?>/images/posttypes/photo.png) no-repeat top left;
			}

			#grain_postopt_posttype-d3d9446802a44259755d38e6d163e820 {
				background: transparent url(<?php echo GRAIN_TEMPLATE_DIR; ?>/images/posttypes/splitpost.gif) no-repeat top left;
			}
		--></style>
		<?php
		}

		/**
		 * inject_editform_js() - Injects a javascript to enhance the edit form options
		 *
		 * @since 0.3
		 * @access private
		 * @param string $optionBox		The option box to inject
		 */
		function inject_editform_js($optionBox) 
		{
			if(!class_exists("YapbUtils")) return;
		
			?>
			<script type="text/javascript">
			/* <![CDATA[ */

				// Borrowed from the Yapb JavaScript Injection Script

				(function($) {

					function grainEnhanceForm() {
						$('#yapbdiv').after('<?php echo YapbUtils::escape($optionBox); ?>');
						$('#grain_postopt_posttype').change(grainToogleFieldsets);
					}

					function grainToogleFieldsets() {
						$('#grainopt-set-general').show();
						var selectedType = $('#grain_postopt_posttype').val();
				<?php
					foreach($this->typeNameMap as $type => $key) {
						// GRAIN_POSTTYPE_PHOTO		 => "photoblog",
						// GRAIN_POSTTYPE_SPLITPOST	 => "splitpost"
						?>
						if( selectedType == <?php echo "$type"; ?> ) {
							$('#grainopt-set-<?php echo $key; ?>').slideDown(250);
						}
						else {
							$('#grainopt-set-<?php echo $key; ?>').slideUp(250);
						}
						<?php
					}
				?>
					}

					grainEnhanceForm();
					grainToogleFieldsets();

				})(jQuery);

			/* ]]> */
			</script>
			<?php
		}
	}

?>