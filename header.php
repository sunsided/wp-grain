<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));

	/* META headers */
	
	// embed general meta information for statistics
	function grain_embed_general_meta() 
	{
		?>
<meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<meta name="copyright" content="<?php grain_embed_copyright(); ?>" />
<meta name="author" Content="<?php echo grain_copyright(); ?>" />
<meta name="content-language" Content="<?php echo get_bloginfo('language'); ?>" />
<?php
	}
	
	// embed Dublin Core meta information
	function grain_embed_dc_meta() 
	{
		?>
<link rel="schema.dc" href="http://purl.org/dc/elements/1.1/" />
<link rel="schema.foaf" href="http://xmlns.com/foaf/0.1/" />
<?php 		
		$imprint_url = grain_imprint_url(null);
		if( !empty($imprint_url) ): ?>
<meta name="dc.rights" content="(Scheme=URL) <?php echo $imprint_url; ?>" />
<?php endif; ?>
<meta name="dc.language" scheme="RFC3066" content="<?php echo get_bloginfo('language'); ?>">
<meta name="dc.title" content="<?php bloginfo('name'); ?>>" />
<meta name="dc.description"  content="<?php bloginfo('name'); ?>, <?php wp_title(); ?>" />
<meta name="dc.creator" content="<?php echo grain_copyright(); ?>" />
<meta name="dc.publisher" content="<?php echo grain_copyright(); ?>" />
<meta name="dc.type" content="Image" /> 
<meta name="dc.format" content="<?php bloginfo('html_type'); ?>" />
<meta name="dc.subject" content="photo" />
<meta name="foaf.maker" content="<?php echo grain_copyright(); ?>" />
<meta name="foaf.topic" content="photo" />	
<?php
	}

	// embed creative commons RDF
	function grain_embed_cc_rdf() 
	{
		if( !grain_cc_enabled() ) return;

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