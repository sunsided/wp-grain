<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/

global $GrainOpt;
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

<!-- feeds -->
<link rel="alternate" type="application/rss+xml" title="RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/rss+xml" title="Comments RSS Feed" href="<?php bloginfo('comments_rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<?php if( $GrainOpt->is(GRAIN_FTR_MEDIARSS) ): ?>
<link rel="alternate" type="application/rss+xml" title="MediaRSS:<?php bloginfo('name'); ?>" id="gallery" href="<?php bloginfo('url'); ?>/?feed=mediarss" />
<?php endif; ?>

<!-- syndication -->
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel="home" href="<?php echo get_settings('home'); ?>" />

<!-- etc -->
<?php wp_head(); ?>

</head>

<body id="body">

<!-- theme js -->
<?php grain_embed_javascripts(); ?>

<?php
	$page_id = grain_ispopup() ? "commentspopup" : "page";
	$contentarea_id = grain_ispopup() ? "comment-page" : "content_area";
?>

<!-- the page -->
<div id="<?php echo $page_id; ?>">

	<!-- header -->
	<div id="header-complete">
		<div id="header">
			<div id="header-top"><div id="header-top-inner"></div></div>
	
			<?php 
			
				if(!grain_ispopup()) grain_inject_navigation_menu(GRAIN_IS_HEADER); 
				
				// link to the main page
				$href = grain_ispopup() ? "javascript:close();" : get_settings('home');
				$rel = grain_ispopup() ? "start" : "home";
			?>
			
			<!-- header image -->
			<div id="blogtitle-complete">
				<div id="headerimg">
					<h1 id="header-title"><a rel="<?php echo $rel; ?>" href="<?php echo $href; ?>"><?php bloginfo('name'); ?></a></h1>
				</div>
				
				<!-- blog description -->
				<div id="header-description">
					<a rel="<?php echo $rel; ?>" href="<?php echo $href; ?>"><?php bloginfo('description'); ?></a>
				</div>
			</div>
	
		</div>
		<div id="header-bottom"><div id="header-bottom-inner"></div></div>
	</div>

	<!-- content following -->
	<div id="<?php echo $contentarea_id; ?>"<?php global $contentarea_class; if(!@empty($contentarea_class)) echo 'class="'.$contentarea_class.'"';?>>