<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$

*//**

	Structure helper functions
	
	@package Grain Theme for WordPress
	@subpackage Structure
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

	/* Session preparing */
	
	/**
	 * The request parameter used to determine if Grain shall switch to "one time extended" mode.
	 * @access private
	 */
	define("GRAIN_OTI_KEY", "otex");
	
	/**
	 * grain_startSession() - Starts a new session
	 *
	 * @since 0.3
	 * @global $GrainOpt Grain options
	 * @param bool $on_popup		Optional. Set to TRUE if this call comes from the comments popup (Defaults to FALSE)
	 */
	function grain_startSession($on_popup=FALSE) 
	{	
		global $wp_query, $GrainOpt;
			
		// start new session
		@session_start();
		
		// If the index page is called, pretend it is not
		if( is_home() ):
			$lastposts = get_posts('numberposts=1');
			foreach ($lastposts as $post):
				setup_postdata($post);			
			endforeach;
			// pretend we are on a single page so that next/prev post functions work
			$wp_query->is_single = true;
		endif;
		
		// allow OTI requests only from local server
		$oti_allowed = array_key_exists("GRAIN_FROM_COMPP", $_SESSION) && $_SESSION["GRAIN_FROM_COMPP"] === true;
		$ext_allowed = $GrainOpt->getYesNo(GRAIN_EXTENDEDINFO_ENABLED);
		$comments_allowed = grain_can_comment(); // $GrainOpt->getYesNo(GRAIN_COMMENTS_ON_EMPTY_ENABLED);
		
		// extended info mode
		if( $comments_allowed && $ext_allowed && isset($_REQUEST['info']) && !empty($_REQUEST['info']) ) 
		{
			define('GRAIN_REQUESTED_EXINFO', $_REQUEST['info'] == 'on' ? TRUE : FALSE);
			define('GRAIN_REQUESTED_OTEXINFO', FALSE);
		} 
		elseif( $oti_allowed  && isset($_REQUEST[GRAIN_OTI_KEY]) && !empty($_REQUEST[GRAIN_OTI_KEY]) ) 
		{
			define('GRAIN_REQUESTED_OTEXINFO', $_REQUEST[GRAIN_OTI_KEY] == 'on' ? TRUE : FALSE);
			define('GRAIN_REQUESTED_EXINFO', GRAIN_REQUESTED_OTEXINFO);
		} 
		else 
		{
			define('GRAIN_REQUESTED_OTEXINFO', FALSE);
			define('GRAIN_REQUESTED_EXINFO', FALSE);
		}	
	}
	
	/**
	 * grain_endSession() - Ends the current session
	 *
	 * This function unregisters some one-time session entries
	 *
	 * @since 0.3
	 */
	function grain_endSession() 
	{
		if( @$_SESSION['GRAIN_FROM_COMPP'] ) {
			session_unregister('GRAIN_FROM_COMPP');
		}
	}

	/**
	 * grain_announce_page() - Announces that a page was visited
	 *
	 * This is used internally for OTEX decisions
	 *
	 * @since 0.3
	 * @access internal
	 * @param string $format				The format
	 * @return string The filtered format
	 */
	function grain_announce_page($id=0) {
		$_SESSION["GRAIN_PAGE_VISITED"]	= $id;
		if(empty($id)) session_unregister("GRAIN_PAGE_VISITED");
	}

	/* META headers */
	
	/**
	 * grain_embed_general_meta() - Embeds general meta information
	 *
	 * @since 0.3
	 * @global $GrainOpt 	Grain options
	 * @subpackage META information
	 */
	function grain_embed_general_meta() 
	{
		global $GrainOpt;
		
		?>
<meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="copyright" content="<?php grain_embed_copyright(); ?>" />
<meta name="author" Content="<?php echo $GrainOpt->get(GRAIN_COPYRIGHT_HOLDER); ?>" />
<meta name="content-language" Content="<?php echo get_bloginfo('language'); ?>" />
<?php
	
		// prevent listing of the popup
		if( grain_ispopup() ) {
			echo '<meta name="robots" content="follow, noindex">'.PHP_EOL;
		}

	}
	
	/**
	 * grain_embed_dc_meta() - Embeds general dublin core meta information
	 *
	 * @since 0.3
	 * @global $GrainOpt 	Grain options
	 * @subpackage META information
	 */
	function grain_embed_dc_meta() 
	{
		global $GrainOpt;
		
		?>
<link rel="schema.dc" href="http://purl.org/dc/elements/1.1/" />
<link rel="schema.foaf" href="http://xmlns.com/foaf/0.1/" />
<?php 		
		$imprint_url = $GrainOpt->get(GRAIN_IMPRINT_URL);
		if( !empty($imprint_url) ): ?>
<meta name="dc.rights" content="(Scheme=URL) <?php echo $imprint_url; ?>" />
<?php endif; ?>
<meta name="dc.language" scheme="RFC3066" content="<?php echo get_bloginfo('language'); ?>">
<meta name="dc.title" content="<?php bloginfo('name'); ?>" />
<meta name="dc.description"  content="<?php bloginfo('name'); ?>, <?php wp_title(); ?>" />
<meta name="dc.creator" content="<?php echo $GrainOpt->get(GRAIN_COPYRIGHT_HOLDER); ?>" />
<meta name="dc.publisher" content="<?php echo $GrainOpt->get(GRAIN_COPYRIGHT_HOLDER); ?>" />
<meta name="dc.type" content="Image" /> 
<meta name="dc.format" content="<?php bloginfo('html_type'); ?>" />
<meta name="dc.subject" content="photo" />
<meta name="foaf.maker" content="<?php echo $GrainOpt->get(GRAIN_COPYRIGHT_HOLDER); ?>" />
<meta name="foaf.topic" content="photo" />	
<?php
	}

	/**
	 * grain_embed_generator_meta() - Embeds generator dublin core meta information
	 *
	 * @since 0.3
	 * @global $GrainOpt 	Grain options
	 * @subpackage META information
	 */
	function grain_embed_generator_meta() 
	{
		// wordpress generator meta gets applied by a hook, so skip it here
		?>
<meta name="theme" content="Grain <?php echo grain_version(); ?>" /> <!-- please leave this for stats -->
<?php
	}

?>