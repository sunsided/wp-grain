<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/

grain_startSession();

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
<div id="header-complete">
	<div id="header">
		<div id="header-top"><div id="header-top-inner"></div></div>

		<?php grain_inject_navigation_menu(GRAIN_IS_HEADER); ?>
		
		<!-- header image -->
		<div id="blogtitle-complete">
			<div id="headerimg">
				<h1 id="header-title"><a rel="start" href="<?php echo get_settings('home'); ?>/"><?php bloginfo('name'); ?></a></h1>
			</div>
			
			<!-- blog description -->
			<div id="header-description">
				<a rel="start" href="<?php echo get_settings('home'); ?>/"><?php bloginfo('description'); ?></a>
			</div>
		</div>

	</div>
	<div id="header-bottom"><div id="header-bottom-inner"></div></div>
</div>

<!-- content following -->
<div id="content_area">