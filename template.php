<?php
/*     
	This file is part of Grain Theme for WordPress.

    Grain Theme for WordPress is free software: you can redistribute it 
	and/or modify it under the terms of the GNU General Public License 
	as published by the Free Software Foundation, either version 3 of 
	the License, or (at your option) any later version.

    Foobar is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Grain Theme for WordPress.  
	If not, see <http://www.gnu.org/licenses/>.
	
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

/* Required files */
	
	@require_once(TEMPLATEPATH . '/func/options.php');

/* Additional filters */

	@require_once(TEMPLATEPATH . '/func/filters.php');

/* Template functions */
	
	function grain_copyright_years() {
		// get options
		$start_year = grain_copyright_start_year();
		$end_year = grain_copyright_end_year();
		$end_year_offset = grain_copyright_end_year_offset();
		
		// add offset
		$end_year = ($end_year + $end_year_offset);

		// setup delimiter
		$year_delimiter = '<span id="copyright-year-delimiter">-</span>';
		
		// compose value
		$value = $end_year . $year_delimiter . $start_year;
		if( $end_year > $start_year ) $value = $start_year . $year_delimiter . $end_year;
		if( $start_year == $end_year ) $value = $start_year;

		return apply_filters(GRAIN_COPYRIGHT_YEARS, $value);
	}
	
	function grain_copyright_years_ex() {
		// get options
		$start_year = grain_copyright_start_year();
		$end_year = grain_copyright_end_year();
		$end_year_offset = grain_copyright_end_year_offset();
		
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

	define('GRAIN_IS_HEADER', 'header');
	define('GRAIN_IS_BODY_BEFORE', 'body_before');
	define('GRAIN_IS_BODY_AFTER', 'body_after');

	function grain_inject_navigation_menu($location) {
		$target = grain_navigation_bar_location();
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
		if( !grain_eyecandy_use_moofx() ) return FALSE;
		return grain_eyecandy_use_reflection();
	}

	function grain_get_gravatar_uri($rating = false, $size = false, $default = false, $border = false) {
		global $comment;
		$uri = "http://www.gravatar.com/avatar.php?gravatar_id=".md5($comment->comment_author_email);
		if($rating && $rating != '') 	$uri .= "&amp;rating=".$rating;
		if($size && $size != '') 		$uri .="&amp;size=".$size;
		if($default && $default != '')	$uri .= "&amp;default=".urlencode($default);
		if($border && $border != '')	$uri .= "&amp;border=".$border;
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
		return grain_eyecandy_use_moofx() && grain_eyecandy_use_fader();
	}

	function grain_inject_fader($element_id='photo') {
		if(!grain_eyecandy_use_moofx()) return;
		if(!grain_eyecandy_use_fader()) return;
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
		if(!grain_eyecandy_use_moofx()) return;
		if(!grain_eyecandy_use_moofx_tips()) return;
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
		return grain_eyecandy_use_moofx() && grain_eyecandy_use_moofx_slide();
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

?>