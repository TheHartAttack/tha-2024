<?php get_header(); ?>

<div class="main">
  <div class="contact">
    <form class="contact__form form" method="post" novalidate>
      <?php wp_nonce_field('contact', 'contact_nonce'); ?>
      <input type="hidden" name="action" value="contact">

      <div class="form__header">
        <h3 class="form__heading">Contact Me</h3>
      </div>

      <?php if (!is_user_logged_in()): ?>
        <div class="form__group">
          <label class="form__label" for="contact-name">Name</label>
          <input class="form__input" id="contact-name" type="text" name="contact_name" required>
        </div>

        <div class="form__group">
          <label class="form__label" for="contact-email">Email</label>
          <input class="form__input" id="contact-email" type="email" name="contact_email" required>
        </div>
      <?php else: ?>
        <?php $user = wp_get_current_user(); ?>
        <input type="hidden" name="contact_name" value="<?php echo $user->user_login; ?>">
        <input type="hidden" name="contact_email" value="<?php echo $user->user_email; ?>">
      <?php endif; ?>

      <div class="form__group">
        <label class="form__label" for="contact-content">Message</label>
        <textarea class="form__textarea form__textarea--large" id="contact-content"name="contact_content"></textarea>
      </div>
      
      <div class="form__footer">
        <button class="form__submit" type="submit" name="contact_submit" style="--icon: '\f0e0';">Send Message</button>
      </div>

      <?php if (isset($_POST['contact_message'])): ?>
        <span class="form__message <?php echo isset($_POST['contact_success']) ? 'form__message--success' : '';?>"><?php echo $_POST['contact_message']; ?></span>
      <?php endif; ?>
    </form>
  </div>
</div>

<?php get_template_part("template-parts/sidebar", '', null); ?>

<?php get_footer(); ?>