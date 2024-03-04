<?php
  $user = wp_get_current_user();
  $image = get_custom_avatar($user->ID);
?>

<div class="sidebar-user">
    <?php if ($image): ?>
      <img class="sidebar-user__image" src="<?php echo $image; ?>" alt="">
    <?php else: ?>
      <div class="sidebar-user__no-image"></div>
    <?php endif; ?>
    <span class="sidebar-user__username"><?php echo $user->user_login; ?></span>
    <a class="sidebar-user__account button" href="<?php echo get_permalink(get_page_by_path('account')); ?>" style="--icon: '\f007';">Account</a>
</div>