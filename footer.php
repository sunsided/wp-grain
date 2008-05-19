<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$	
*/
?>
</div>

<div id="footer">
	<div id="footer-top"><div id="footer-top-inner"></div></div>

		<?php 
			// embed the RSS icon
			grain_embed_rss_icon();
			
			// embed ATOM feed icon, if wanted
			if(grain_atomfeed_enabled()) grain_embed_atom_icon();

			// embed Creative Commons license hint, if wanted
			if( grain_cc_enabled() ) grain_embed_cc_div(); 
		?>

	<div id="footer-info<?php if( !grain_cc_enabled() ) echo '-padded'; ?>">

		<span class="powered-by">
			<?php bloginfo('name'); ?> <?php _e("is proudly powered by"); ?> <a href="http://wordpress.org/">WordPress</a> <?php _e("and"); ?>
			<a href="<?php echo GRAIN_THEME_URL; ?>" title="Grain <?php echo GRAIN_THEME_VERSION; ?>">Grain</a>
		</span>
		
		<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
		<?php if( grain_getpostcount() ) edit_post_link(__("edit post", "grain"), ' | ', ''); ?>

		<div id="final-footer">
			<?php if( grain_flat_syndication_enabled() ): ?>
			<div id="syndication">
				<?php echo grain_flat_syndication( grain_flat_delimiter() ); ?>
			</div>
			<?php endif; ?>
			
			<div id="copyright">
		       		<span><?php _e("Copyright"); ?> <?php grain_embed_copyright(TRUE); ?></span>
			</div>
			
		 </div>
	</div

	<?php wp_footer(); ?>
	
	<div id="footer-bottom"><div id="footer-bottom-inner"></div></div>	
</div>
</div>

</div>

</div>
</body>
</html>
