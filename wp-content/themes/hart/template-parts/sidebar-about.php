<?php
  $bio = get_the_author_meta('description', 1 );
  $about_image = get_field('image', 'user_1') ? get_field('image', 'user_1')['sizes']['large'] : null;
  $facebook = get_field('facebook', 'user_1');
  $twitter = get_field('twitter', 'user_1');
  $steam = get_field('steam', 'user_1');
  $lastfm = get_field('lastfm', 'user_1');
  $youtube = get_field('youtube', 'user_1');
  $instagram = get_field('instagram', 'user_1');
?>

<div class="sidebar-about">
  <?php if ($about_image): ?>
    <img class="sidebar-about__image" src="<?php echo $about_image; ?>" alt="">
  <?php endif; ?>

  <?php if ($bio): ?>
    <div class="sidebar-about__bio">
      <?php echo wpautop($bio); ?>
    </div>
  <?php endif; ?>

  <?php if ($facebook OR $instagram OR $twitter OR $youtube OR $steam OR $lastfm): ?>
    <div class="sidebar-about__socials">
      <?php if ($facebook): ?><a class="sidebar-about__social button button--icon-only" target="_blank" href="<?php echo $facebook; ?>" style="--icon: '\f39e'; --icon-font: 'Font Awesome 5 Brands'; --icon-weight: 100;"></a><?php endif; ?>
      <?php if ($instagram): ?><a class="sidebar-about__social button button--icon-only" target="_blank" href="<?php echo $instagram; ?>" style="--icon: '\f16d'; --icon-font: 'Font Awesome 5 Brands'; --icon-weight: 100;"></a><?php endif; ?>
      <?php if ($twitter): ?><a class="sidebar-about__social button button--icon-only" target="_blank" href="<?php echo $twitter; ?>" style="--icon: '\e61b'; --icon-font: 'Font Awesome 5 Brands'; --icon-weight: 100;"></a><?php endif; ?>
      <?php if ($youtube): ?><a class="sidebar-about__social button button--icon-only" target="_blank" href="<?php echo $youtube; ?>" style="--icon: '\f167'; --icon-font: 'Font Awesome 5 Brands'; --icon-weight: 100;"></a><?php endif; ?>
      <?php if ($steam): ?><a class="sidebar-about__social button button--icon-only" target="_blank" href="<?php echo $steam; ?>" style="--icon: '\f3f6'; --icon-font: 'Font Awesome 5 Brands'; --icon-weight: 100;"></a><?php endif; ?>
      <?php if ($lastfm): ?><a class="sidebar-about__social button button--icon-only" target="_blank" href="<?php echo $lastfm; ?>" style="--icon: '\f202'; --icon-font: 'Font Awesome 5 Brands'; --icon-weight: 100;"></a><?php endif; ?>
    </div>
  <?php endif; ?>
</div>