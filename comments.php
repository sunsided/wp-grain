<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$	
*/


	// Do not delete these lines
	
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die (__("Please don't call this page directly"));

        if (!empty($post->post_password)) { // if there's a password
            if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
				?>
				
				<p class="nocomments"><?php _e("The comments are password protected, too"); ?><p>
				
				<?php
				return;
            }
        }

		/* This variable is for alternating comment background */
		$oddcomment = 'alt';
?>

<!-- You can start editing here. -->

<div id="comment-frame">

<a name="comments"></a>
<?php if(grain_can_inject_moofx_slide()) : ?>
<a href="#" id="comments-toggle" name="comments-toggle">
<?php endif ?>

	<h2 id="comments-headline"><?php 
		$comments_title = str_replace( "%", $post->comment_count, __("Comments (%)", "grain"));
		echo $comments_title; 
	?></h2>

<?php if(grain_can_inject_moofx_slide()) : ?>
</a>	
<?php endif ?>
	
	<div id="comment-frame-body">

		<p>
		<a href="feed:<?php bloginfo('comments_rss2_url'); ?>"><img id="comments-rss-feed" src="<?php echo GRAIN_TEMPLATE_DIR; ?>/images/rss-feed.gif" alt="RSS feed icon" title="RSS feed" />
		<?php _e("<abbr title=\"Really Simple Syndication\">RSS</abbr> feed for comments on this photo.", "grain"); ?></a></p>

		<?php if ('open' == $post->ping_status) { ?>
		<p><?php _e("The <acronym title=\"Uniform Resource Identifier\">(URI)</acronym> to TrackBack this photo is:", "grain"); ?><br /><em><?php trackback_url() ?></em></p>
		<?php } 
		
		// this line is WordPress' motor, do not delete it.
		$commenter = wp_get_current_commenter();
		extract($commenter);	
		
		// get all commments, approved as well as unapproved
		$comments = grain_get_comments($id);
		$post = get_post($id);
		if (!empty($post->post_password) && $_COOKIE['wp-postpass_'. COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			echo(get_the_password_form());
		} else { ?>

		<div id="comment-area">
			<?php if ($comments) { ?>
			<ol id="commentlist">
			<?php foreach ($comments as $comment): 
			
				$is_author_comment = ($comment->user_id == $post->post_author);		
				$is_syndicated = ($comment->comment_type == 'trackback' || $comment->comment_type == 'pingback');		
				$has_url = (!empty($comment->comment_author_url) && $comment->comment_author_url != 'http://');
			
				?>
				<li class="commentbody<?php 
					if( $is_author_comment ) // if the email addresses match
						echo "-author";
					else if( $is_syndicated ) // if the email addresses match
						echo "-syndication";
				?>" id="comment-<?php comment_ID() ?>">
					
					<div class="comment-boxed">
					
					<div class="comment-text">
						<?php 
						
						// test if the current user can moderate comments
						if( !empty($user_ID) && current_user_can('moderate_comments') ):
								$approved = ($comment->comment_approved=='1');
						?>
						<div class="comment-admin-tools<?php if(!$approved) echo "-unapproved"; ?>"><?php if(!$approved) echo '<strong>'.__("unapproved &rarr;", "grain"); ?> <a href="<?php bloginfo('url'); ?>/wp-admin/comment.php?action=editcomment&c=<?php comment_ID() ?>" target="_self" title="<?php echo grain_thumbnail_title(__("edit comment", "grain"), __("comment ID:", "grain") .' '. $comment->comment_ID); ?>"><?php _e("edit comment", "grain"); ?></a><?php if(!$approved) echo '</strong>'; ?></div>
						<?php 
						endif; // moderation test
						
						
						?>
					
						<div class="comment-text-inner" <?php if($GrainOpt->getYesNo(GRAIN_EYECANDY_GRAVATARS_ENABLED)) echo ' style="min-height: '.$GrainOpt->get(GRAIN_EYECANDY_GRAVATAR_SIZE).'px;"'; ?>>				
						
						<?php if($is_syndicated): ?>
							<span class="syndication-comment-info">
							<img class="syndication-comment-icon" src="<?php echo GRAIN_TEMPLATE_DIR; ?>/icons/syndicated.png" />
							<span class="syndication-comment-type">
							<?php 
							echo ($comment->comment_type == 'trackback' ? __("Trackback", "grain") : __("Pingback", "grain"));
							?></span></span><?php endif; ?>
							
						<?php 
							$thumTitle = grain_thumbnail_title($comment->comment_author, $has_url ? $comment->comment_author_url : __("no website", "grain") .' '. $comment->comment_ID);						
							
							if($GrainOpt->getYesNo(GRAIN_EYECANDY_GRAVATARS_ENABLED) && !$is_syndicated) : 
								$gravatar_size = $GrainOpt->get(GRAIN_EYECANDY_GRAVATAR_SIZE);
								$gravatar_uri = grain_get_gravatar_uri($GrainOpt->get(GRAIN_EYECANDY_GRAVATAR_RATING), $gravatar_size, $GrainOpt->get(GRAIN_EYECANDY_GRAVATAR_ALTERNATE));
								$disableLinkedGravatar = !$GrainOpt->getYesNo(GRAIN_EYECANDY_GRAVATARS_LINKED);
						?>
						
						<div class="comment-gravatar" style="width: <?php echo $gravatar_size; ?>px; height: <?php echo $gravatar_size; ?>px;">
							<?php if($has_url && !$disableLinkedGravatar) {
								$author_url = '<a href="'.$comment->comment_author_url.'">%CONTENT</a>';							
								echo '<a href="'.$comment->comment_author_url.'">';
							} ?>
							<img class="gravatar" style="border:1px solid white;" src="<?php echo $gravatar_uri; ?>" title="<?php echo (!$disableLinkedGravatar ? $thumTitle : $comment->comment_author); ?>" title="<?php echo $comment->comment_author; ?>" />
							<?php if($has_url && !$disableLinkedGravatar) echo '</a>'; ?>
						</div>
						
						<?php endif; //gravatar
						 ?>
							
						<?php comment_text() ?>			
						
						</div>
					</div>

					<div class="comment-meta">
						<?php if($is_author_comment): ?>
						<img class="author-comment-icon" src="<?php echo GRAIN_TEMPLATE_DIR; ?>/icons/camera.png" />
						<?php endif; ?>
					
					<?php

						$string = __("%AUTHOR &#8212; %DATE at %TIME", "grain");
						if($has_url)
							$string = str_replace('%AUTHOR', '<a title="'.(empty($thumTitle) ? $comment->comment_author : $thumTitle).'" class="comment-author-link" href="'.get_comment_author_url().'">'.get_comment_author().'</a>', $string);
						else
							$string = str_replace('%AUTHOR', $comment->comment_author, $string);
						$string = str_replace('%IP', get_comment_author_IP(), $string);
						$string = str_replace('%DATE', get_comment_date( grain_filter_dt($GrainOpt->get(GRAIN_DFMT_COMMENTS))), $string );
						$string = str_replace('%TIME', get_comment_date( grain_filter_dt($GrainOpt->get(GRAIN_TFMT_COMMENTS))), $string );
						$string = str_replace('%TYPE', $comment->comment_type == 'trackback' ? __("Trackback", "grain") : $comment->comment_type == 'pingback' ? __("Pingback", "grain") : __("Comment", "grain"), $string );
						echo $string;

					?>
					</div>
					
					</div>
				</li>
			
			<?php endforeach; // end for each comment ?>
			</ol>
			<?php } else { // this is displayed if there are no comments so far ?>
				<p><?php _e("Be the first to comment!", "grain"); ?></p>
			<?php } ?>
		</div>

		<?php if ('open' == $post->comment_status) { ?>
		<h2 id="write-comment-headline"><?php _e("Leave a comment", "grain"); ?></h2>
		<p><?php _e("Line and paragraph breaks automatic, e-mail address never displayed, <acronym title=\"Hypertext Markup Language\">HTML</acronym> allowed: ", "grain"); ?> <code><?php echo allowed_tags(); ?></code></p>

		<form action="<?php echo get_settings('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">

		<input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
		<input type="hidden" name="redirect_to" value="<?php echo wp_specialchars($_SERVER["REQUEST_URI"]); ?>" />

<?php if ( $user_ID ) : ?>

			<p id="comment-field-loggedin" >
				<?php _e("Your are logged in as", "grain"); ?> 
				<a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. 
				<a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e("logout", "grain"); ?>"><?php _e("Logout &raquo;", "grain"); ?></a>
			</p>

<?php else : ?>		
		
			<p id="comment-field-author" class="comment-form-input">
				<label for="author">
				<img id="comment-field-author-icon" src="<?php echo GRAIN_TEMPLATE_DIR; ?>/icons/author.png" />
				<input 
					class="textfield"
					type="text" 
					name="author" 
					id="author" 
					value="<?php echo $comment_author; ?>" 
					size="28" 
					tabindex="1" />
				<?php _e("Your Name:", "grain"); ?></label>
			</p>

			<p id="comment-field-email" class="comment-form-input">
				<label for="email">
				<img id="comment-field-email-icon" src="<?php echo GRAIN_TEMPLATE_DIR; ?>/icons/email.png" />
				<input 
					class="textfield"
					type="text" 
					name="email" 
					id="email" 
					value="<?php echo $comment_author_email; ?>" 
					size="28" 
					tabindex="2" />
				<?php _e("E-mail (invisible):", "grain"); ?></label>
			</p>

			<p id="comment-field-url" class="comment-form-input">
				<label for="url">
				<img id="comment-field-url-icon" src="<?php echo GRAIN_TEMPLATE_DIR; ?>/icons/address.png" />
				<input 
					class="textfield"
					type="text" 
					name="url" 
					id="url" 
					value="<?php echo $comment_author_url; ?>" 
					size="28" 
					tabindex="3" />
				<?php _e("Website (<acronym title=\"Uniform Resource Locator\">URL</acronym>):", "grain"); ?></label>
			</p>

<?php endif; ?>					

			<p id="comment-field-text" class="comment-form-input">
				<label for="comment">
					<img id="comment-field-text-icon" src="<?php echo GRAIN_TEMPLATE_DIR; ?>/icons/comment.png" />
					<?php _e("Your comment:", "grain"); ?>
				</label>
			<br />
				<textarea 
					class="textarea" 
					name="comment" 
					id="comment" 
					cols="70" 
					rows="4" 
					tabindex="4"></textarea>
			</p>

			<p id="comment-field-submit" class="comment-form-input">
				<input 
					id="submit" name="submit" type="submit" tabindex="5" value="<?php _e("Say it!", "grain"); ?>" />
				<span id="addon-buttons-frame">
				
				<?php if($GrainOpt->getYesNo(GRAIN_EYECANDY_PBORG_BOOKMARK_ENABLED)) : 
					$uri = $GrainOpt->get(GRAIN_EYECANDY_PBORG_URI);
					if(!empty($uri)):
				?>
				<input 
					class="addon-button"
					name="photoblogs-org" 
					type="button" 
					id="photoblogs-org" 
					value="<?php _e("bookmark me at photoblogs.org", "grain"); ?>" 
					onclick="window.open('<?php echo $uri; ?>')" />
				<?php endif; endif; ?>	
				
				<?php if($GrainOpt->getYesNo(GRAIN_EYECANDY_COOLPB_ENABLED)) : 
					$uri = $GrainOpt->get(GRAIN_EYECANDY_COOLPB_URI);
					if(!empty($uri)):
				?>
				<input 
					class="addon-button"
					name="coolphotoblogs-com" 
					type="button" 
					id="coolphotoblogs-com" 
					value="<?php _e("vote for me at CoolPhotoblogs", "grain"); ?>" 
					onclick="window.open('<?php echo $uri; ?>')" />
				<?php endif; endif; ?>
				</span>
			</p>
			
			<?php do_action('comment_form', $post->ID); ?>
		</form>
		<?php } else { // comments are closed ?>
			<div id="comments-closed"><?php _e("Sorry, the comment form is closed at this time.", "grain"); ?></div>
		<?php }
		} // end password check
		?>
	</div> <!-- #comment-frame-body -->
</div> <!-- #comment-frame -->