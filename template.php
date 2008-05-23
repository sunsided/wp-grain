<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* Required files */
	
	@require_once(TEMPLATEPATH . '/func/options.php');

/* Additional filters */

	@require_once(TEMPLATEPATH . '/func/filters.php');

/* Template functions */
	
	if( !function_exists("is_private") ) {
		function is_private() 
		{
			global $post;
			if( empty($post) ) return false;
			return $post->post_status == "private";
		}	
	}
	
	function grain_inject_photopage_error($message) 
	{
		// set and filter
		$html = '<div id="photo-page-error"><h2 class="errormessage">'.$message.'</h2></div>';
		$html = apply_filters(GRAIN_PHOTO_PAGE_ERROR_TITLE, $html);
		
		// display error
		echo $html;
		do_action(GRAIN_PHOTO_PAGE_ERROR);
	}
	
	function grain_get_copyright_string($extended = FALSE) 
	{
		global $GrainOpt;
		
		// get values
		$copysign = $extended ? '&copy;' : '(C)';
		$years = grain_copyright_years_ex();
		$key = $extended ? GRAIN_COPYRIGHT_HOLDER_HTML : GRAIN_COPYRIGHT_HOLDER;
		$copyrightString = $GrainOpt->get($key);
		
		// compose the string
		$string = $copysign;
		if(!empty($years)) $string .= ' '.$years;
		if(!empty($copyrightString)) $string .= ' '.$copyrightString;
		
		// return
		return $string;
	}
	
	function grain_embed_copyright($html = FALSE) 
	{
		echo grain_get_copyright_string($html);
	}
	
	function grain_copyright_years() 
	{	
		global $GrainOpt;
		
		// get options
		$start_year = $GrainOpt->get(GRAIN_COPYRIGHT_START_YEAR);
		$end_year = $GrainOpt->get(GRAIN_COPYRIGHT_END_YEAR);
		$end_year_offset = $GrainOpt->get(GRAIN_COPYRIGHT_END_OFFSET);
				
		// add offset
		$end_year = ($end_year + $end_year_offset);

		// setup delimiter
		$year_delimiter = '<span id="copyright-year-delimiter">-</span>';
				
		// compose value
		$value = $end_year . $year_delimiter . $start_year;
		
		// test special cases
		if( $end_year > $start_year ) $value = $start_year . $year_delimiter . $end_year;
		else if( $start_year == $end_year ) $value = $start_year;

		// apply filters and return		
		return apply_filters(GRAIN_COPYRIGHT_YEARS, $value);
	}
		
	function grain_copyright_years_ex() 
	{			
		global $GrainOpt;
		
		// get options
		$start_year = $GrainOpt->get(GRAIN_COPYRIGHT_START_YEAR);
		$end_year = $GrainOpt->get(GRAIN_COPYRIGHT_END_YEAR);
		$end_year_offset = $GrainOpt->get(GRAIN_COPYRIGHT_END_OFFSET);
		
		// add offset
		$end_year = ($end_year + $end_year_offset);

		// setup delimiter -- NO HTML CODE HERE!
		$year_delimiter = '-';
		
		// compose value
		$value = $end_year . $year_delimiter . $start_year;
		if( $end_year > $start_year ) $value = $start_year . $year_delimiter . $end_year;
		if( $start_year == $end_year ) $value = $start_year;

		return apply_filters(GRAIN_COPYRIGHT_YEARS_EX, $value);
	}
	
/* Header Menu */

	function grain_inject_navigation_menu($location) 
	{
		global $GrainOpt;
		$target = $GrainOpt->get(GRAIN_NAVBAR_LOCATION);
		
		if($location != $target ) return;
		
		global $post;
		
		if( $location == GRAIN_IS_HEADER )
			$class = "in-header";
		else
			$class = "in-body";
		?>

	<div id="headermenu" class="<?php echo $class; ?>">
		<?php
		include (TEMPLATEPATH . '/header.menu.php');
		?>
	</div>		
		
		<?php
	}
	
/* Eye candy helper */	
	
	function grain_use_reflection() {
		global $GrainOpt;
		if( !$GrainOpt->getYesNo(GRAIN_EYECANDY_MOOFX) ) return FALSE;
		return $GrainOpt->getYesNo(GRAIN_EYECANDY_REFLECTION_ENABLED);
	}

	function grain_get_gravatar_uri($rating = false, $size = false, $default = false, $border = false) {
		global $comment;
		$uri = "http://www.gravatar.com/avatar.php?gravatar_id=".md5(trim(strtolower($comment->comment_author_email)));
		if($rating && $rating != '') 	$uri .= "&amp;rating=".strtolower($rating);
		if($size && $size != '') 		$uri .="&amp;size=".intval($size);
		if($default && $default != '')	$uri .= "&amp;default=".urlencode(trim(strtolower($default)));
		if($border && $border != '')	$uri .= "&amp;border=".intval($border);
		return $uri;
	}

/* Eye candy: Fading */

	function grain_inject_simplefader($element_id='photo') {
		if(!grain_eyecandy_use_fader()) return;
	?>
	<style type='text/css'>#<?php echo $element_id; ?> {visibility:hidden;}</style>
	<script type="text/javascript" language="JavaScript">

		function initImage() {
			// get image
			imageId = '<?php echo $element_id; ?>';
			image = document.getElementById(imageId);
			// begin fade
			setOpacity(image, 0);
			image.style.visibility = "visible";
			fadeIn(imageId,0);
		}
		function fadeIn(objId,opacity) {
			if (document.getElementById) {
				obj = document.getElementById(objId);
				if (opacity <= 100) {
					setOpacity(obj, opacity);
					opacity += 5;
					window.setTimeout("fadeIn('"+objId+"',"+opacity+")", 20);
				}
			}
		}
		function setOpacity(obj, opacity) {
			opacity = (opacity == 100)?99.999:opacity;
			// IE/Win
			obj.style.filter = "alpha(opacity:"+opacity+")";
			// Safari<1.2, Konqueror
			obj.style.KHTMLOpacity = opacity/100;
			// Older Mozilla and Firefox
			obj.style.MozOpacity = opacity/100;
			// Safari 1.2, newer Firefox and Mozilla, CSS3
			obj.style.opacity = opacity/100;
		}
		window.onload = function() {initImage()}	
		
	</script>
	<?php
	}
	
/* Eye candy: Fading 2 */

	function grain_fx_can_fade() {
		global $GrainOpt;
		return $GrainOpt->getYesNo(GRAIN_EYECANDY_MOOFX) && $GrainOpt->getYesNo(GRAIN_EYECANDY_FADER);
	}

	function grain_inject_fader($element_id='photo') {
		global $GrainOpt;
		
		if(!$GrainOpt->getYesNo(GRAIN_EYECANDY_MOOFX)) return;
		if(!$GrainOpt->getYesNo(GRAIN_EYECANDY_FADER)) return;
	?>
	<script type="text/javascript" language="JavaScript">

		Element.extend({
			fadeIn:function(delay) {
				// Will fade in after delay - is chainable, ie:
				// $('element').fadeIn(500).fadeOut(1000);
				f = new Fx.Style(this, 'opacity', {duration:750, fps:50});
				f.start.pass([0,1], f).delay(delay);
				return this;
			},
			hide:function() {
				this.setStyle('opacity', '0');
				return this;
			},
			fadeOut:function(delay) {
				// Will fade out after delay - is chainable, ie:
				// $('element').fadeOut(500).fadeIn(1000);
				f = new Fx.Style(this, 'opacity', {duration:500, fps:50});
				f.start.pass([1,0], f).delay(delay);
				return this;
			}
		});

		window.addEvent('domready', function() {
		
			photo = $('photo-fade');
			//photo = $('photo');
			photo.hide();
		
		});	
		
		window.addEvent('load', function() {
		
			photo = $('photo-fade');
			//photo = $('photo');
			photo.fadeIn(100);
		});	
		
	</script>
	<?php
	}	

/* Eye candy: Tooltips */

	function grain_inject_moofx_tooltips($element_id='photo') {
		global $GrainOpt;
		if(!$GrainOpt->getYesNo(GRAIN_EYECANDY_MOOFX)) return;
		if(!$GrainOpt->getYesNo(GRAIN_EYECANDY_USE_MOOTIPS)) return;
	?>
	<script type="text/javascript" language="JavaScript">

		window.addEvent('domready', function(){

			var tooltips = new Tips($('<?php echo $element_id; ?>'), {
				className: 'tooltip'
			});
			var tooltips2 = new Tips($$('.tooltipped'), {
				className: 'tooltip'
			});
		
		});
		
	</script>
	<?php
	}

/* Eye candy: Slide */

	function grain_can_inject_moofx_slide() {
		global $GrainOpt;
		return $GrainOpt->getYesNo(GRAIN_EYECANDY_MOOFX) && $GrainOpt->getYesNo(GRAIN_EYECANDY_USE_SLIDE);
	}

	function grain_inject_moofx_slide() {
		if(!grain_can_inject_moofx_slide()) return;
	?>
	<script type="text/javascript" language="JavaScript">

		window.addEvent('domready', function(){
		
			var contentSlide = new Fx.Slide('content');
		
			$('content-toggle').addEvent('click', function(e){
				e = new Event(e);
				contentSlide.toggle();
				e.stop();
			});
		
			var commentSlide = new Fx.Slide('comment-frame-body');
		
			$('comments-toggle').addEvent('click', function(e){
				e = new Event(e);
				commentSlide.toggle();
				e.stop();
			});
		
		});
		
	</script>
	<?php
	}

/* Eye Candy: Various */

	function grain_inject_reflec_script($element = 'photo') {
		if( !grain_use_reflection() ) return;
		
		$reflection_size_factor = 0.3;
		$reflection_opacity = 0.3;
		
		$reflection_shift = 0;
		$top_offset = intval($height*$reflection_size_factor) - $reflection_shift;
		?>
		
		<script language="Javascript">
			Reflection.add('<?php echo $element; ?>', { height: <?php echo $reflection_size_factor; ?>, opacity: <?php echo $reflection_opacity; ?> });
		</script>
		
		<?php
	}


/* Helper: Popups */

	function grain_thumbnail_title($title, $text) {
		return 'cssbody=[tooltip-text-prev] cssheader=[tooltip-title-prev] header=['.$title.'] body=['.$text.']';
	}

/* Helper: Override Styles */

	function grain_get_css_overrides() {
		$regexp = '#style\.override([-\.].+|\d+)?\.css#i';
		$result = array();
		
		if ($dh = opendir(TEMPLATEPATH)) {
			while (($file = readdir($dh)) !== false) {
				$path = TEMPLATEPATH.'/'.$file;
				
				if(is_file($path)) {
					if( preg_match($regexp, $file) ) {
						$result[] = $file;
					}
				}
			}
			closedir($dh);
		}
		return $result;
    }

/* Helper: Content sidebars */

	function grain_inject_sidebar_above() {
		/* Widgetized sidebar, if you have the plugin installed. */ 
		if ( function_exists('dynamic_sidebar')) {
		?>
			<div id="content-sidebar-above" class="">
				<ul>
		<?php
			dynamic_sidebar('Above Image');
		?>							
				</ul>
			</div>
		<?php
		}	
	}
	
	function grain_inject_sidebar_below() {
		/* Widgetized sidebar, if you have the plugin installed. */ 
		if ( function_exists('dynamic_sidebar')) {
		?>
			<div id="content-sidebar-below" class="">
				<ul>
		<?php
			dynamic_sidebar('Below Image');
		?>							
				</ul>
			</div>
		<?php
		}	
	}
	
	function grain_inject_sidebar_footer() {
		/* Widgetized sidebar, if you have the plugin installed. */ 
		if ( function_exists('dynamic_sidebar')) {
		?>
			<div id="content-sidebar-footer" class="">
				<ul>
		<?php
			dynamic_sidebar('Footer');
		?>							
				</ul>
			</div>
		<?php
		}	
	}

/* Mosaic related */

	/*
		Thanks for the support to Johannes Jarolim (http://johannes.jarolim.com)
	*/

	function grain_mocaic_current_page() {
		global $wp_query;
		if( $wp_query->query_vars['paged'] != '' ) return $wp_query->query_vars['paged'];
		return 1;
	}

	function grain_mosaic_ppl( $title, $page, $count_per_page ) {
		
		global $wpdb, $tableposts;

		$query = "SELECT count(ID) as c ".
			"FROM $tableposts ".
			"WHERE post_status = 'publish'";
		$count   = $wpdb->get_results($query);

		if( $count[0]->c <= $page*$count_per_page ) return;
		echo '<a class="prev-page" rel="prev" href="'.get_pagenum_link($page+1).'">'.$title.'</a>';
	}

	function grain_mosaic_npl( $title, $page, $count_per_page ) {
		if( $page <= 1 ) return;
		echo '<a class="next-page" rel="next" href="'.get_pagenum_link($page-1).'">'.$title.'</a>';
	}
	
/* Date and time related */

	function grain_filter_dt($format) {	
		$escape_mode = 0;
		$is_escaped = 0;
		$output = '';
		for($i = 0; $i<strlen($format); ++$i) {
			$char = $format[$i];

			// something => something
			// {something} => \s\o\m\e\t\h\i\n\g
			// \{ something => \{ something
			// { \{ something} => \{ \s\o\m\e\t\h\i\n\g
			
			// if char is an escape character
			if( $char == '\\' ) {
				$is_escaped = 1;		// set escape flag
				$output .= $char;		// output character
				continue;				// loop
			}		
			else {
				// if we are in local escaping mode, just output the character
				if($is_escaped) {
					$output .= $char;	// output character
					$is_escaped = 0;	// unflag local escaping
					continue;
				}
				else {
					// if we are in escape mode we got the close tag
					if( $escape_mode && $char == '}' ) {
						$escape_mode = 0;
						continue;
					}
					// else if we are not in escape mode and got the open tag
					elseif( !$escape_mode && $char == '{' ) {
						$escape_mode = 1;
						continue;
					}
					// else we are in escape mode
					elseif( $escape_mode ) {
						$output .= '\\'.$char;
						continue;
					}
					// or else we are not in escape mode
					else {
						$output .= $char;
						continue;
					}
				}
			}		
		}
		
		return $output;
	}
	
	function grain_wpformatted_dt($the_time, $format) {
		return apply_filters('get_the_time', $the_time, $d);
	}

	function grain_inject_commenteditlink() {
		global $user_ID, $comment;
		// test if the current user can moderate comments
		if( !empty($user_ID) && current_user_can('moderate_comments') ):
				$approved = ($comment->comment_approved=='1');
			?>
			<div class="comment-admin-tools<?php if(!$approved) echo "-unapproved"; ?>">
				<?php 
					$target = GRAIN_IS_POPUP ? "_blank" : "_self";
					$tooltip = grain_thumbnail_title(__("edit comment", "grain"), __("comment ID:", "grain") .' '. $comment->comment_ID);
					$edit_text = __("edit comment", "grain");
					$html = '<a href="'.get_bloginfo('url').'/wp-admin/comment.php?action=editcomment&c='.get_comment_ID().'" target="'.$target.'" title="'.$tooltip.'">'.__("edit comment", "grain").'</a>';
					if( !$approved ) $html = '<span class="unapproved-comment">'.__("unapproved &rarr;", "grain").' '.$html.'</span>';
					echo $html;
				?>
			</div>
			<?php 
		endif; // moderation test	
	}
	
	function grain_announce_page($id=0) {
		$_SESSION["GRAIN_PAGE_VISITED"]	= $id;
		if(empty($id)) session_unregister("GRAIN_PAGE_VISITED");
	}

?>