<?php get_header(); ?>

<div class="main">
  <?php if (have_posts()): while (have_posts()):
    the_post();

    $likeCount = new WP_Query(array(
      'post_type' => 'post_like',
      'meta_query' => array(
        array(
          'key' => 'liked_post_id',
          'compare' => '=',
          'value' => get_the_ID()
        )
      )
    ));


    $alreadyLiked = "false";
    if (is_user_logged_in()){
      $existQuery = new WP_Query(array(
        'author' => get_current_user_ID(),
        'post_type' => 'post_like',
        'meta_query' => array(
          array(
            'key' => 'liked_post_id',
            'compare' => '=',
            'value' => get_the_ID()
          )
        )
      ));

      if ($existQuery->found_posts){
        $alreadyLiked = "true";
      }
    }
  ?>

  <div class="post">
    <img class="post__image" src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full'); ?>" alt="">
    <h3 class="post__heading"><?php echo get_the_title(); ?></h3>
    <time class="post__date"><?php the_date('jS F Y'); ?></time>
    <div class="post__content"><?php the_content(); ?></div>
    <div class="post__footer">
      <div class="post__share">
        <i class="fa-solid fa-share-nodes"></i>
        <a class="post__share-link post__share-link--facebook" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink(); ?>"><i class="fa-brands fa-facebook-f"></i></a>
        <a class="post__share-link post__share-link--twitter" target="_blank" href="https://twitter.com/intent/tweet?text=<?php echo get_the_title(); ?>&url=<?php echo get_the_permalink(); ?>"><i class="fa-brands fa-x-twitter"></i></a>
      </div>
      <div class="post__like <?php echo is_user_logged_in() ? 'post__like--active' : ''; ?>" data-like="<?php if (isset($existQuery->posts[0]->ID)) echo $existQuery->posts[0]->ID; ?>" data-liked="<?php echo $alreadyLiked; ?>" data-post="<?php echo get_the_ID(); ?>">
        <span class="post__like-count"><?php echo $likeCount->found_posts; ?></span>
        <i class="post__like-icon fa-regular fa-heart"></i>
        <i class="post__like-icon fa-solid fa-heart"></i>
      </div>
    </div>

    <?php get_template_part("template-parts/comments"); ?>
  </div>
  
  <?php endwhile; endif; ?>
</div>

<?php get_template_part("template-parts/sidebar", '', null); ?>

<?php get_footer(); ?>