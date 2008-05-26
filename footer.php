<?php
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$	
*/

	global $GrainOpt;
?>
</div>

<div id="footer-complete">
	<div id="footer">
	<div id="footer-top"><div id="footer-top-inner"></div></div>

		<div id="newsfeed-icons">
		<?php 
			// embed ATOM feed icon, if wanted
			if($GrainOpt->getYesNo(GRAIN_FEED_ATOM_ENABLED)) grain_embed_atom_icon();
		
			// embed the RSS icon
			grain_embed_rss_icon();
		?>
		</div>

		<?php
			// embed Creative Commons license hint, if wanted
			if( !grain_ispopup() && $GrainOpt->getYesNo(GRAIN_COPYRIGHT_CC_ENABLED) ) grain_embed_cc_div(); 
		?>

		<div id="footer-info" class="<?php if( !$GrainOpt->getYesNo(GRAIN_COPYRIGHT_CC_ENABLED) ) echo 'no-cclicense'; else echo 'has-cclicense'; ?>">

			<span class="powered-by">
				<?php
				
				$blogName = '<span class="blogname">'.get_bloginfo('name').'</span>';
				$grainURL = '<a class="grain" href="'.GRAIN_THEME_URL.'" title="Grain">Grain</a>';
				$wordpressURL = '<a class="wordpress" href="http://wordpress.org/">WordPress</a>';
				$message = __("{BLOG} is proudly powered by {WP} and {GRAIN}");
				echo str_replace( 
					array( "{BLOG}", "{WP}", "{GRAIN}" ), 
					array( $blogName, $wordpressURL, $grainURL ), 
					$message );
				
				?>
			</span>
			
			<span id="edit-post-link"><?php if( !grain_ispopup() && grain_getpostcount() ) edit_post_link(__("edit post", "grain"), ' | ', ''); ?></span>
		</div>

		<div id="wp-footer"><?php wp_footer(); ?></div>
		
		<div id="final-footer">
			
			<div id="syndication">
				<?php 
				if( !grain_ispopup() && $GrainOpt->getYesNo(GRAIN_SYND_FLAT_ENABLED) ) {
					echo grain_flat_syndication( $GrainOpt->get(GRAIN_SYND_FLAT_DELIMITER) );
				} ?>
			</div>
			
			<div id="copyright">
					<span id="copyright-content"><?php _e("Copyright", "grain"); ?> <?php grain_embed_copyright(TRUE); ?></span>
			</div>
			
		</div>
		<div id="footer-bottom"><div id="footer-bottom-inner"></div></div>
	</div> <!-- footer -->
</div> <!-- footer-complete -->

</div>

</div>

</div>
</body>
</html> <!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
