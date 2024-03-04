<?php
if (!isset($_SERVER['HTTP_REFERER'])){
  wp_redirect(home_url());
} else if ($_SERVER['HTTP_REFERER'] != get_permalink(get_page_by_path('change-password'))){
  wp_redirect(home_url());
  exit;
}

get_header(); ?>

<div class="main">
  <div class="password-updated">
    <h3 class="password-updated__heading">Password Updated</h3>
    <p class="password-updated__paragraph">Your password has been successfully updated.</p>    
  </div>
</div>

<?php get_template_part("template-parts/sidebar", '', null); ?>

<?php get_footer(); ?>