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

	add_action('admin_print_scripts', 'grain_adminpage_landingzone_scripthook' );
	add_action('wp_ajax_grain_switch_logging', 'grain_ajax_switch_logging' );

	/**
	 * grain_ajax_switch_logging() - AJAX responder that switches and returns the state of the logger
	 *
	 * @since 0.3
	 * @access private
	 * @global $GrainOpt	Grain option store
	 */
	function grain_ajax_switch_logging() 
	{
		global $GrainOpt, $userdata;

		if( empty($userdata) ) get_currentuserinfo();
	
		if(!defined("GRAIN_LOGGING_DISABLED_THE_HARD_WAY") && current_user_can('edit_themes'))  
		{
			// begin the response
			$response = "(function($) {";

			// is logging enabled?
			$logging_enabled = $GrainOpt->is(GRAIN_DEBUG_LOGGING);

			/* TODO: Testen, ob das erlaubt ist */
			$GrainOpt->set(GRAIN_DEBUG_LOGGING, !$logging_enabled);
			$GrainOpt->writeOptions();

			// remove all options from the combobox
			$text = __("disabled", "grain");
			$class = "disabled";
			if( $GrainOpt->is(GRAIN_DEBUG_LOGGING) ) {
				$text = __("enabled", "grain");
				$class = "enabled";
			}
			$response .= "$('#logging-state-text').text('$text');";
			$response .= "$('#logging-state-text').removeClass('enabled');";
			$response .= "$('#logging-state-text').removeClass('disabled');";
			$response .= "$('#logging-state-text').addClass('$class');";
			
			// close the response
			$response .= "})(jQuery);";
			
		}
		else {
			$response .= "alert('Wrong permissions or state error.');";
		}
		
		// un-hide elements
		$response .= 'grain_hide_element("switch_logging_state", false);';
		$response .= 'grain_show_ajaxloader("logging-state", false);';
		
		// send response
		die("/* <![CDATA[ */ ".$response ."/* ]]> */");
	}

	/**
	 * grain_adminpage_landingzone_scripthook() - Injects JavaScripts needed for the landing zone
	 *
	 * @since 0.3
	 * @access private
	 * @uses wp_print_scripts()	To inject the SACK script
	 */
	function grain_adminpage_landingzone_scripthook()
	{
		wp_print_scripts( array( 'sack' ));
	
	?>
	
	<script type="text/javascript" lang="JavaScript">
	//<![CDATA[
	
	function grain_ajax_switch_logging( )
	{
		// Disable the box
		grain_hide_element("switch_logging_state", true);		
		grain_show_ajaxloader("logging-state", true, "append");
				
		// send the AJAX request
		var mysack = new sack("<?php bloginfo( 'wpurl' ); ?>/wp-admin/admin-ajax.php" );    
		mysack.execute = 1;
		mysack.method = 'POST';
		mysack.setVar( "action", "grain_switch_logging" );
		mysack.encVar( "cookie", document.cookie, false );
		mysack.onError = function() 
				{ 
					grain_show_ajaxloader("logging-state", false);
					grain_hide_element("switch_logging_state", false);
					alert('Ajax error in doing stuff' )
				};
		mysack.runAJAX();

	}
	
	// create the button
	addLoadEvent(grain_overridecssajaxbutton);	
	function grain_overridecssajaxbutton() {
		(function($) {
			$('#logging-state-text').click(grain_ajax_switch_logging);
		})(jQuery);
	}
	
	//]]>
	</script>
	
	<?php
	}

?>