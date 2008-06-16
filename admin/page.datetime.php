<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/

	
	if(!defined('GRAIN_ADMINPAGE_LOADED') ) die(basename(__FILE__));
	

/* functions */


	function grain_adminpage_datetime() 
	{
		global $HTML_allowed, $no_HTML, $GrainOpt;
		grain_admin_inject_yapb_msg();
		
		if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.__("Changes saved", "grain").'</strong></p></div>';
		
		grain_admin_start_page();
	?>
	<div class='wrap'>
		<div id="grain-header">
		<div id="nonJsForm">
			<form method="post" action="">
				<h2 id="first"><?php _e("Date and Time Settings", "grain"); ?></h2>
					<div class="zerosize"><input type="submit" name="defaultsubmit" value="Save" /></div>
					
					<p>
						<?php _e("For information on date and time formatting codes refer to the PHP manual entry of <a href=\"http://www.php.net/date\" target=\"_blank\">the <code>date()</code> function</a>.", "grain"); ?><br />
						<?php _e("If you want to include a specific text in the format, such as \"<code>o'clock</code>\", you may include it in curly braces, e.g. \"<code>{o'clock'}</code>\"", "grain"); ?>						
					</p>
					
					<fieldset>
					<legend><?php _e("Some examples", "grain"); ?></legend>
					
					<table class="examples">
					<thead>
						<tr>
							<td><?php _e("format"); ?></td>
							<td><?php _e("result"); ?></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<?php $format = 'l, F jS, Y'; ?>
							<td class="key"><?php echo $format; ?></td>
							<td class="value"><?php echo date(grain_filter_dt($format)); ?></td>
						</tr>
						<tr>
							<?php $format = 'F, Y'; ?>
							<td class="key"><?php echo $format; ?></td>
							<td class="value"><?php echo date(grain_filter_dt($format)); ?></td>
						</tr>
						<tr>
							<?php $format = 'g:ia'; ?>
							<td class="key"><?php echo $format; ?></td>
							<td class="value"><?php echo date(grain_filter_dt($format)); ?></td>
						</tr>
						<tr>
							<?php $format = '{it is} G:i T'; ?>
							<td class="key"><?php echo $format; ?></td>
							<td class="value"><?php echo date(grain_filter_dt($format)); ?></td>
						</tr>						
						<tr>
							<?php $format = '{abc}'; ?>
							<td class="key"><?php echo $format; ?></td>
							<td class="value"><?php echo date(grain_filter_dt($format)); ?></td>
						</tr>
						<tr>
							<?php $format = 'abc'; ?>
							<td class="key"><?php echo $format; ?></td>
							<td class="value"><?php echo date(grain_filter_dt($format)); ?></td>
						</tr>
					</tbody>
					</table>
					
					</fieldset>
					
					<fieldset>
						<legend><?php _e("Archive settings", "grain"); ?></legend>
						<?php
						$message = __("The current selection (<code>%FORMAT</code>) results in '<code>%RESULT</code>' for the current time.", "grain");
						$message = str_replace('%FORMAT', $GrainOpt->get(GRAIN_DTFMT_DAILYARCHIVE), $message); 
						$message = str_replace('%RESULT', date(grain_filter_dt($GrainOpt->get(GRAIN_DTFMT_DAILYARCHIVE))), $message); 
						grain_admin_shortline(GRAIN_DTFMT_DAILYARCHIVE, "archive_daily_dt", NULL, __("Daily Archive:", "grain"), $no_HTML, __("The date or time format in the archive for a specific day.", "grain").'<br />'.$message);
						
						$message = __("The current selection (<code>%FORMAT</code>) results in '<code>%RESULT</code>' for the current time.", "grain");
						$message = str_replace('%FORMAT', $GrainOpt->get(GRAIN_DTFMT_MONTHLYARCHIVE), $message); 
						$message = str_replace('%RESULT', date(grain_filter_dt($GrainOpt->get(GRAIN_DTFMT_MONTHLYARCHIVE))), $message); 
						grain_admin_shortline(GRAIN_DTFMT_MONTHLYARCHIVE, "archive_monthly_dt", NULL, __("Monthly Archive:", "grain"), $no_HTML, __("The date or time format in the archive for a specific month.", "grain").'<br />'.$message);
						?>
					</fieldset>
					
					<fieldset>
						<legend><?php _e("Photo Info", "grain"); ?></legend>
						<?php
						$message = __("The current selection (<code>%FORMAT</code>) results in '<code>%RESULT</code>' for the current time.", "grain");
						$message = str_replace('%FORMAT', $GrainOpt->get(GRAIN_DTFMT_PUBLISHED), $message); 
						$message = str_replace('%RESULT', date(grain_filter_dt($GrainOpt->get(GRAIN_DTFMT_PUBLISHED))), $message); 
						grain_admin_shortline(GRAIN_DTFMT_PUBLISHED, "post_publish_dt", NULL, __("Publication date:", "grain"), $no_HTML, __("The date and time format for the photo's 'published' date.", "grain").'<br />'.$message);
						
						$message = __("The current selection (<code>%FORMAT</code>) results in '<code>%RESULT</code>' for the current time.", "grain");
						$message = str_replace('%FORMAT', $GrainOpt->get(GRAIN_DTFMT_EXIF), $message); 
						$message = str_replace('%RESULT', date(grain_filter_dt($GrainOpt->get(GRAIN_DTFMT_EXIF))), $message); 
						$enabled_msg = __("(EXIF filtering is currently enabled.)", "grain");
						$disabled_msg = __("(EXIF filtering is currently disabled.)", "grain");
						$quickfilter_msg = $GrainOpt->is(GRAIN_FANCY_EXIFFILTER)?$enabled_msg:$disabled_msg;
						grain_admin_shortline(GRAIN_DTFMT_EXIF, "post_exif_dt", NULL, __("EXIF dates:", "grain"), $no_HTML, __("The timestamp format used for some EXIF fields if EXIF quickfiltering is enabled.", "grain").'<br />'.$quickfilter_msg.'<br />'.$message);
						?>					
					</fieldset>
					
					<fieldset>
						<legend><?php _e("Per comment", "grain"); ?></legend>
						<?php
						$message = __("The current selection (<code>%FORMAT</code>) results in '<code>%RESULT</code>' for the current time.", "grain");
						$message = str_replace('%FORMAT', $GrainOpt->get(GRAIN_DFMT_COMMENTS), $message); 
						$message = str_replace('%RESULT', date(grain_filter_dt($GrainOpt->get(GRAIN_DFMT_COMMENTS))), $message); 
						grain_admin_shortline(GRAIN_DFMT_COMMENTS, "comments_date", NULL, __("Date of comment:", "grain"), $no_HTML, __("The date format for a comment.", "grain").'<br />'.$message);
						
						$message = __("The current selection (<code>%FORMAT</code>) results in '<code>%RESULT</code>' for the current time.", "grain");
						$message = str_replace('%FORMAT', $GrainOpt->get(GRAIN_TFMT_COMMENTS), $message); 
						$message = str_replace('%RESULT', date(grain_filter_dt($GrainOpt->get(GRAIN_TFMT_COMMENTS))), $message); 
						grain_admin_shortline(GRAIN_TFMT_COMMENTS, "comments_time", NULL, __("Time of comment:", "grain"), $no_HTML, __("The time format for a comment.", "grain").'<br />'.$message);				
						?>
					</fieldset>

					<!-- <input type="submit" name="defaults" value="<?php _e("Factory Defaults", "grain"); ?>" /> -->
					<input type="submit" class="defbutton" name="submitform" value="<?php _e("Save Settings", "grain"); ?>" />
					<input type="hidden" name="action" value="save" />
					<input type="hidden" name="datetime_form" value="true" />
			</form>
		</div>
		</div>
	</div>
	<?php 
	} // function grain_adminpage_datetime()

?>