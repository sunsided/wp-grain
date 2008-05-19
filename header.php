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
<title>
	<?php wp_title('&laquo;',true,'right'); ?>
	<?php bloginfo('name'); ?>
	
</title>

<!-- meta information -->
<?php 
	grain_embed_general_meta();
	grain_embed_dc_meta();
	grain_embed_cc_rdf(); 
	grain_embed_generator_meta();
?>

<!-- stylesheets -->
<?php grain_embed_css(); ?>

<!-- theme js -->
<?php grain_embed_javascripts(); ?>

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

<!-- the page -->
<div id="page">

<!-- header -->
<div id="header">
	<div id="header-top"><div id="header-top-inner"></div></div>

	<?php grain_inject_navigation_menu(GRAIN_IS_HEADER); ?>
	
	<!-- header image -->
	<div id="headerimg">
		<h1><a rel="start" href="<?php echo get_settings('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
	</div>
	
	<!-- blog description -->
	<div id="header-description"><?php bloginfo('description'); ?></div>

	<div id="header-bottom"><div id="header-bottom-inner"></div></div>
</div>

<!-- content following -->
<div id="content_area">
