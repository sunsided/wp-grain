<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/



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

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<title><?php bloginfo('name'); ?> <?php wp_title(); ?></title>

<meta http-equiv="content-type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<meta name="copyright" content="(c) <?php echo grain_copyright_years_ex(); ?> <?php echo grain_copyright(); ?>" />
<meta name="author" Content="<?php echo grain_copyright(); ?>" />
<meta name="content-language" Content="<?php echo get_bloginfo('language'); ?>" />

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

<meta name="generator" content="WordPress <?php bloginfo('version'); ?>" /> <!-- leave this for stats -->
<meta name="theme" content="Grain <?php echo GRAIN_THEME_VERSION; ?>" /> <!-- leave this for stats -->

<?php
	if( grain_cc_enabled() ) {	
		$rdf = grain_cc_rdf();
		if(!empty($rdf)) echo '<!--'.$rdf.'-->';
	}
?>

<!-- stylesheets -->
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<?php 
	$style_override = grain_override_style();
	$style_overrides = grain_get_css_overrides();
	if( array_search($style_override, $style_overrides) !== FALSE ):
	?>
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/<?php echo $style_override; ?>" type="text/css" media="screen" />
	<?php
	endif;
?>

<!-- theme js -->
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/boxover.js"></script>
<?php 
	if(grain_eyecandy_use_moofx()): 
?>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/mootools.v1.11.js"></script>
<?php 	
		if(grain_eyecandy_use_reflection()) : 
?>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/reflection.js"></script>
<?php		endif; ?>
<?php endif; ?>
<?php comments_popup_script(600, 600); ?>

<!-- feeds -->
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> Comments RSS Feed" href="<?php bloginfo('comments_rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />

<!-- syndication -->
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!-- etc -->
<?php wp_head(); ?>
</head>
<body id="body">
<div id="page">


<div id="header">
	<div id="header-top"><div id="header-top-inner"></div></div>

	<?php grain_inject_navigation_menu(GRAIN_IS_HEADER); ?>
	<div id="headerimg">
		<h1><a rel="start" href="<?php echo get_settings('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
	</div>
	<div id="header-description"><?php bloginfo('description'); ?></div>

	<div id="header-bottom"><div id="header-bottom-inner"></div></div>
</div>

<div id="content_area">
