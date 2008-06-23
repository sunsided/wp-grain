<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Administration menu options for styling settings
	
	@package Grain Theme for WordPress
	@subpackage AJAX responder
*/
	
	if(!defined('GRAIN_ADMINPAGE_LOADED') ) die(basename(__FILE__));
	
	// load AJAX functions
	@require_once(TEMPLATEPATH . '/admin/common.ajax.php');

/* functions */

	add_action('admin_print_scripts', 'grain_adminpage_styling_scripthook' );
	add_action('wp_ajax_grain_get_override_css', 'grain_ajax_get_override_css' );

	/**
	 * grain_ajax_get_override_css() - AJAX responder that refreshes the list of override stylesheets
	 *
	 * @since 0.3
	 * @access private
	 * @global $GrainOpt	Grain option store
	 */
	function grain_ajax_get_override_css() 
	{
		global $GrainOpt;
		$user_selected = $GrainOpt->get(GRAIN_STYLE_OVERRIDE);
		$found_selected = FALSE;
		
		// begin the response
		$response = "/* <![CDATA[ */ (function($) {";

		// remove all options from the combobox
		$response .= "$('#css_override_file').empty();";

		// get the override stylesheets
		$files = grain_get_css_overrides();
		$file_lines = "";
		foreach($files as $file) {
			if( $found_selected || $file != $user_selected )
				$file_lines .= "$('#css_override_file').append('<option value=\"".$file."\">".$file."</option>');";
			else 
			{
				$file_lines .= "$('#css_override_file').append('<option selected=\"selected\" value=\"".$file."\">".$file."</option>');";
				$found_selected = TRUE;
			}
		}
		
		// add "zero" option
		if( $found_selected )
			$response .= "$('#css_override_file').append('<option value=\"-\">".__("none", "grain")."</option>');";
		else
			$response .= "$('#css_override_file').append('<option selected=\"selected\" value=\"-\">".__("none", "grain")."</option>');";

		// add files
		$response .= $file_lines;
			
		// remove ajaxloader
		$response .= "$('#css_override_file_ajaxloader').remove();";
			
		// enable the combobox and the button
		$response .= "$('#css_override_file').removeAttr('disabled');";
		$response .= "$('#refresh_override_css').css('visibility', 'visible');";
		
		// close the response
		$response .= "})(jQuery); /* ]]> */";
		
		// send response
		die($response);
	}

	/**
	 * grain_adminpage_styling_scripthook() - Injects JavaScripts needed for the styling page
	 *
	 * @since 0.3
	 * @access private
	 * @uses wp_print_scripts()	To inject the SACK script
	 */
	function grain_adminpage_styling_scripthook()
	{
	  wp_print_scripts( array( 'sack' ));

	
	?>
	
	<script type="text/javascript" lang="JavaScript">
	//<![CDATA[
	
	function grain_ajax_refresh_override_css( )
	{
		// Disable the box
		grain_enable_element("css_override_file", false);
		grain_hide_element("refresh_override_css", true);		
		grain_show_ajaxloader("css_override_file", true);
				
		// send the AJAX request
		var mysack = new sack("<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php" );    
		mysack.execute = 1;
		mysack.method = 'POST';
		mysack.setVar( "action", "grain_get_override_css" );
		mysack.encVar( "cookie", document.cookie, false );
		mysack.onError = function() 
				{ 
					grain_enable_element("css_override_file", true); 
					grain_hide_element("refresh_override_css", false);
					alert('Ajax while trying to refresh stylesheet list' )
				};
		mysack.runAJAX();

	}
	
	// create the button
	addLoadEvent(grain_overridecssajaxbutton);	
	function grain_overridecssajaxbutton() {
		(function($) {
			$('#css_override_file').after('<input type="button" id="refresh_override_css" class="refreshbutton" value="<?php echo __("refresh", "grain") ?>" title="<?php echo __("reload the stylesheet list", "grain") ?>" />');
			$('#refresh_override_css').click(grain_ajax_refresh_override_css);
		})(jQuery);
	}
	
	//]]>
	</script>
	
	<?php
	}

?>