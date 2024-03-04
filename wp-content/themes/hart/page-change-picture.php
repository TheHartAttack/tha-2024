<?php
if (!is_user_logged_in()){
  wp_redirect(home_url());
}

$image = get_field('image', 'user_'.get_current_user_id());

get_header(); ?>

<div class="main">
  <div class="change-picture">
    <form class="change-picture__form form" method="post" enctype="multipart/form-data" novalidate>
    <?php wp_nonce_field('change_picture', 'change_picture_nonce'); ?>
      <input type="hidden" name="action" value="change_picture">

      <div class="form__header">
        <h3 class="form__heading">Change Your Picture</h3>
      </div>

      <div class="form__upload <?php if ($image): ?>form__upload--preview<?php endif; ?>" <?php if ($image): ?>style="--bg-image: url(<?php echo $image['sizes']['large']; ?>);"<?php endif; ?>>
        <label class="form__upload-label" for="change-picture-file"></label>
        <input class="form__upload-input" id="change-picture-file" type="file" name="change_picture_file" accept="image/gif, image/jpeg, image/jpg, image/png">
        <input class="form__upload-existing" type="hidden" name="change_picture_existing" value="<?php echo $image ? $image['ID'] : ''; ?>">
      </div>
      
      <div class="form__footer">
        <button class="form__submit" type="submit" name="change_picture_submit" style="--icon: '\f03e';">Change Picture</button>
        <div class="form__footer-links">
          <a class="form__footer-link" href="<?php echo get_permalink(get_page_by_path('account')); ?>">Cancel</a>
        </div>
      </div>

      <?php if (isset($_POST['change_picture_message'])): ?>
        <span class="form__message"><?php echo $_POST['change_picture_message']; ?></span>
      <?php endif; ?>
    </form>
  </div>
</div>

<?php get_template_part("template-parts/sidebar", '', null); ?>

<?php get_footer(); ?>