<form class="sidebar-login form" method="post" novalidate>
  <?php wp_nonce_field('login', 'login_nonce'); ?>
  <input type="hidden" name="action" value="login">

  <div class="form__group">
    <label class="form__label" for="login-username">Username</label>
    <input class="form__input" id="login-username" type="text" name="login_username" required>
  </div>

  <div class="form__group">
    <label class="form__label" for="login-password">Password</label>
    <input class="form__input" id="login-password" type="password" name="login_password" required>
  </div>

  <div class="form__footer">
    <button class="form__submit" type="submit" name="login_submit" style="--icon: '\f2f6';">Login</button>
    <div class="form__footer-links">
      <a class="form__footer-link form__footer-link--forgot" href="<?php echo get_permalink(get_page_by_path('forgot-password')); ?>">Forgot your password?</a>
      <a class="form__footer-link form__footer-link--register" href="<?php echo get_permalink(get_page_by_path('create-account')); ?>">Create an account</a>
    </div>
  </div>

  <?php if (isset($_POST['login_message'])): ?>
    <span class="form__message login__message"><?php echo $_POST['login_message']; ?></span>
  <?php endif; ?>
</form>