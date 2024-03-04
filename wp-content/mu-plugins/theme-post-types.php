<?php

function theme_post_types(){
  register_post_type('post_like', array(
    'public' => false,
    'show_ui' => true,
    'supports' => array('title'),
    'labels' => array(
      'name' => 'Post Likes',
      'add_new_item' => 'Add New Post Like',
      'edit_item' => 'Edit Post Like',
      'all_items' => 'All Post Likes',
      'singular_name' => 'Post Like'
    ),
    'menu_icon' => 'dashicons-heart'
  ));

  register_post_type('comment_like', array(
    'public' => false,
    'show_ui' => true,
    'supports' => array('title'),
    'labels' => array(
      'name' => 'Comment Likes',
      'add_new_item' => 'Add New Comment Like',
      'edit_item' => 'Edit Comment Like',
      'all_items' => 'All Comment Likes',
      'singular_name' => 'Comment Like'
    ),
    'menu_icon' => 'dashicons-thumbs-up'
  ));
}
add_action('init', 'theme_post_types');