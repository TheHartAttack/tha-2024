<?php

require get_theme_file_path('/inc/utils.php');
require get_theme_file_path('/inc/form-handlers.php');
require get_theme_file_path('/inc/post-like-route.php');
require get_theme_file_path('/inc/comment-route.php');
require get_theme_file_path('/inc/comment-like-route.php');
require get_theme_file_path('/inc/contact-route.php');

function theme_files(){
  wp_enqueue_script('theme_main_scripts', get_theme_file_uri('/build/index.js'), null, '1.0', true);
  wp_enqueue_style('theme_main_styles', get_theme_file_uri('/build/style-index.css'));

  if (is_user_logged_in()){
    $user = wp_get_current_user();
    $user_info = array(
      'name' => $user->user_login,
      'email' => $user->user_email
    );
  } else {
    $user_info = array(
      'name' => '',
      'email' => ''
    );
  }

  wp_localize_script('theme_main_scripts', 'hartData', array(
    'rootUrl' => get_site_url(),
    'nonce' => wp_create_nonce('wp_rest'),
    'loggedIn' => get_current_user_ID(),
    'user' => $user_info
  ));
}
add_action('wp_enqueue_scripts', 'theme_files');

function theme_features(){
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  register_nav_menu('header_menu', 'Header Menu');
}
add_action('after_setup_theme', 'theme_features');

function hart_custom_rest(){
  register_rest_field('post', 'featured_image', array(
    'get_callback' => function(){return get_the_post_thumbnail_url(get_the_ID(), 'full');}
  ));
}
add_action('rest_api_init', 'hart_custom_rest');

function limit_title($title){
  if (strlen($title) > 99){
    $title = mb_strimwidth(html_entity_decode($title), 0, 99, '');
  }

  return $title;
}
add_filter('the_title', 'limit_title');

function redirect_users_to_frontend() {

  if (!is_user_logged_in()){
    wp_redirect(home_url());
    exit;
  }

  $current_user = wp_get_current_user();
  
  if (!in_array('administrator',  wp_get_current_user()->roles)) {
    wp_redirect(home_url());
    exit;
  }
}
add_action('admin_init', 'redirect_users_to_frontend');
add_action('login_init', 'redirect_users_to_frontend');

function rewrite_author_base(){
  global $wp_rewrite;
  $wp_rewrite->author_base = 'user';
}
add_action('after_setup_theme', 'rewrite_author_base', 1);

//Email testing (remove for production)
// function mailtrap($phpmailer) {
//   $phpmailer->isSMTP();
//   $phpmailer->Host = 'sandbox.smtp.mailtrap.io';
//   $phpmailer->SMTPAuth = true;
//   $phpmailer->Port = 2525;
//   $phpmailer->Username = '931a063394763e';
//   $phpmailer->Password = '86dcea3b49dd90';
// }

// add_action('phpmailer_init', 'mailtrap');

function smtp_setup($phpmailer) {
  $phpmailer->isSMTP();
  $phpmailer->Host = 'mail.thehartattack.com';
  $phpmailer->SMTPAuth = true;
  $phpmailer->Port = 465;
  $phpmailer->Username = 'dan@thehartattack.com';
  $phpmailer->Password = 'M153RY8U5!N355';
  $phpmailer->SMTPSecure = 'ssl';
}

add_action('phpmailer_init', 'smtp_setup');

function search_filter($query) {
  if ($query->is_search && !is_admin() ) {
    $query->set('post_type', array('post'));
  }
  return $query;
}
add_filter('pre_get_posts','search_filter');