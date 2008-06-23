<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Administration menu options: Common AJAX
	
	@package Grain Theme for WordPress
	@subpackage AJAX responder
*/
	
	if(!defined('GRAIN_ADMINPAGE_LOADED') ) die(basename(__FILE__));
	

/* functions */

	add_action('admin_print_scripts', 'grain_admincommon_ajax_scripthook' );

	/**
	 * grain_adminpage_landingzone_scripthook() - Injects JavaScripts needed for the landing zone
	 *
	 * @since 0.3
	 * @access private
	 * @uses wp_print_scripts()	To inject the SACK script
	 */
	function grain_admincommon_ajax_scripthook()
	{
	  wp_print_scripts( array( 'sack' ));
	
	?>
	
	<script type="text/javascript" lang="JavaScript">
	//<![CDATA[
	function grain_enable_element(name, enabled)
	{
		(function($) {
			if(enabled)
				$('#'+name).removeAttr('disabled');
			else
				$('#'+name).attr('disabled', 'disabled');
		})(jQuery);
	}
	
	function grain_hide_element(name, hidden)
	{
		(function($) {
			if(hidden)
				$('#'+name).css('visibility', 'hidden');
			else
				$('#'+name).css('visibility', 'visible');
		})(jQuery);
	}	
	
	function grain_show_ajaxloader(name, enabled, mode)
	{
		(function($) {
			if(enabled) {
				markup = $('<div id="'+name+'_ajaxloader" class="ajaxloader"><img id="'+name+'_ajaxloader_img" height="16" width="16" class="ajaxloader" src="<?php echo GRAIN_TEMPLATE_DIR ?>/images/ajax-loader.gif" /><span class="loading"><?php echo __("loading ...", "grain") ?></span></div>');
				if(mode == null || mode == "after") {
					markup.addClass("ajaxloader-after");
					$('#'+name).after(markup);
				}
				else if( mode == "append") {
					markup.addClass("ajaxloader-append");
					$('#'+name).append(markup);
				}
				else {
					markup.addClass("ajaxloader-prepend");
					$('#'+name).prepend(markup);
				}
			} else {
				$('#'+name+"_ajaxloader").remove();
			}
		})(jQuery);
	}
	
	//]]>
	</script>
	
	<?php
	}

?>