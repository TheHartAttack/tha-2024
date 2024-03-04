<?php
if (!is_user_logged_in()){
  wp_redirect(home_url());
  exit;
}

get_header(); ?>

<div class="main">
  <div class="change-password">
    <form class="change-password__form form" method="post" novalidate>
      <?php wp_nonce_field('change_password', 'change_password_nonce'); ?>
      <input type="hidden" name="action" value="change_password">

      <div class="form__header">
        <h3 class="form__heading">Change Your Password</h3>
      </div>

      <div class="form__group">
        <label class="form__label" for="change-password-new">New Password</label>
        <input class="form__input" id="change-password-new" type="password" name="change_password_new" required>
      </div>

      <div class="form__group">
        <label class="form__label" for="change-password-confirm">Confirm Password</label>
        <input class="form__input" id="change-password-confirm" type="password" name="change_password_confirm" required>
      </div>
      
      <div class="form__footer">
        <button class="form__submit" type="submit" name="change_password_submit" style="--icon: '\f084';">Change Password</button>
        <div class="form__footer-links">
          <a class="form__footer-link" href="<?php echo get_permalink(get_page_by_path('account')); ?>">Cancel</a>
        </div>
      </div>

      <?php if (isset($_POST['change_password_message'])): ?>
        <span class="form__message"><?php echo $_POST['change_password_message']; ?></span>
      <?php endif; ?>
    </form>
  </div>
</div>

<?php get_template_part("template-parts/sidebar", '', null); ?>

<?php get_footer(); ?>