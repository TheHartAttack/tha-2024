<a class="post-panel" href="<?php the_permalink(); ?>">
  <?php if (has_post_thumbnail()): ?>
    <img class="post-panel__image" src="<?php the_post_thumbnail_url('large'); ?>" alt="<?php the_title(); ?>">
  <?php endif; ?>

  <div class="post-panel__inner">
    <h3 class="post-panel__heading"><?php echo get_the_title(); ?></h3>
    <time class="post-panel__date"><?php echo get_the_date('jS F Y'); ?></time>
    <span class="post-panel__read">Read post</span>
  </div>
</a>