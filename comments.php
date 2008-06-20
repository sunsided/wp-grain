<?php 
/*     
	This file is part of Grain Theme for WordPress.
	------------------------------------------------------------------
	File version: $Id$	
*/

	// Do not delete these lines
	
	if ('comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ("Please don't call this page directly");

	if (!empty($post->post_password)) { // if there's a password
		if ($_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			?>
			
			<p class="nocomments"><?php _e("The comments are password protected, too"); ?><p>
			
			<?php
			return;
		}
	}

	// Get options
	global $GrainOpt, $userdata, $post;
	
	// get the ID
	if( empty($userdata) ) get_currentuserinfo();
	$user_ID = $userdata->ID;
	
	// get some values
	$id = $post->ID;
	
	/* This variable is for alternating comment background */
	$oddcomment = 'alt';
?>

<!-- You can start editing here. -->

<div id="comment-frame">

<a name="comments"></a>
<?php if($GrainOpt->is(GRAIN_EYECANDY_USE_SLIDE)) : ?>
<a href="#" id="comments-toggle" name="comments-toggle">
<?php endif ?>

	<h2 id="comments-headline"><?php 
		$comments_title = str_replace( "%", $post->comment_count, __("Comments (%)", "grain"));
		echo $comments_title; 
	?></h2>

<?php if($GrainOpt->is(GRAIN_EYECANDY_USE_SLIDE)) : ?>
</a>	
<?php endif ?>
	
	<div id="comment-frame-body">

		<div id="comment-rss-feed-container">
			<?php 
				$linktext_short = __("RSS feed for comments on this photo", "grain");
				$linktext = str_replace("RSS", "<abbr title=\"Really Simple Syndication\">RSS</abbr>", $linktext_short);
				$thumb_title = grain_thumbnail_title( __("Syndication", "grain"), $linktext_short );
			?>
			<a href="feed:<?php bloginfo('comments_rss2_url'); ?>&p=<?php echo $post->ID; ?>"><img id="comments-rss-feed" title="<?php echo $thumb_title ; ?>" src="<?php echo GRAIN_TEMPLATE_DIR; ?>/images/rss-feed.gif" alt="RSS feed icon" title="RSS feed" />
			<span class="linktext"><?php echo $linktext; ?></span></a>
		</div>

		<?php if ('open' == $post->ping_status) { ?>
		<div id="trackback-info"><?php _e("The <acronym title=\"Uniform Resource Identifier\">(URI)</acronym> to TrackBack this photo is:", "grain"); ?> <span class="trackback-url"><?php trackback_url() ?></span></div>
		<?php } 
		
		// this line is WordPress' motor, do not delete it.
		// it takes the latest input from the cookies to prefill the forms with the values
		$commenter = wp_get_current_commenter();
		extract($commenter);	
			
		// get all commments, approved as well as unapproved
		$comments = grain_get_comments($post->ID);
		
		// test for password protection
		if (!empty($post->post_password) && $_COOKIE['wp-postpass_'. COOKIEHASH] != $post->post_password) {  // and it doesn't match the cookie
			echo(get_the_password_form());
		} else { ?>

		<div id="comment-area">
			<?php if ($comments) { ?>
			<ol id="commentlist">
			<?php 
				global $comment;
				foreach ($comments as $comment): 
			
				$is_author_comment = ($comment->user_id == $post->post_author);		
				$is_syndicated = ($comment->comment_type == 'trackback' || $comment->comment_type == 'pingback');		
				$author_url = get_comment_author_url();
				$has_url = (!empty($author_url) && $author_url != 'http://');

				// check if this comment is approved
				$is_approved = ($comment->comment_approved == "1");
				$user_can_moderate = !empty($user_ID) && current_user_can('moderate_comments');

				if( !$is_approved && !$user_can_moderate ) continue;

				?>
				<li class="commentbody<?php 
					if( $is_author_comment ) // if the comment was written by the author of the comment
						echo "-author";
					else if( $is_syndicated ) // if the comment came via trackback or pingback
						echo "-syndication";
				?>" id="comment-<?php echo $comment->comment_ID; ?>">
					
					<div class="comment-boxed">
					
					<div class="comment-text">
						<?php grain_inject_commenteditlink($comment); ?>
						
					
						<div class="comment-text-inner" <?php if($GrainOpt->getYesNo(GRAIN_EYECANDY_GRAVATARS_ENABLED)) echo ' style="min-height: '.$GrainOpt->get(GRAIN_EYECANDY_GRAVATAR_SIZE).'px;"'; ?>>				
						
						<?php if($is_syndicated): ?>
							<span class="syndication-comment-info">
							<img class="syndication-comment-icon" src="<?php echo GRAIN_TEMPLATE_DIR; ?>/icons/syndicated.png" />
							<span class="syndication-comment-type">
							<?php 
							echo ($comment->comment_type == 'trackback' ? __("Trackback", "grain") : __("Pingback", "grain"));
							?></span></span><?php 
						endif; 
						
						// Generate the Tooltip
						$thumbURL = $has_url ? $comment->comment_author_url : __("no website", "grain");
						if( $is_author_comment ) $thumbURL = __("post author", "grain");
						$thumbTitle = grain_thumbnail_title($comment->comment_author, $thumbURL);
						
						// Generate the gravatar
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
								<img class="gravatar" style="border:1px solid white;" src="<?php echo $gravatar_uri; ?>" title="<?php echo (!$disableLinkedGravatar ? $thumbTitle : $comment->comment_author); ?>" title="<?php echo $comment->comment_author; ?>" />
								<?php if($has_url && !$disableLinkedGravatar) echo '</a>'; ?>
							</div>
						
						<?php 
						endif; //gravatar
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
							$string = str_replace('%AUTHOR', '<span class="author"><a title="'.(empty($thumbTitle) ? $comment->comment_author : $thumbTitle).'" class="comment-author-link" href="'.get_comment_author_url().'">'.get_comment_author().'</a></span>', $string);
						else
							$string = str_replace('%AUTHOR', '<span class="author">'.$comment->comment_author.'</span>', $string);
						$string = str_replace('%IP', '<span class="ip">'.get_comment_author_IP().'</span>', $string);
						$string = str_replace('%DATE', '<span class="date">'.get_comment_date( grain_filter_dt($GrainOpt->get(GRAIN_DFMT_COMMENTS))).'</span>', $string );
						$string = str_replace('%TIME', '<span class="time">'.get_comment_date( grain_filter_dt($GrainOpt->get(GRAIN_TFMT_COMMENTS))).'</span>', $string );
						$string = str_replace('%TYPE', '<span class="type">'.($comment->comment_type == 'trackback' ? __("Trackback", "grain") : $comment->comment_type == 'pingback' ? __("Pingback", "grain") : __("Comment", "grain")).'</span>', $string );
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

		<input type="hidden" name="comment_post_ID" value="<?php echo $post->ID; ?>" />
		<input type="hidden" name="redirect_to" value="<?php echo wp_specialchars($_SERVER["REQUEST_URI"]); ?>" />

<?php if ( $user_ID ) : ?>

			<p id="comment-field-loggedin" >
				<?php _e("Your are logged in as", "grain"); ?> 
				<a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $userdata->display_name; ?></a>. 
				<a href="<?php echo get_option('siteurl'); ?>/wp-login.php?action=logout" title="<?php _e("logout", "grain"); ?>"><?php _e("Logout &raquo;", "grain"); ?></a>
			</p>

			<!--
			<input 
					type="hidden"
					name="author"
					id="author"
					value="<?php echo $comment_author; ?>"
					/>
					
			<input 
					type="hiden"
					name="email"
					id="email"
					value="<?php echo $comment_author_email; ?>"
					/>
					
			<input 
					type="hidden"
					name="url"
					id="url"
					value="<?php echo $comment_author_url; ?>"
					/>-->

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
					rev="vote-for"
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
					rev="vote-for"
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