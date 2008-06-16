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
			echo '<script type="text/javascript" src="'.GRAIN_TEMPLATE_DIR.'/js/mootools.js"></script>';
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
		
		// fix locale bug (decimal "point" is locale specific)
		$reflection_size_factor = intval($reflection_size_factor) .'.'. ($reflection_size_factor-intval($reflection_size_factor)) * 1000;
		$reflection_opacity = intval($reflection_opacity) .'.'. ($reflection_opacity-intval($reflection_opacity)) * 1000;
		
		?>
		
		<script language="Javascript">
			$$("img#<?php echo $element; ?>").reflect({ height: <?php echo $reflection_size_factor; ?>, opacity: <?php echo $reflection_opacity; ?> });
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

		Element.implement({
			fadeIn:function(delay) {
			this.setStyle('display','');  
				this.fade('in');
			},
			
			hide:function() {
				this.setStyle('opacity', '0');
				/*this.setStyle('display','none'); */
				return this;
			},
			
			fadeOut:function(delay) {
				this.fade("out");
			}
		});

		window.addEvent('domready', function() {
		
			photo = $$('div#photo-fade');		
			photo.hide();
		
		});	
		
		window.addEvent('load', function() {
		
			photo = $$('div#photo-fade');
			photo.fadeIn(100);
		});	
		
		window.addEvent('unload', function() {
		
			photo = $$('div#photo-fade');
			photo.fadeOut(100);
		});	
		
	</script>
	<?php
	}	

?>