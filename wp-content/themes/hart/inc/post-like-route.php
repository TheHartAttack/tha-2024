<?php
function postLikeRoutes(){
  register_rest_route('hart/v1', 'managePostLike', array(
    'methods' => 'POST',
    'callback' => 'createPostLike',
    'permission_callback' => '__return_true'
  ));

  register_rest_route('hart/v1', 'managePostLike', array(
    'methods' => 'DELETE',
    'callback' => 'deletePostLike',
    'permission_callback' => '__return_true'
  ));
}
add_action('rest_api_init', 'postLikeRoutes');

function createPostLike($data){
  if (!is_user_logged_in()){
    die(json_encode(array(
      'success' => false,
      'message' => 'Only logged in users can like posts.'
    )));
  }

  $postID = sanitize_text_field($data['postID']);

  $userLikeQuery = new WP_Query(array(
    'author' => get_current_user_id(),
    'post_type' => 'post_like',
    'meta_query' => array(
      array(
        'key' => 'liked_post_id',
        'compare' => '=',
        'value' => $postID
      )
    )
  ));

  if ($userLikeQuery->found_posts != 0 OR get_post_type($postID) != 'post'){
    die(json_encode(array(
      'success' => false,
      'message' => 'Invalid post ID.'
    )));
  }

  $postLike = wp_insert_post(array(
    'post_type' => 'post_like',
    'post_status' => 'publish',
    'post_title' => wp_get_current_user()->user_login . ' liked ' . get_post_field('post_title', $postID),
    'meta_input' => array(
      'liked_post_id' => $postID
    )
  ));

  if (!$postLike){
    die(json_encode(array(
      'success' => false,
      'message' => 'Something went wrong.'
    )));
  }

  return array(
    'success' => true,
    'postLike' => $postLike
  );
}

function deletePostLike($data){
  $likeID = sanitize_text_field($data['likeID']);

  if (get_current_user_ID() != get_post_field('post_author', $likeID) OR get_post_type($likeID) != 'post_like'){
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