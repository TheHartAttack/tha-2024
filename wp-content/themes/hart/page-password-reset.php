<?php
if (!isset($_SERVER['HTTP_REFERER'])){
  wp_redirect(home_url());
} else if ($_SERVER['HTTP_REFERER'] != get_permalink(get_page_by_path('forgot-password'))){
  wp_redirect(home_url());
  exit;
}

get_header(); ?>

<div class="main">
  <div class="password-reset">
    <h3 class="password-reset__heading">Password Reset</h3>
    <p class="password-reset__paragraph">Your password has been successfully reset. Please check your email for your new temporary password.</p>    
  </div>
</div>

<?php get_template_part("template-parts/sidebar", '', null); ?>

<?php get_footer(); ?>