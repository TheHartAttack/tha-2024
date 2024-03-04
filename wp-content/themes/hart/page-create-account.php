<?php
if (is_user_logged_in()){
  wp_redirect(get_author_posts_url(get_current_user_id()));
  exit;
}

get_header(); ?>

<div class="main">
  <div class="register">
    <form class="register__form form" method="post" novalidate>
      <?php wp_nonce_field('register', 'register_nonce'); ?>
      <input type="hidden" name="action" value="register">

      <div class="form__header">
        <h3 class="form__heading">Create An Account</h3>
      </div>

      <div class="form__group">
        <label class="form__label" for="register-username">Username</label>
        <input class="form__input" id="register-username" type="text" name="register_username" required>
      </div>

      <div class="form__group">
        <label class="form__label" for="register-email">Email</label>
        <input class="form__input" id="register-email" type="email" name="register_email" required>
      </div>

      <div class="form__group">
        <label class="form__label" for="register-new">Password</label>
        <input class="form__input" id="register-new" type="password" name="register_password">
      </div>

      <div class="form__group">
        <label class="form__label" for="register-confirm">Confirm Password</label>
        <input class="form__input" id="register-confirm" type="password" name="register_confirm">
      </div>
      
      <div class="form__footer">
        <button class="form__submit" type="submit" name="register_submit" style="--icon: '\2b';">Create Account</button>
      </div>

      <?php if (isset($_POST['register_message'])): ?>
        <span class="form__message"><?php echo $_POST['register_message']; ?></span>
      <?php endif; ?>
    </form>
  </div>
</div>

<?php get_template_part("template-parts/sidebar", '', null); ?>

<?php get_footer(); ?>