<?php
	
	if(!defined('GRAIN_THEME_VERSION') ) die(basename(__FILE__));


/* functions */

	define('GRAIN_POSTTYPE_PHOTO', 'photo');
	define('GRAIN_POSTTYPE_SPLITPOST', 'split-post');

	function grain_posttype($post_id, $default=GRAIN_POSTTYPE_PHOTO) {
		
		return GRAIN_POSTTYPE_PHOTO;
		//return GRAIN_POSTTYPE_SPLITPOST;
		//return 'split-post';
		
	}
	
	function grain_get_the_content() {
		global $post;
	
		$array = explode('<!--more-->', $post->post_content);
		//$content = get_the_content('', 0, '');
		$content = $array[0];
		$content = apply_filters('get_the_content', $content);
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		$content = apply_filters(GRAIN_GET_THE_CONTENT, $content);
		return $content;
	}
	
	function grain_get_the_special_content() {
		global $post;
	
		$array = explode('<!--more-->', $post->post_content);
		//$content = get_the_content('', 0, '');
		$content = $array[1];
		$content = apply_filters('get_the_content', $content);
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		$content = apply_filters(GRAIN_THE_SPECIAL_CONTENT, $content);
		return $content;
	}

?>