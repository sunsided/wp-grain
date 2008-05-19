<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/

	
	if(!defined('GRAIN_ADMINPAGE_LOADED') ) die(basename(__FILE__));
	

/* functions */


	function grain_adminpage_copyright() {
		if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.__("Changes saved", "grain").'</strong></p></div>';
	?>
	<div class='wrap'>
		<div id="grain-header">
		<div id="nonJsForm">
			<form method="post" action="">
				<h2 id="first"><?php _e("Copyright Settings", "grain"); ?></h2>
					<div class="zerosize"><input type="submit" name="defaultsubmit" value="Save" /></div>
									
				<fieldset>
					<legend><?php _e("Short Copyright Message", "grain"); ?></legend>
				
					<label for="copyright_person"><?php _e("Copyright holder:", "grain"); ?></label><input style="width: 400px" type="text" name="copyright_person" id="copyright_person" value="<?php echo htmlentities(grain_copyright(FALSE, __("(Your name)", "grain"))); ?>" /> <?php _e("No HTML here", "grain"); ?><br />
					<label for="copyright_person_ex"><?php _e("Copyright holder (HTML):", "grain"); ?></label><input style="width: 400px" type="text" name="copyright_person_ex" id="copyright_person_ex" value="<?php echo htmlentities(grain_copyright_ex(FALSE, '<em>'.__("(Your name)", "grain").'</em>')); ?>" /> <?php _e("HTML allowed here", "grain"); ?><br />
					<label for="copyright_start_year"><?php _e("Start year:", "grain"); ?></label><input style="width: 150px" type="text" name="copyright_start_year" id="copyright_start_year" value="<?php echo grain_copyright_start_year(FALSE); ?>" /> <?php _e("% for current year", "grain"); ?> (<?php echo date('Y'); ?>)<br />
					<label for="copyright_end_year"><?php _e("End year:", "grain"); ?></label><input style="width: 150px" type="text" name="copyright_end_year" id="copyright_end_year" value="<?php echo grain_copyright_end_year_ex(FALSE); ?>" /> <?php _e("% for current year", "grain"); ?> (<?php echo date('Y'); ?>)<br />
					<label for="copyright_end_year_offset"><?php _e("Offset to end year:", "grain"); ?></label><input style="width: 150px" type="text" name="copyright_end_year_offset" id="copyright_end_year_offset" value="<?php echo grain_copyright_end_year_offset(FALSE); ?>" /> <?php

					$string = __("i.e. <code>%OFFSET</code> to get <code>%SUMMED_YEAR</code> if the end year is <code>%YEAR</code>", "grain");
					$string = str_replace( "%OFFSET", "10", $string);
					$string = str_replace( "%YEAR", date('Y'), $string);
					$string = str_replace( "%SUMMED_YEAR", (date('Y') + 10), $string);
					echo $string;

					?><br />
				</fieldset>
				
				<fieldset>
					<legend><?php _e("Imprint", "grain"); ?></legend>

					<p><?php _e("You can embed an URL to your imprint page in the head of the webpage via a <a href=\"http://dublincore.org/\" target=\"_blank\">Dublin Core</a> meta tag.", "grain"); ?></p>
				
					<p><label for="imprint_url"><?php _e("Imprint URL:", "grain"); ?></label>
						<input style="width: 400px" type="text" name="imprint_url" id="imprint_url" value="<?php echo htmlentities(grain_imprint_url(FALSE)); ?>" /> 
						<?php _e("No HTML here", "grain"); ?><br />
						<div class="input_pad"><?php _e("The URL to your imprint page. Leave empty if you do not want to embed an <code>DC.Rights</code> meta tag.", "grain"); ?></div>
					</p>

				</fieldset>									

				<h2><?php _e("Creative Commons License", "grain"); ?></h2>
				
				<fieldset>
					<legend><?php _e("Creative Commons Embedding", "grain"); ?></legend>
				
					<p>
						<?php 
							$message = __("If you like to, you can publish your photos under a <a href=\"http://creativecommons.org/\" target=\"_blank\">Creative Commons</a> License. You can choose a license <a href=\"http://creativecommons.org/license/?lang=%LANGCODE\" target=\"_blank\">here</a>.", "grain"); 
							$message = str_replace('%LANGCODE', grain_get_base_locale(), $message);
							echo $message;
						?>
						<?php _e("You may also want to read the <a href=\"http://wiki.creativecommons.org/Frequently_Asked_Questions\" target=\"_blank\">Frequently Asked Questions</a> page.", "grain"); ?>
					</p>
					<p><label for="cc_license_enabled"><?php _e("Copyright:", "grain"); ?></label> <input style="margin-top: 8px;" type="checkbox" name="cc_license_enabled" id="cc_license_enabled" <?php if( grain_cc_enabled() ) echo ' checked="checked" ';?> value="1" /> <strong><?php _e("Embed Creative Commons license", "grain"); ?></strong><br />
						<div class="input_pad"><?php _e("Unchecking this option will remove the Creative Commons Logo, the textual message, as well as the embedded RDF.", "grain"); ?></div>
					</p>
					<p><label for="cc_license_code"><?php _e("The License's HTML code:", "grain"); ?></label>
						<textarea class="license-code" cols="100" wrap="off" rows="10" name="cc_license_code" id="cc_license_code"><?php
							echo htmlentities(grain_cc_code(FALSE));
						?></textarea>
						<div class="input_pad">
							<?php _e("To simplify CSS styling of the theme, all occurrences of <code>&lt;br /&gt;</code>, <code>&lt;br/&gt;</code> and <code>&lt;br&gt;</code> tags will be removed when embedding the code.", "grain"); ?>
						</div>
					</p>
					
					<p>
						<?php _e("In addition to the code above you can choose to embed an <acronym title=\"Resource Description Framework\">RDF</acronym> representation of the license. If you don't want to do that or don't know what it does, you may that option.", "grain"); ?>
					</p>
					
					<p><label for="cc_license_rdf"><?php _e("The License's RDF code:", "grain"); ?></label>
						<textarea class="license-code" cols="100" wrap="off" rows="10" name="cc_license_rdf" id="cc_license_rdf"><?php
							echo htmlentities(grain_cc_rdf(FALSE));
						?></textarea>
						<div class="input_pad">
							<?php _e("The RDF code is another way of displaying the CC license that can be embedded into the Feeds, as well as the HTML pages.", "grain"); ?>
							<br /><?php _e("The RDF code for the license you selected by downloading and opening the template for the PDF or XML embedding on the license selection page.<br />The code begins with <code>&lt;rdf:RDF ...</code> and ends with <code>&lt;/rdf:RDF&gt;</code>.", "grain"); ?>
							<br /><?php _e("You can validate RDF encoded licenses with the <a href=\"http://validator.creativecommons.org/\" target=\"_blank\">CC RDF License Validator</a>.", "grain"); ?>
						</div>
					</p>
					<p>
						<label for="cc_rdf_feed"><?php _e("Embed RDF in Feeds:", "grain"); ?></label> 
						<input style="margin-top: 8px;" type="checkbox" name="cc_rdf_feed" id="cc_rdf_feed" <?php if( grain_ccrdf_feed_embed() ) echo ' checked="checked" ';?> value="1" />
						<?php _e("Embed the RDF in the Atom and RSS feeds", "grain"); ?><br />
						<div class="input_pad">
							<strong><?php _e("Only enable this option if you know what you are doing, as faulty RDF markup may render your feeds invalid.", "grain"); ?></strong>
						</div>
					</p>



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