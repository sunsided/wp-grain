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
		 * GrainPostscreenHook() - Constructor
		 *
		 * @since 0.3
		 * @access private
		 */
		function GrainPostscreenHook() 
		{
			// hook it
			add_filter('edit_form_advanced', array(&$this, 'inject_editform_options'));
			
			// open in dev build
			if( GRAIN_THEME_VERSION_DEVBUILD ) $this->closedByDefault = FALSE;
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
			$boxTitle = __("Grain Options", "grain");
			
			// Get CSS classes
			$cssClasses = array();
			$cssClasses[] = "postbox";
			if( $this->closedByDefault ) $cssClasses[] = "closed";
			$cssClasses = implode(" ", $cssClasses);
			
			// Get the options
			$optionRowsMarkup = $this->generate_option_rows_markup();
			
			// Build the markup ...
			$optionBox = <<<EOT
			<div id="graindiv" class="$cssClasses">
				<h3>$boxTitle</h3>
				<div class="inside">
					$optionRowsMarkup
				</div>
			</div>
EOT;
			
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
			$options = $this->generate_options();
			$rows = array();
			foreach($options as $option) {
				if( empty($option) ) continue;
			
				// build the markup
				$markup  = '<div class="grain_option_row">';
				$markup .= $option;
				$markup .= "</div>";
				
				// append
				$rows[] = $markup;
			}
			
			// Return the rows
			return implode("", $rows);
		}
		
		/**
		 * generate_options() - Generates the markup for all options
		 *
		 * @since 0.3
		 * @return array An array of strings containing the markup for the individual options
		 */
		function generate_options() 
		{
			// Prepare
			$optionMarkups = array();
			
			// Generate the options
			$optionMarkups[] = $this->get_postopt_checkbox("foo", "fooid", "fooname");
			
			// return the options
			return $optionMarkups;
		}
		

		/**
		 * get_postopt_checkbox() - Gets the HTML markup for a post options checkbox
		 *
		 * @since 0.3
		 * @param string 	$postOption			The related post option
		 * @param string 	$htmlFieldID			The ID of the HTML input field
		 * @param string 	$htmlFieldName		Optional. The name of the HTML input field
		 * @param string 	$additionalCSS		Optional. A list of additional CSS classes
		 */
		function get_postopt_checkbox($postOption, $htmlFieldID, $htmlFieldName=NULL, $additionalCSS=NULL) 
		{
			// Sanity check
			if(empty($postOption))  throw new ErrorException("Option key must not be empty.");
			if(empty($htmlFieldID)) throw new ErrorException("Field ID must not be empty.");
		
			// Get a field name, if unset
			if( empty($htmlFieldName) ) $htmlFieldName = $htmlFieldID;
		
			// Get the title, text, description, etc.
			$title = "sumthin";
		
			// Generate the markup
			$markup  = '<span id="'.$htmlFieldID.'_option">';
			$markup .= '<input type="hidden"   id="'.$htmlFieldID.'_zerovalue" name="'.$htmlFieldName.'"                      value="0" />';
			$markup .= '<input type="checkbox" id="'.$htmlFieldID.'"           name="'.$htmlFieldName.'" class="'.$classes.'" value="1" />';
			$markup .= '<label id="'.$htmlFieldID.'_label" class="'.$classes.'" for="'.$htmlFieldID.'">'.$title.'</label>';
			$markup .= '</span>';
			
			// return the markup
			return $markup;
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
			?>
			<script type="text/javascript">
			/* <![CDATA[ */

				// Borrowed from the Yapb JavaScript Injection Script

				(function($) {

					function grainEnhanceForm() {

						// Mutate the form to a fileupload form
						// As usual: Special code for IE
						if (jQuery.browser.msie) $('#post').attr('encoding', 'multipart/form-data');
						else $('#post').attr('enctype', 'multipart/form-data');

						// Ensure proper encoding
						$('#post').attr('acceptCharset', 'UTF-8');

						// Insert the fileupload field
						$('#yapbdiv').after('<?php echo YapbUtils::escape($optionBox); ?>');

					}

					grainEnhanceForm();

				})(jQuery);

			/* ]]> */
			</script>
			<?php
		}
	}

?>