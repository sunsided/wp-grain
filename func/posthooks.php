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
			add_filter('edit_form_advanced', 	array(&$this, 'inject_editform_options'));		
			add_action('save_post', 			array(&$this, 'on_save_post'), 100, 2);
			
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
			
			// Shall be box be closed?
			$closed = $this->closedByDefault;
			
			// Get the options
			$optionRowsMarkup = $this->generate_option_rows_markup();
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
			if( count($options) == 0 ) return NULL;
			
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
		 * @global $GrainOpt Grain option store
		 * @return array An array of strings containing the markup for the individual options
		 */
		function generate_options() 
		{
			global $GrainOpt;
		
			// Prepare
			$optionMarkups = array();
			
			// Generate the options
			if( $GrainOpt->is(GRAIN_EXIF_VISIBLE) ) $optionMarkups[] = $this->get_postopt_checkbox(GRAIN_POSTOPT_HIDE_EXIF, NULL, __("Hide EXIF data", "grain"));
			
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
			// Sanity check
			if(empty($postOption))  throw new ErrorException("Post option key must not be empty.");
			
			global $GrainPostOpt;
			if(!$GrainPostOpt->is_defined($postOption))  throw new ErrorException("Post option key \"$postOption\" was undefined.");
		
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