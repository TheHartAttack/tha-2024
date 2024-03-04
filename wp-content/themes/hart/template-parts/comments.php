<?php
  $comments = get_comments(array(
    'orderby' => 'comment_date',
    'order' => 'DESC',
    'post_id' => get_the_ID(),
    'status' => 'approve',
    'parent' => 0
  ));

  foreach ($comments as $comment){
    $author = get_user_by('id', $comment->user_id);
    $comment->comment_author = $author->user_login;
    $comment->comment_author_id = $author->ID;

    $likeCount = new WP_Query(array(
      'post_type' => 'comment_like',
      'meta_query' => array(
        array(
          'key' => 'liked_comment_id',
          'compare' => '=',
          'value' => $comment->comment_ID
        )
      )
    ));
    $comment->like_count = $likeCount->found_posts;
  }

  usort($comments, function($a, $b){
    return $b->like_count <=> $a->like_count;
  });

  $total_comments = count($comments);
  $comments = array_slice($comments, 0, get_option('comments_per_page'));
?>

<div class="comments" data-post="<?php echo get_the_ID(); ?>">
  <span class="comments__count"><span><?php echo $total_comments; ?></span> comment<?php echo $total_comments == 1 ? "" : "s"; ?></span>

  <?php if (is_user_logged_in()): ?>
    <form class="comments__form form" method="post">
      <?php wp_nonce_field('comment', 'comment_nonce'); ?>
      <input type="hidden" name="action" value="comment">

      <div class="form__group">
        <input class="form__input form__input--comments" type="text" name="comment" placeholder="Add a comment..." autocomplete="off" required>
      </div>
      
      <div class="form__footer form__footer--justify-end form__footer--hidden">
        <div class="form__footer-links">
          <a class="form__footer-link form__footer-link--cancel" href="#">Cancel</a>
        </div>
        <button class="form__submit" type="submit" name="comment_submit" style="--icon: '\f075';">Post Comment</button>
      </div>

      <?php if (isset($_POST['comment_message'])): ?>
        <span class="form__message"><?php echo $_POST['comment_message']; ?></span>
      <?php endif; ?>
    </form>
  <?php else: ?>
      <p class="comments__logged-out">Please log in to leave a comment.</p>
  <?php endif; ?>


  <?php if (count($comments)): ?>
    <div class="comments__comments">
      <?php foreach ($comments as $comment): ?>
        <?php

        $alreadyLiked = "false";
        if (is_user_logged_in()){
          $existQuery = new WP_Query(array(
            'author' => get_current_user_ID(),
            'post_type' => 'comment_like',
            'meta_query' => array(
              array(
                'key' => 'liked_comment_id',
                'compare' => '=',
                'value' => $comment->comment_ID
              )
            )
          ));

          if ($existQuery->found_posts){
            $alreadyLiked = "true";
          }
        } ?>

        <div class="comment" data-id="<?php echo $comment->comment_ID; ?>" data-like="<?php if (isset($existQuery->posts[0]->ID)) echo $existQuery->posts[0]->ID; ?>" data-liked="<?php echo $alreadyLiked; ?>">
          <img class="comment__image" src="<?php echo get_custom_avatar($comment->comment_author_id); ?>" alt="<?php echo $comment->comment_author; ?>">

          <div class="comment__info">
            <span class="comment__author"><?php echo $comment->comment_author; ?></span>
            <span class="comment__time"><?php echo time_ago($comment->comment_date); ?></span>
          </div>

          <p class="comment__content"><?php echo $comment->comment_content; ?></p>

          <div class="comment__likes">
            <div class="comment__like <?php echo is_user_logged_in() ? 'comment__like--active' : ''; ?>">
              <span class="comment__like-count"><?php echo $comment->like_count; ?></span>
              <i class="comment__like-icon fa-regular fa-thumbs-up"></i>
              <i class="comment__like-icon fa-solid fa-thumbs-up"></i>
            </div>       
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    
    <?php if (get_comment_count(get_the_ID())['total_comments'] > count($comments)): ?>
      <a class="comments__load button" href="#" style="--icon: '\f141';">Load more</a>
    <?php endif; ?>
  <?php endif; ?>
</div>