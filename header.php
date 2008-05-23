<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

	/* Session preparing */
	
	function grain_startSession() 
	{	
		global $wp_query;
			
		// start new session
		session_start();
		
		// If the index page is called, pretend it is not
		if( is_home() ):
			$lastposts = get_posts('numberposts=1');
			foreach ($lastposts as $post):
				setup_postdata($post);
			endforeach;
			// pretend we are on a single page so that next/prev post functions work
			$wp_query->is_single = true;
		endif;
		
		
		// extended info mode
		if( isset($_REQUEST['info']) && !empty($_REQUEST['info']) ) {
			// $_SESSION['grain:info'] = $_REQUEST['info'];
			define('GRAIN_REQUESTED_EXINFO', $_REQUEST['info'] == 'on' ? TRUE : FALSE);
			define('GRAIN_REQUESTED_OTEXINFO', FALSE);
		} elseif( isset($_REQUEST['oti']) && !empty($_REQUEST['oti']) ) {
			//$_SESSION['grain:oti'] = $_REQUEST['oti'];
			define('GRAIN_REQUESTED_OTEXINFO', $_REQUEST['oti'] == 'on' ? TRUE : FALSE);
			define('GRAIN_REQUESTED_EXINFO', GRAIN_REQUESTED_OTEXINFO);
		} else {
			define('GRAIN_REQUESTED_OTEXINFO', FALSE);
			define('GRAIN_REQUESTED_EXINFO', FALSE);
		}	
	}
	
	function grain_endSession() 
	{
		if( $_SESSION['grain:oti'] ) {
			session_unregister('grain:oti');
			session_unregister('grain:info');
		}
	}

	/* META headers */
	
	// embed general meta information for statistics
	function grain_embed_general_meta() 
	{
		global $GrainOpt;
		
		?>
<meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="copyright" content="<?php grain_embed_copyright(); ?>" />
<meta name="author" Content="<?php echo $GrainOpt->get(GRAIN_COPYRIGHT_HOLDER); ?>" />
<meta name="content-language" Content="<?php echo get_bloginfo('language'); ?>" />
<?php
	}
	
	// embed Dublin Core meta information
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
<meta name="dc.title" content="<?php bloginfo('name'); ?>>" />
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

	// embed creative commons RDF
	function grain_embed_cc_rdf() 
	{
		global $GrainOpt;
		
		if( !$GrainOpt->get(GRAIN_COPYRIGHT_CC_ENABLED) ) return;

		// test rdf
		$rdf = grain_cc_rdf();
		if(!empty($rdf)) echo '<!--'.$rdf.'-->';
	}

	// embed Generator meta information for statistics
	function grain_embed_generator_meta() 
	{
		// wordpress generator meta gets applied by a hook, so skip it here
		?>
<meta name="theme" content="Grain <?php echo grain_version(); ?>" /> <!-- please leave this for stats -->
		<?php
	}

?>