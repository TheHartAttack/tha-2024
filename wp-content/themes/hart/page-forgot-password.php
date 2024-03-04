<?php
if (is_user_logged_in()){
  wp_redirect(get_author_posts_url(get_current_user_id()));
  exit;
}

get_header(); ?>

<div class="main">
  <div class="forgot-password">
    <form class="forgot-password__form form" method="post" novalidate>
      <?php wp_nonce_field('forgot_password', 'forgot_password_nonce'); ?>
      <input type="hidden" name="action" value="forgot_password">

      <div class="form__header">
        <h3 class="form__heading">Forgot Your Password?</h3>
      </div>

      <div class="form__group">
        <label class="form__label" for="forgot-password-username">Username/Email</label>
        <input class="form__input" id="forgot-password-username" type="text" name="forgot_password_user" required>
      </div>
      
      <div class="form__footer">
        <button class="form__submit" type="submit" name="forgot_password_submit" style="--icon: '\f09c';">Reset Password</button>
      </div>

      <?php if (isset($_POST['forgot_password_message'])): ?>
        <span class="form__message"><?php echo $_POST['forgot_password_message']; ?></span>
      <?php endif; ?>
    </form>
  </div>
</div>

<?php get_template_part("template-parts/sidebar", '', null); ?>

<?php get_footer(); ?>