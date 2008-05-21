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
		grain_admin_inject_yapb_msg();
		
		if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.__("Changes saved", "grain").'</strong></p></div>';
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
					
					<p><label for="archive_daily_dt"><?php _e("Daily Archive:", "grain"); ?></label>
						<input 
							name="archive_daily_dt" 
							id="archive_daily_dt" 
							style="width: 200px" 
							type="text" 
							value="<?php echo htmlentities(grain_dtfmt_dailyarchive(FALSE)); ?>" /><br />
						<div class="input_pad">
							<?php _e("The date or time format in the archive for a specific day.", "grain"); ?>
							<br />
							<?php 
								$value = str_replace('%FORMAT', grain_dtfmt_dailyarchive(), __("The current selection (<code>%FORMAT</code>) results in '<code>%RESULT</code>' for the current time.", "grain")); 
								$value = str_replace('%RESULT', date(grain_filter_dt(grain_dtfmt_dailyarchive())), $value); 
								echo $value;
							?>
						</div>
					</p>
					
					<p><label for="archive_monthly_dt"><?php _e("Monthly Archive:", "grain"); ?></label>
						<input 
							name="archive_monthly_dt" 
							id="archive_monthly_dt" 
							style="width: 200px" 
							type="text" 
							value="<?php echo htmlentities(grain_dtfmt_monthlyarchive(FALSE)); ?>" /><br />
						<div class="input_pad"><?php _e("The date or time format in the archive for a specific month.", "grain"); ?>
						<br />
							<?php 
								$value = str_replace('%FORMAT', grain_dtfmt_monthlyarchive(), __("The current selection (<code>%FORMAT</code>) results in '<code>%RESULT</code>' for the current time.", "grain")); 
								$value = str_replace('%RESULT', date(grain_filter_dt(grain_dtfmt_monthlyarchive())), $value); 
								echo $value;
							?>
						</div>
					</p>
					
					</fieldset>
					
					<fieldset>
					<legend><?php _e("Photo Info", "grain"); ?></legend>
					
					<p><label for="post_publish_dt"><?php _e("Time of comment:", "grain"); ?></label>
						<input 
							name="post_publish_dt" 
							id="post_publish_dt" 
							style="width: 200px" 
							type="text" 
							value="<?php echo htmlentities(grain_dtfmt_published(FALSE)); ?>" /><br />
						<div class="input_pad"><?php _e("The date and time format for the photo's 'published' date.", "grain"); ?>
						<br />
							<?php 
								$value = str_replace('%FORMAT', grain_dtfmt_published(), __("The current selection (<code>%FORMAT</code>) results in '<code>%RESULT</code>' for the current time.", "grain")); 
								$value = str_replace('%RESULT', date(grain_filter_dt(grain_dtfmt_published())), $value); 
								echo $value;
							?>
						</div>
					</p>
					
					</fieldset>
					
					<fieldset>
					<legend><?php _e("Per comment", "grain"); ?></legend>

					<p><label for="comments_date"><?php _e("Date of comment:", "grain"); ?></label>
						<input 
							name="comments_date" 
							id="comments_date" 
							style="width: 200px" 
							type="text" 
							value="<?php echo htmlentities(grain_dfmt_comments(FALSE)); ?>" /><br />
						<div class="input_pad"><?php _e("The date format for a comment.", "grain"); ?>
						<br />
							<?php 
								$value = str_replace('%FORMAT', grain_dfmt_comments(), __("The current selection (<code>%FORMAT</code>) results in '<code>%RESULT</code>' for the current time.", "grain")); 
								$value = str_replace('%RESULT', date(grain_filter_dt(grain_dfmt_comments())), $value); 
								echo $value;
							?>
						</div>
					</p>
					
					<p><label for="comments_time"><?php _e("Time of comment:", "grain"); ?></label>
						<input 
							name="comments_time" 
							id="comments_time" 
							style="width: 200px" 
							type="text" 
							value="<?php echo htmlentities(grain_tfmt_comments(FALSE)); ?>" /><br />
						<div class="input_pad"><?php _e("The time format for a comment.", "grain"); ?>
						<br />
							<?php 
								$value = str_replace('%FORMAT', grain_tfmt_comments(), __("The current selection (<code>%FORMAT</code>) results in '<code>%RESULT</code>' for the current time.", "grain")); 
								$value = str_replace('%RESULT', date(grain_filter_dt(grain_tfmt_comments())), $value); 
								echo $value;
							?>
						</div>
					</p>
					
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