<?php
wp_redirect(home_url());
exit;

$user = get_user_by('ID', get_query_var('author'));
$user_image = get_field('image', "user_$user->ID") ? get_field('image', "user_{$user->ID}")['sizes']['large'] : '';

$postLikesQuery = new WP_Query(array(
  'post_type' => 'post_like',
  'author' => $user->ID
));
$postLikes = $postLikesQuery->posts;

$likedPosts = array();
foreach ($postLikes as $postLike):
  array_push($likedPosts, get_post(get_field('liked_post_id', $postLike->ID)));
endforeach;

get_header(); ?>

<div class="main">
  <div class="user">
    <div class="user__info">
      <?php if (!empty($user_image)): ?>
        <img class="user__image" src="<?php echo $user_image; ?>" alt="<?php echo $user->user_login; ?>">
      <?php else: ?>
        <div class="user__no-image"></div>
      <?php endif; ?>

      <h3 class="user__name"><?php echo $user->user_login; ?></h3>

      <?php if ($user->ID == get_current_user_ID()): ?>
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
      <?php endif; ?>
    </div>

    <div class="user__column user__column--likes">
        <h3 class="user__column-heading">Liked Posts:</h3>
        <?php if ($likedPosts): ?>
          <?php foreach ($likedPosts as $likedPost):
            get_template_part('template-parts/post-panel', '', array('ID' => $likedPost->ID));
          endforeach; ?>
        <?php else: ?>
          <p><?php echo $user->user_login; ?> has not liked any posts yet.</p>
        <?php endif; ?>
    </div>

    <div class="user__column user__column--comments">
        <h3 class="user__column-heading">Comments:</h3>
        <?php if ($likedPosts): ?>
          <?php foreach ($likedPosts as $likedPost): ?>
            <div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Exercitationem at voluptates tempora aspernatur placeat error, consectetur voluptatum, tempore incidunt, minima non? Quod, ad fugiat. Consequatur nulla excepturi ipsum architecto sint.</div>
          <?php endforeach; ?>
        <?php else: ?>
          <p><?php echo $user->user_login; ?> has not posted any comments yet.</p>
        <?php endif; ?>
    </div>
  </div>
</div>

<?php get_template_part("template-parts/sidebar", '', null); ?>

<?php get_footer(); ?>