<?php
function comment_routes(){
  register_rest_route('hart/v1', 'addComment', array(
    'methods' => 'POST',
    'callback' => 'add_comment',
    'permission_callback' => '__return_true'
  ));

  register_rest_route('hart/v1', 'loadMoreComments', array(
    'methods' => 'GET',
    'callback' => 'load_more_comments',
    'permission_callback' => '__return_true'
  ));
}
add_action('rest_api_init', 'comment_routes');

function add_comment($data){
  if (!is_user_logged_in()){
    die(json_encode(array(
      'success' => false,
      'message' => 'Only logged in users can post comments.'
    )));
  }

  $user = wp_get_current_user();
  $post_id = sanitize_text_field($data['postID']);
  $parent_id = sanitize_text_field($data['parentID']);
  $comment = sanitize_text_field($data['comment']);

  //Validate data
  if (get_post_type($post_id) != 'post'){
    die(json_encode(array(
      'success' => false,
      'message' => 'Invalid post.'
    )));
  }

  if (empty($comment)){
    die(json_encode(array(
      'success' => false,
      'message' => 'Comment cannot be blank.'
    )));
  }

  //Check user hasn't posted already in the last 60 seconds
  $userLastPost = get_comments(array(
    'user_id' => $user->ID,
    'orderby' => 'post_date',
    'order' => 'DESC',
    'number' => 1
  ));

  $userLastPostDate = $userLastPost[0]->comment_date;
  if (!strlen($userLastPostDate)){
    $userLastPostDate = '2000-01-01 00:00:00';
  }
  $currentDate = current_time('mysql');
  $datetime1 = date_format(date_create($userLastPostDate), 'U');
  $datetime2 = date_format(date_create($currentDate), 'U');
  $interval = $datetime2 - $datetime1;

  if ($interval < 60){
    die(json_encode(array(
      'success' => false,
      'message' => 'You must wait another '.(60 - $interval).' seconds before posting another comment.'
    )));
  }

  $newComment = wp_insert_comment(array(
    'comment_post_ID' => $post_id,
    'user_id' => $user->ID,
    'comment_content' => $comment,
    'comment_parent' => $parent_id
  ));

  if (!$newComment){
    die(json_encode(array(
      'success' => false,
      'message' => 'Something went wrong.'
    )));
  }

  return array(
    'success' => true,
    'newComment' => array(
      'ID' => $newComment,
      'content' => $comment,
      'author' => array(
        'name' => $user->user_login,
        'url' => get_author_posts_url($user->ID),
        'image' => get_custom_avatar($user->ID)
      )
    )
  );
}

function load_more_comments($data){
  $comments = get_comments(array(
    'orderby' => 'comment_date',
    'order' => 'DESC',
    'post_id' => $data['postID'],
    'status' => 'approve',
    'parent' => 0,
    'comment__not_in' => $data['alreadyLoaded']
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

  $sliced_comments = array_slice($comments, 0, get_option('comments_per_page'));
  $moreToLoad = count($comments) - count($sliced_comments);
  $response_array = array();

  foreach ($sliced_comments as $comment){
    array_push($response_array, array(
      'ID' => $comment->comment_ID,
      'content' => $comment->comment_content,
      'author' => array(
        'name' => $comment->comment_author,
        'url' => get_author_posts_url($comment->comment_author_id),
        'image' => get_custom_avatar($comment->comment_author_id)
      )
    ));
  }

  return array(
    'success' => true,
    'comments' => $response_array,
    'moreToLoad' => $moreToLoad
  );
}