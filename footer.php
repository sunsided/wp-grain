<?php
/*     
	This file is part of Grain Theme for WordPress.

    Grain Theme for WordPress is free software: you can redistribute it 
	and/or modify it under the terms of the GNU General Public License 
	as published by the Free Software Foundation, either version 3 of 
	the License, or (at your option) any later version.

    Grain Theme for WordPress is distributed in the hope that it will 
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
	of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Grain Theme for WordPress.  
	If not, see <http://www.gnu.org/licenses/>.
	
	------------------------------------------------------------------
	File version: $Id$	
*/
?>
</div>

<div id="footer">
	<div id="footer-top"><div id="footer-top-inner"></div></div>

        <a title="<?php echo grain_thumbnail_title(__("RSS Feed", "grain"), __("Syndication", "grain")); ?>" href="<?php bloginfo('rss2_url'); ?>"><img onMouseover="this.src='<?php bloginfo('template_directory'); ?>/images/rss-feed.gif'" onMouseout="this.src='<?php bloginfo('template_directory'); ?>/images/rss-feed-low.gif'" id="rss-feed" src="<?php bloginfo('template_directory'); ?>/images/rss-feed-low.gif" alt="RSS feed icon" /></a>
		<?php if(grain_atomfeed_enabled()): ?><a title="<?php echo grain_thumbnail_title(__("Atom Feed", "grain"), __("Syndication", "grain")); ?>" href="<?php bloginfo('atom_url'); ?>"><img onMouseover="this.src='<?php bloginfo('template_directory'); ?>/images/atom-feed.gif'" onMouseout="this.src='<?php bloginfo('template_directory'); ?>/images/atom-feed-low.gif'"id="atom-feed" src="<?php bloginfo('template_directory'); ?>/images/atom-feed-low.gif" alt="Atom feed icon" /></a><?php endif; ?>

		<?php
		if( grain_cc_enabled() ) 
		{
			echo '<div id="license-text">';
			echo '<!--Creative Commons License-->';
			$code = grain_cc_code();
			// remove linebreaks for the style
			$code = str_replace('<br />', '', $code);
			$code = str_replace('<br/>', '', $code);
			$code = str_replace('<br>', '', $code);
			// display code
			echo $code;
			echo '<!--/Creative Commons License-->';
			echo '</div>';
		}
		?>

	<div id="footer-info<?php if( !grain_cc_enabled() ) echo '-padded'; ?>">

		<span class="powered-by">
			<?php bloginfo('name'); ?> <?php _e("is proudly powered by"); ?> <a href="http://wordpress.org/">WordPress</a> <?php _e("and"); ?>
			<a href="http://mac.defx.de/grain-theme" title="Grain <?php echo GRAIN_THEME_VERSION; ?>">Grain</a>
		</span>
		
		<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
		<?php edit_post_link(__("edit post", "grain"), ' | ', ''); ?>

		<div id="final-footer">
			<?php if( grain_flat_syndication_enabled() ): ?>
			<div id="syndication">
				<?php echo grain_flat_syndication( grain_flat_delimiter() ); ?>
			</div>
			<?php endif; ?>
			
			<div id="copyright">
		       		<span><?php _e("Copyright"); ?> &copy; <?php echo grain_copyright_years(); ?> <?php echo grain_copyright_ex(); ?></span>
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
