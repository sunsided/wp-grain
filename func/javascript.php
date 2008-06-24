<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	JavaScript helper functions
	
	@package Grain Theme for WordPress
	@subpackage JavaScript
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));


	/* functions */

	/**
	 * grain_embed_javascripts() - Injects the HTML markup for the JavaScript files used by Grain
	 *
	 * @since 0.3
	 */
	function grain_embed_javascripts() 
	{
		global $GrainOpt;
		echo '<script type="text/javascript" src="'.GRAIN_TEMPLATE_DIR.'/js/boxover.js"></script>'.PHP_EOL;
		
		if($GrainOpt->getYesNo(GRAIN_EYECANDY_MOOFX)) 
		{
			echo '<script type="text/javascript" src="'.GRAIN_TEMPLATE_DIR.'/js/mootools.js"></script>'.PHP_EOL;
			echo '<script type="text/javascript" src="'.GRAIN_TEMPLATE_DIR.'/js/mootools-more.js"></script>'.PHP_EOL;
			if($GrainOpt->getYesNo(GRAIN_EYECANDY_REFLECTION_ENABLED)) 
			{
				echo '<script type="text/javascript" src="'.GRAIN_TEMPLATE_DIR.'/js/reflection.js"></script>'.PHP_EOL;
			}
		}
		
		// info for the comments popup
		if(!$GrainOpt->getYesNo(GRAIN_EXTENDEDINFO_ENABLED)) comments_popup_script(600, 600);
		
		// invoke the action
		do_action("grain_scripts");
	}

/* Eye candy: Tooltips */

	/**
	 * grain_inject_moofx_tooltips() - Injects the HTML markup for the JavaScript to use Moo.FX tooltips
	 *
	 * This function is deprecated and currently not in use.
	 *
	 * @since 0.3
	 * @deprecated
	 */
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

	/**
	 * grain_can_inject_moofx_slide() - Tests whether Moo.FX slides are enabled
	 *
	 * This function is deprecated and currently not in use.
	 *
	 * @since 0.2
	 * @global $GrainOpt	Grain options
	 * @deprecated
	 * @see grain_inject_moofx_slide()
	 * @return bool TRUE if Moo.FX slides are enabled
	 */
	function grain_can_inject_moofx_slide() {
		global $GrainOpt;
		return $GrainOpt->getYesNo(GRAIN_EYECANDY_MOOFX) && $GrainOpt->getYesNo(GRAIN_EYECANDY_USE_SLIDE);
	}

	/**
	 * grain_inject_moofx_slide() - Injects the HTML markup for the JavaScript to use Moo.FX slides
	 *
	 * This function is deprecated and currently not in use.
	 *
	 * @since 0.2
	 * @uses grain_can_inject_moofx_slide() To determine if Moo.FX slides can be used
	 * @deprecated
	 */
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

	/**
	 * grain_use_reflection() - Checks if reflections are enabled
	 *
	 * @since 0.2
	 * @global $GrainOpt Grain options
	 * @see grain_inject_reflec_script()
	 * @return bool TRUE if reflections can be used
	 */
	function grain_use_reflection() {
		global $GrainOpt;
		if( !$GrainOpt->getYesNo(GRAIN_EYECANDY_MOOFX) ) return FALSE;
		return $GrainOpt->getYesNo(GRAIN_EYECANDY_REFLECTION_ENABLED);
	}

	/**
	 * grain_inject_reflec_script() - Injects the reflection JavaScript
	 *
	 * @since 0.2
	 * @uses grain_use_reflection() To test if reflection is available
	 */
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

	/**
	 * grain_fx_can_fade() - Checks if fading is enabled
	 *
	 * @since 0.2
	 * @global $GrainOpt Grain options
	 * @see grain_inject_fader()
	 * @return bool TRUE if fading can be used
	 */
	function grain_fx_can_fade() {
		global $GrainOpt;
		return $GrainOpt->getYesNo(GRAIN_EYECANDY_MOOFX) && $GrainOpt->getYesNo(GRAIN_EYECANDY_FADER);
	}

	/**
	 * grain_inject_fader() - Injects the JavaScript used by the fading effect
	 *
	 * @since 0.2
	 * @uses grain_fx_can_fade() to test if fading can be used
	 */
	function grain_inject_fader() {
		if(!grain_fx_can_fade()) return;
	?>
	<script type="text/javascript" language="JavaScript">

		Element.implement({
		
			grainFadeIn:function(delay) {
				var myFx = new Fx.Tween(this, { duration: 500, link: "chain" } );
				myFx.start("opacity", 0, 1);				
				return this;
			},
			
			grainHide:function() {
				this.setStyle('opacity', '0');
				return this;
			},
			
			grainFadeOut:function(delay) {
				var myFx = new Fx.Tween(this, { duration: 500, link: "chain" } );
				myFx.start("opacity", 1, 0);
				return this;
			}
		});

		window.addEvent('domready', function() {
		
			photo = $$('div#photo-fade');		
			photo.grainHide();
		
		});
		
		window.addEvent('load', function() {
			photo = $$('div#photo-fade');
			photo.grainFadeIn(100);
		});	
		
		window.addEvent('unload', function() {
		
			photo = $$('div#photo-fade');
			photo.grainFadeOut(100);
		});
		
	</script>
	<?php
	}	

?>