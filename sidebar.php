<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$
*/ 
?>
	<div id="sidebar">
		<ul>
			<?php   /* Widgetized sidebar, if you have the plugin installed. */ 
				if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?> 
		
			<?php if( grain_sidebar_calendar_enabled() ): ?>
			<li>
			<?php get_calendar(); ?>
			</li>
			<?php endif; ?>

			<li>
				<?php include (TEMPLATEPATH . '/searchform.php'); ?>
			</li>

			<li>
				<?php /* If this is a 404 page */ if (is_404()) { ?>
				<?php /* If this is a category archive */ } elseif (is_category()) { ?>
				<p><?php
					
					$string = __("You are browsing the archive for the category '%CATEGORY'.", "grain");
					$string = str_replace('%BLOG', '<a href="'.get_settings('siteurl').'">'.get_bloginfo('name').'</a>', $string);
					$string = str_replace('%CATEGORY', single_cat_title('', false), $string);
					echo $string;

				?></p>
				
				<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
				<p><?php

					$string = __("You are browsing the %BLOG archive for %DAY, %DATE.", "grain");
					$string = str_replace('%BLOG', '<a href="'.get_settings('siteurl').'">'.get_bloginfo('name').'</a>', $string);
					$string = str_replace('%DAY', apply_filters('the_time', get_the_time( 'l' ), 'l'), $string);
					$format = grain_filter_dt(grain_dtfmt_dailyarchive());
					$string = str_replace('%DATE', apply_filters('the_time', get_the_time( $format ), $format), $string);
					echo $string;

				?></p>
				
				<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
				<p><?php

					$string = __("You are browsing the %BLOG archive for the month %DATE.", "grain");
					$string = str_replace('%BLOG', '<a href="'.get_settings('siteurl').'">'.get_bloginfo('name').'</a>', $string);
					$format = grain_filter_dt(grain_dtfmt_monthlyarchive());
					$string = str_replace('%DATE', apply_filters('the_time', get_the_time( $format ), $format), $string);
					echo $string;

				?></p>

				<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
				<p><?php

					$string = __("You are browsing the %BLOG archive for the year %YEAR.", "grain");
					$string = str_replace('%BLOG', '<a href="'.get_settings('siteurl').'">'.get_bloginfo('name').'</a>', $string);
					$string = str_replace('%YEAR', apply_filters('the_time', get_the_time( 'Y' ), 'Y'), $string);
					$string = str_replace('%DATE', apply_filters('the_time', get_the_time( 'Y' ), 'Y'), $string);
					echo $string;

				?></p>
				
				<?php /* more links to find */ } elseif (is_search()) { ?>
				<p><?php

					$string = __("You have searched the %BLOG archives for <strong>%SEARCH</strong>. If you are unable to find anything in these search results, you can try one of these links.", "grain");
					$string = str_replace('%BLOG', '<a href="'.get_settings('siteurl').'">'.get_bloginfo('name').'</a>', $string);
					$string = str_replace('%SEARCH', wp_specialchars($s), $string);
					echo $string;
				?></p>

				<?php /* If this is not a specified archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
				<p><?php

                                	$string = __("You are browsing the %BLOG archive.", "grain");
					$string = str_replace('%BLOG', '<a href="'.get_settings('siteurl').'">'.get_bloginfo('name').'</a>', $string);
					echo $string;
				?></p>
				
				<?php } ?>
			</li>

			<?php wp_list_pages('title_li=<h2>'.__("Pages", "grain").'</h2>' ); ?>

			<li><h2><?php _e("Archives by years", "grain"); ?></h2>
			
			<ul>
				<?php
					$years = $wpdb->get_col("SELECT DISTINCT YEAR(post_date) FROM $wpdb->posts WHERE post_status = 'publish' ORDER BY post_date DESC");
					foreach($years as $year) :
						if( $year == 0 || $year =="0" ) continue;
				?>
					<li><a href="<?php echo get_year_link($year); ?> "><?php echo $year; ?></a></li>
				
				<?php endforeach; ?>
			</ul>

			</li>

			<li><h2><?php _e("Archives by month", "grain"); ?></h2>
				<ul>
				<?php wp_get_archives('type=monthly'); ?>
				</ul>
			</li>

			<li><h2><?php _e("Categories", "grain"); ?></h2>
				<ul>
				<?php wp_list_cats('sort_column=name&optioncount=1&hierarchical=0'); ?>
				</ul>
			</li>
			
			<?php if( grain_sidebar_mc_enabled() && function_exists("get_mostcommented") ): ?>
			<li><h2><?php _e("Most commented", "grain"); ?></h2>
			<ul>
				<?php grain_mostcommented(grain_sidebar_mc_count()); ?>
			</ul>
			</li>
			<?php endif; ?>

			<?php /* If this is the frontpage */ if ( is_home() || is_page() ) { ?>
				<?php
					if( grain_sidebar_blogroll_enabled() ):
						get_links_list();
					endif; // blogroll enabled
 				?>

			<?php if( grain_sidebar_meta_enabled() ): ?>
				<li><h2><?php _e("Meta", "grain"); ?></h2>
				<ul>
					<?php wp_register(); ?>
					<li><?php wp_loginout(); ?></li>
					<li><a href="http://validator.w3.org/check/referer" title="This page validates as XHTML 1.0 Transitional">Valid <abbr title="eXtensible HyperText Markup Language">XHTML</abbr></a></li>
					<li><a href="http://gmpg.org/xfn/"><abbr title="XHTML Friends Network">XFN</abbr></a></li>
					<li><a href="http://wordpress.org/" title="Powered by WordPress, state-of-the-art semantic personal publishing platform.">WordPress</a></li>
					<li><a href="http://wordpress-deutschland.org/" title="WordPress Deutschland">WPD</a></li>
					<?php wp_meta(); ?>
				</ul>
				</li>
			<?php
                        	endif; // meta enabled
			} ?>

			<?php if( grain_sidebar_syndication_enabled() ): ?>
			<li><h2><?php _e("Syndicate", "grain"); ?></h2>
				<ul class="syndication-list">
					<?php echo grain_sidebar_syndication() ?>
					<li><a alt="yapb flavoured" href="http://johannes.jarolim.com/yapb"><img class="syndbutton" src="<?php echo GRAIN_TEMPLATE_DIR; ?>/images/yapb.gif" border="0" alt="yapb logo" title="yapb flavoured"></a></li>
				</ul>
			</li>
			<?php endif; ?>
			
			<?php endif; // widgets ?> 
		</ul>
	</div>

