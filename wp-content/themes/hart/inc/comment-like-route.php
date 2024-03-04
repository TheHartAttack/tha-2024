<?php
function commentLikeRoutes(){
  register_rest_route('hart/v1', 'manageCommentLike', array(
    'methods' => 'POST',
    'callback' => 'createCommentLike',
    'permission_callback' => '__return_true'
  ));

  register_rest_route('hart/v1', 'manageCommentLike', array(
    'methods' => 'DELETE',
    'callback' => 'deleteCommentLike',
    'permission_callback' => '__return_true'
  ));
}
add_action('rest_api_init', 'commentLikeRoutes');

function createCommentLike($data){
  if (!is_user_logged_in()){
    die(json_encode(array(
      'success' => false,
      'message' => 'Only logged in users can like comments.'
    )));
  }

  $commentID = sanitize_text_field($data['commentID']);
  $comment = get_comment($commentID);
  $commentAuthor = get_user_by('id', $comment->user_id);
  $post = get_post($comment->comment_post_ID);

  $userLikeQuery = new WP_Query(array(
    'author' => get_current_user_id(),
    'post_type' => 'comment_like',
    'meta_query' => array(
      array(
        'key' => 'liked_comment_id',
        'compare' => '=',
        'value' => $commentID
      )
    )
  ));

  if ($userLikeQuery->found_posts != 0 OR !$comment){
    die(json_encode(array(
      'success' => false,
      'message' => 'Invalid comment ID.'
    )));
  }

  $commentLike = wp_insert_post(array(
    'post_type' => 'comment_like',
    'post_status' => 'publish',
    'post_title' => wp_get_current_user()->user_login . ' liked ' . $commentAuthor->user_login . '\'s comment on ' . $post->post_title,
    'meta_input' => array(
      'liked_comment_id' => $commentID
    )
  ));

  if (!$commentLike){
    die(json_encode(array(
      'success' => false,
      'message' => 'Something went wrong.'
    )));
  }

  return array(
    'success' => true,
    'commentLike' => $commentLike
  );
}

function deleteCommentLike($data){
  $likeID = sanitize_text_field($data['likeID']);

  if (get_current_user_ID() != get_post_field('post_author', $likeID) OR get_post_type($likeID) != 'comment_like'){
    die(json_encode(array(
      'success' => false,
      'message' => 'You do not have permission to delete this like.'
    )));
  }

  wp_delete_post($likeID, true);
  return array(
    'success' => true,
    'message' => 'Like deleted.'
  );
}