<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/

	
	if(!defined('GRAIN_ADMINPAGE_LOADED') ) die(basename(__FILE__));
	

/* functions */


	function grain_adminpage_copyright() 
	{		
		global $HTML_allowed, $no_HTML;
		grain_admin_inject_yapb_msg();
		
		if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.__("Changes saved", "grain").'</strong></p></div>';
		
		grain_admin_start_page();
	?>
	<div class='wrap'>
		<div id="grain-header">
		<div id="nonJsForm">		
			<form method="post" action="">
				<h2 id="first"><?php _e("Copyright Settings", "grain"); ?></h2>
					<div class="zerosize"><input type="submit" name="defaultsubmit" value="Save" /></div>
									
				<fieldset>
					<legend><?php _e("Copyright Holder", "grain"); ?></legend>				
					<?php
					grain_admin_infoline(NULL, __("Here you can enter your copyright information and tell Grain how to display them.", "grain"));
					
					$string = __("% is always the current year", "grain");
					grain_admin_shortline(GRAIN_COPYRIGHT_START_YEAR, "copyright_start_year", NULL, __("First copyright year:", "grain"), $string, NULL);
					grain_admin_shortline(GRAIN_COPYRIGHT_END_YEAR, "copyright_end_year", NULL, __("Last copyright year:", "grain"), $string, NULL);
					
					$string = __("i.e. <code>%OFFSET</code> to get <code>%SUMMED_YEAR</code> if the end year is <code>%YEAR</code>", "grain");
					$string = str_replace( array( "%OFFSET", "%YEAR", "%SUMMED_YEAR" ), array(10, date('Y'), (date('Y') + 10)), $string);
					grain_admin_shortline(GRAIN_COPYRIGHT_END_OFFSET, "copyright_end_year_offset", NULL, __("Offset to end year:", "grain"), $string, NULL);
					
					grain_admin_infoline(NULL, __("Here you can configure the informational text, e.g. your name and a licensing info.", "grain"));
					
					grain_admin_longline(GRAIN_COPYRIGHT_HOLDER, "copyright_person", NULL, __("Copyright holder:", "grain"), $no_HTML, __("Keep this short, e.g. <code>\"John Doe\"</code>. This info will be embedded in the page's meta header.", "grain"));
					grain_admin_longline(GRAIN_COPYRIGHT_HOLDER_HTML, "copyright_person_ex", NULL, __("Full copyright holder and info:", "grain"), $HTML_allowed, __("e.g. <code>\"John Doe. All rights reserved.\"</code> This info will be visible to the visitor.", "grain") );					
					?>
									
				</fieldset>
				
				<fieldset>
					<legend><?php _e("Imprint Page", "grain"); ?></legend>
					<?php
					grain_admin_infoline(NULL, __("Grain uses <a href=\"http://dublincore.org/\" target=\"_blank\">Dublin Core</a> meta tags to describe your blog. If you have an imprint page you may set it here.<br />If you do not have an imprint or do not want to propagate it through the DC meta tag the field can be left empty.", "grain"));					
					grain_admin_longline(GRAIN_IMPRINT_URL, "imprint_url", NULL, __("Imprint URL:", "grain"), $no_HTML, __("The URL to your imprint page. Leave empty if you do not want to embed an <code>DC.Rights</code> meta tag.", "grain"));
					?>		
				</fieldset>									

				<h2><?php _e("Creative Commons License", "grain"); ?></h2>
				
				<fieldset>
					<legend><?php _e("Embedding a Creative Commons License", "grain"); ?></legend>
				
					<?php 
					$message = __("If you like to, you can publish your photos under a <a href=\"http://creativecommons.org/\" target=\"_blank\">Creative Commons</a> License. You can choose a license <a href=\"http://creativecommons.org/license/?lang=%LANGCODE\" target=\"_blank\">here</a>.", "grain"); 
					$message = str_replace('%LANGCODE', grain_get_base_locale(), $message);
					grain_admin_infoline(NULL, $message);					
					
					grain_admin_checkbox(GRAIN_COPYRIGHT_CC_ENABLED, "cc_license_enabled", NULL, __("Copyright:", "grain"), '<strong>'.__("Embed Creative Commons license", "grain").'</strong>', __("Unchecking this option will remove the Creative Commons Logo, the textual message, as well as the embedded <a target=\"_blank\" href=\"http://en.wikipedia.org/wiki/Resource_Description_Framework\">RDF</a> (if any). This is the way to go if you want your photos to be published as, e.g. \"All rights reserved\".", "grain"));
					grain_admin_multiline(GRAIN_COPYRIGHT_CC_CODE, "cc_license_code", NULL, __("The license's HTML code:", "grain"), __("To simplify CSS styling of the theme, all occurrences of <code>&lt;br /&gt;</code>, <code>&lt;br/&gt;</code> and <code>&lt;br&gt;</code> tags will be removed when embedding the code.", "grain") );
					
					grain_admin_infoline(NULL, __("In addition to the code above you can choose to embed an <acronym title=\"Resource Description Framework\">RDF</acronym> representation - a machine readable version - of the license. The RDF will be embedded on the website and can also be integrated in your newsfeeds, if you choose to enable that.", "grain"));
					
					$message = __("The RDF code for the license you selected can be found by downloading and opening the template for the PDF or XML embedding on the license selection page. The code begins with <code>&lt;rdf:RDF ...</code> and ends with <code>&lt;/rdf:RDF&gt;</code>.", "grain");
					$message .= '<br />' . __("You can validate RDF encoded licenses and RDF enabled content with the <a href=\"http://validator.creativecommons.org/\" target=\"_blank\">CC RDF License Validator</a>.", "grain");
					grain_admin_multiline(GRAIN_COPYRIGHT_CC_RDF, "cc_license_rdf", NULL, __("The license's RDF code:", "grain"), $message );
					grain_admin_checkbox(GRAIN_CC_RDF_FEED, "cc_rdf_feed", NULL, __("Embed RDF in Newsfeeds:", "grain"), __("Embed the RDF in the Atom and RSS feeds", "grain"), __("<strong>Use this with caution!</strong> Enable this option only if you are sure what you are doing! Faulty RDF markup may render your newsfeeds invalid or even unusable. Test your newsfeeds thoroughly when enabling this!", "grain"));
					?>

				</fieldset>

					<!-- <input type="submit" name="defaults" value="<?php echo _e("Factory Defaults", "grain"); ?>" /> -->
					<input type="submit" class="defbutton" name="submitform" value="<?php _e("Save Settings", "grain"); ?>" />
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="copyright_form" value="true" />
			</form>
		</div>
		</div>
	</div>
	<?php 
	} // function grain_adminpage_copyright()

?>