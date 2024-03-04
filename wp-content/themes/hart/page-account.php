<?php
if (!is_user_logged_in()){
  wp_redirect(home_url());
  exit;
}

$user = wp_get_current_user();
$user_image = get_custom_avatar($user->ID, 'large');

get_header(); ?>

<div class="main">
  <div class="user">
    <?php if (!empty($user_image)): ?>
      <img class="user__image" src="<?php echo $user_image; ?>" alt="<?php echo $user->user_login; ?>">
    <?php else: ?>
      <div class="user__no-image"></div>
    <?php endif; ?>

    <h3 class="user__name"><?php echo $user->user_login; ?></h3>

    <ul class="user__links">
      <?php if (is_current_user_admin()): ?>
        <li><a class="user__link button" href="<?php echo admin_url(); ?>" target="_blank" style="--icon: '\f411'; --icon-font: 'Font Awesome 5 Brands'; --icon-weight: 300;">Dashboard</a></li>
      <?php endif; ?>
      <li><a class="user__link button" href="<?php echo get_permalink(get_page_by_path('change-password')); ?>" style="--icon: '\f084';">Password</a></li>
      <li><a class="user__link button" href="<?php echo get_permalink(get_page_by_path('change-picture')); ?>" style="--icon: '\f03e';">Picture</a></li>
      <li>
        <form class="user__logout" method="post">
          <?php wp_nonce_field('logout', 'logout_nonce'); ?>
          <input type="hidden" name="action" value="logout">
          <button class="user__link button" style="--icon: '\f2f5';" type="submit">Logout</button>
        </form>
      </li>
    </ul>
  </div>
</div>

<?php get_template_part("template-parts/sidebar", '', null); ?>

<?php get_footer(); ?>