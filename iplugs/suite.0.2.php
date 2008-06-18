<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Plugin Suite 1
	
	@package Grain Theme for WordPress
	@subpackage Plugin Suite
*/

	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));
	

// this is not implemented in 0.2 -- don't change it, it won't work anyways
define('GRAIN_INCLUDE_PANO_EXTENSION', FALSE);

	
/* included? */		
		
if( GRAIN_INCLUDE_PANO_EXTENSION ):
	

/* plugin embedding */	

	function grain_plugin_path($name) {
		return TEMPLATEPATH.'/iplugs/'.$name;
	}
	
	function grain_plugin_url($name) {
		return GRAIN_TEMPLATE_DIR.'/iplugs/'.$name;
	}
	
/* ptviewer */

	@require_once(TEMPLATEPATH.'/iplugs/ptviewer/ptviewer-inject.php');
	@require_once(TEMPLATEPATH.'/iplugs/devalvr/devalvr-inject.php');	
	
/* menu hook */

	add_action('edit_form_advanced', 'grain_hook_panorama', '', 0);
	
	
	add_action('publish_post', 'grain_hook_save_post');
	add_action('save_post', 'grain_hook_save_post');
	add_action('delete_post', 'grain_hook_delete_post');
	
	function grain_hook_save_post($post_id) {
	
		//print_r($_REQUEST);
		//die();
		
		// pano enabled, etc ...
		// post type
	
	?>
	<?php
	}
	
	function grain_hook_delete_post($post_id) {
	?>
	<?php
	}
	
	function grain_hook_panorama() {
	?>
	<style type="text/css" media="screen">
		#grainpoststuff td.info {
			width: 150px;
			vertical-align: top;
		}
		
		#grainpoststuff {
			padding: 10px;
		}
		
		#grainpoststuff fieldset.grain_internal {
			margin: 0px;
			/*padding: 5px;*/
			border: 1px dotted silver;
		}
		
		#grainpoststuff .hidden {
			display: none;
			visibility: hidden;
			overflow: none;
			width: 0px;
			height: 0px;
		}
		
		#grainpano_url {
			width: 100%;
			margin-right: 10px;
			font-family: monospace;
		}
		
		#grain_posttype {
			margin-bottom: 2px;
		}
		
		.grain_desc {
			border: 1px solid silver;
			background: #FFFFF5;
			margin-left: 2px;
			margin-top: 3px;
		}
	</style>
	
	<script language="Javascript" type="text/javascript">
		function grain_selectposttype(element) {
			var value = element.value;
			document.getElementById('grain_panorama').disabled = (value != 'pano');			
			var key = 'grain_pt_' + value;
			var description = document.getElementById(key).value;
			document.getElementById('grain_post_desc').innerHTML = description;			
			return true;
		}
		
		function grain_selectplayer(element) {
			if(element.value == "") {
				document.getElementById('g_pt_deval').disabled = element.checked;
				document.getElementById('g_pt_qt').disabled = element.checked;
				document.getElementById('g_pt_java').disabled = element.checked;
			}
			return true;
		}
	</script>
	
	<div class="dbx-b-ox-wrapper">
	<fieldset id="grainpost" class="dbx-box">
		<div class="dbx-h-andle-wrapper">
			<h3 class="dbx-handle"><?php _e('Grain: Post Settings') ?></h3>
		</div>
		<div class="dbx-c-ontent-wrapper">
			<div id="grainpoststuff" class="dbx-content">
			
				<?php
				
					$grain_desc = array();
					$grain_desc['photo'] = __("Shows a photo and it's description.");
					$grain_desc['pano'] = __("Uses a viewer applet to show the panorama given in the box below.");
					$grain_desc['split'] = __("Looks like a 'Photo Post' type, but displays everything behind the <code>&lt;</code><code>!--more--&gt;</code> marker instead of the photo. This way you can show user-defined content.");
					$grain_desc['pure'] = __("The post will be displayed just like a normal (text) blog post or static page.");
				
				?>
			
				<input class="hidden" type="hidden" id="grain_pt_photo" value="<?php echo $grain_desc['photo']; ?>" />
				<input class="hidden" type="hidden" id="grain_pt_pano" value="<?php echo $grain_desc['pano']; ?>" />
				<input class="hidden" type="hidden" id="grain_pt_split" value="<?php echo $grain_desc['split']; ?>" />
				<input class="hidden" type="hidden" id="grain_pt_pure"value="<?php echo $grain_desc['pure']; ?>" />
				
				<fieldset id="grain_post" class="grain_internal">
				<legend><?php _e("Post Settings"); ?></legend>
				<table>
					<tbody>
						<tr>
							<td class="info">
								<label for="grain_posttype"><?php _e("Post type:"); ?> </label>
							</td>
							<td>
								<select onchange="grain_selectposttype(this);" name="grain_posttype" id="grain_posttype">
									<optgroup label="Photo & Panorama">
										<option 
											title="<?php echo $grain_desc['photo']; ?>"
											value="photo"><?php _e("Photo Post"); ?></option>
										<option 
											title="<?php echo $grain_desc['pano']; ?>"
											value="pano"><?php _e("Panorama Post"); ?></option>
									</optgroup>
									<optgroup label="User-Defined Content">
										<option 
											title="<?php echo strip_tags($grain_desc['split']); ?>"
											value="split"><?php _e("Split Post"); ?></option>
										<option 
											title="<?php echo strip_tags($grain_desc['pure']); ?>"
											value="pure"><?php _e("Pure Content"); ?></option>
									</optgroup>
								</select><br />
								<div id="grain_post_desc" class="grain_desc"><?php echo $grain_desc['photo']; ?></div>
							</td>
						</tr>
					</tbody>
				</table>
				</fieldset>
				
				<fieldset id="grain_panorama" class="grain_internal">
				<legend><?php _e("Panorama Settings"); ?></legend>
				<table>
					<tbody>
						<tr>
							<td>
								<label for="grainpano_url"><?php _e("Panorama file URL:"); ?> </label>
							</td>
							<td>
								<input 
									name="grainpano_url" 
									id="grainpano_url"  />
								
								<br />
								<div id="grain_url_desc" class="grain_desc"><?php _e("You can upload or select a panorama file using the Upload box. To find the URL of the uploaded file, click it's <code>Edit</code> link afterwards."); ?></div>
							</td>
						</tr>
						<tr>
							<td class="info">
								<?php _e("Panorama player:"); ?>
							</td>
							<td>
								<label for="g_pt_auto">
									<input type="checkbox" 
										id="g_pt_auto" name="grainpano_target[]" 
										value="" 
										checked="checked"
										onchange="grain_selectplayer(this);" />
									<strong><?php _e("autoselect by filetype"); ?></strong>
								</label>
								<label for="g_pt_deval">
									<input type="checkbox" 
										id="g_pt_deval" name="grainpano_target[]" 
										value="deval" 
										disabled="disabled"
										checked="checked"
										onchange="grain_selectplayer(this);" />
									<?php _e("DevalVR"); ?>
								</label>
								<label for="g_pt_qt">
									<input type="checkbox" 
										id="g_pt_qt" name="grainpano_target[]" 
										value="qt" 
										disabled="disabled"
										checked="checked"
										onchange="grain_selectplayer(this);" />
									<?php _e("Quicktime"); ?>
								</label>
								<label for="g_pt_java">
									<input type="checkbox" 
										id="g_pt_java" name="grainpano_target[]" 
										value="java" 
										disabled="disabled"
										checked="checked"
										onchange="grain_selectplayer(this);" />
									<?php _e("Java based (PTViewer)"); ?>
								</label><br />
							</td>
						</tr>
						<tr>
							<td>
								<label for="grainpano_settings"><?php _e("Panorama Image:"); ?> </label>
							</td>
							<td>
								Einstellungen f&uuml;r für bildbasierte Panoramas hier
							</td>
						</tr>
					</tbody>
			</table>
			</fieldset>

			</div>
		</div>
	</fieldset>
	</div>
			
	<?php
	}
	
endif; // if( GRAIN_INCLUDE_PANO_EXTENSION ):
	
?>