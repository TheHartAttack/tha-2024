<?php

//Debug
if (!function_exists('dbug')) {
  function dbug($object) {
    print '<pre>';
    var_dump($object);
    print '</pre>';
  }
}

//Gets custom user profile image URL
function get_custom_avatar($id, $size = 'thumbnail'){
  $user = get_user_by('id', $id);	

  if ($user && is_object($user)) {
    $image = get_field('image', "user_$user->ID");
    if ($image){
      $avatar = $image ? $image['sizes'][$size] : '';
    } else {
      $avatar = '';
    }
  } else {
    $avatar = '';
  }

  return $avatar;
}

//Removes http:// and www. from URLs
function remove_http_www($url){
  $remove_http = '#^http(s)?://#';
  $remove_www = '/^www\./';
  $replace = '';
  $new_link = preg_replace($remove_http, $replace, $url);
  $new_link = preg_replace($remove_www, $replace, $new_link);
  return $new_link;
}

//Checks if current user is admin
function is_current_user_admin(){
  if (!is_user_logged_in()){
    return false;
  }

  return in_array('administrator',  wp_get_current_user()->roles);
}

//Returns day comment was posted as 'today', 'yesterday' or date
function get_posted_day($id){
  $today = date('jS F Y');
  $yesterday = date('jS F Y', time() - 86400);
  $postedDay = get_comment_date('jS F Y', $id);
  if ($today == $postedDay){
    $postedDay = 'today';
  } else if ($yesterday == $postedDay){
    $postedDay = 'yesterday';
  }
  return $postedDay;
}

//Returns time ago string
function time_ago ($time) {
  $timeCalc = strtotime(date("Y-m-d H:i:s")) - strtotime($time);
  if ($timeCalc >= (60*60*24*30*12*2)){
    $timeCalc = intval($timeCalc/60/60/24/30/12) . " years ago";
    }else if ($timeCalc >= (60*60*24*30*12)){
      $timeCalc = intval($timeCalc/60/60/24/30/12) . " year ago";
    }else if ($timeCalc >= (60*60*24*30*2)){
      $timeCalc = intval($timeCalc/60/60/24/30) . " months ago";
    }else if ($timeCalc >= (60*60*24*30)){
      $timeCalc = intval($timeCalc/60/60/24/30) . " month ago";
    }else if ($timeCalc >= (60*60*24*2)){
      $timeCalc = intval($timeCalc/60/60/24) . " days ago";
    }else if ($timeCalc >= (60*60*24)){
      $timeCalc = " Yesterday";
    }else if ($timeCalc >= (60*60*2)){
      $timeCalc = intval($timeCalc/60/60) . " hours ago";
    }else if ($timeCalc >= (60*60)){
      $timeCalc = intval($timeCalc/60/60) . " hour ago";
    }else if ($timeCalc >= 60*2){
      $timeCalc = intval($timeCalc/60) . " minutes ago";
    }else if ($timeCalc >= 60){
      $timeCalc = intval($timeCalc/60) . " minute ago";
    }else if ($timeCalc > 0){
      $timeCalc .= " seconds ago";
    }
  return $timeCalc;
  }

  //Custom email template
  function hart_email($content){
    ob_start();
    get_template_part('template-parts/html-email', '', array(
      'content' => $content
    ));
    $var = ob_get_contents();
    ob_end_clean();
    return $var;
  }