<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));


	/* functions */

	function grain_embed_javascripts() 
	{
		global $GrainOpt;
		echo '<script type="text/javascript" src="'.GRAIN_TEMPLATE_DIR.'/js/boxover.js"></script>';
		
		if($GrainOpt->getYesNo(GRAIN_EYECANDY_MOOFX)) 
		{
			echo '<script type="text/javascript" src="'.GRAIN_TEMPLATE_DIR.'/js/mootools.v1.11.js"></script>';
			if($GrainOpt->getYesNo(GRAIN_EYECANDY_REFLECTION_ENABLED)) 
			{
				echo '<script type="text/javascript" src="'.GRAIN_TEMPLATE_DIR.'/js/reflection.js"></script>';
			}
		}
		
		// info for the comments popup
		if(!$GrainOpt->getYesNo(GRAIN_EXTENDEDINFO_ENABLED)) comments_popup_script(600, 600);
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

	function grain_use_reflection() {
		global $GrainOpt;
		if( !$GrainOpt->getYesNo(GRAIN_EYECANDY_MOOFX) ) return FALSE;
		return $GrainOpt->getYesNo(GRAIN_EYECANDY_REFLECTION_ENABLED);
	}

	function grain_inject_reflec_script($element = 'photo') {
		if( !grain_use_reflection() ) return;
		
		$reflection_size_factor = 0.3;
		$reflection_opacity = 0.3;
		
		$reflection_shift = 0;
		$top_offset = intval($height*$reflection_size_factor) - $reflection_shift;
		
		// fix locale bug (decimal "point" is locale specific)
		$reflection_size_factor = intval($reflection_size_factor) .'.'. ($reflection_size_factor-intval($reflection_size_factor)) * 1000;
		$reflection_opacity = intval($reflection_opacity) .'.'. ($reflection_opacity-intval($reflection_opacity)) * 1000;
		?>
		
		<script language="Javascript">
			Reflection.add('<?php echo $element; ?>', { height: <?php echo $reflection_size_factor; ?>, opacity: <?php echo $reflection_opacity; ?> });
		</script>
		
		<?php
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

?>