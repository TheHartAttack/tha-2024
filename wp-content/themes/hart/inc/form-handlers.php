<?php

function handle_custom_login(){
  if (isset($_POST['action']) AND $_POST['action'] == 'login'){
    if (!isset($_POST['login_nonce']) || !wp_verify_nonce($_POST['login_nonce'], 'login')){
      $_POST['login_message'] = 'Invalid request.';
      return;
    }

    $creds = array();
    $creds['user_login'] = sanitize_text_field($_POST['login_username']);
    $creds['user_password'] = sanitize_text_field($_POST['login_password']);
    $creds['remember'] = true;
  
    $user = wp_signon($creds, false);
    if (is_wp_error($user)){
      if (array_key_exists('empty_username', $user->errors)){
        $_POST['login_message'] = 'Username field empty.';
      } elseif (array_key_exists('empty_password', $user->errors)){
        $_POST['login_message'] = 'Password field empty.';
      } elseif (array_key_exists('invalid_username', $user->errors)){
        $_POST['login_message'] = 'Invalid username.';
      } elseif (array_key_exists('incorrect_password', $user->errors)){
        $_POST['login_message'] = 'Incorrect password.';
      }
      return;
    } else {
      wp_clear_auth_cookie();
      wp_set_current_user($user->ID);
      wp_set_auth_cookie($user->ID, true);
      do_action('wp_login', $user->ID);
      global $wp;
      wp_redirect($_SERVER['HTTP_REFERER']);
      exit;
    }
  }
}
add_action('after_setup_theme', 'handle_custom_login');

function handle_custom_register(){
  if (isset($_POST['action']) AND $_POST['action'] == 'register'){
    if (!isset($_POST['register_nonce']) || !wp_verify_nonce($_POST['register_nonce'], 'register')){
      $_POST['register_message'] = 'Invalid request.';
      return;
    }

    if (is_user_logged_in()){
      $_POST['register_message'] = 'You are already logged in to an account.';
      return;
    }

    $username = sanitize_text_field($_POST['register_username']);
    $email = sanitize_text_field($_POST['register_email']);
    $password = sanitize_text_field($_POST['register_password']);
    $confirm = sanitize_text_field($_POST['register_confirm']);

    //Username validation
    if (empty($username)){
      $_POST['register_message'] = 'You must enter a username.';
      return;
    }
    if (strlen($username) < 4){
      $_POST['register_message'] = 'The username must be at least 4 characters.';
      return;
    }
    if (strlen($username) > 32){
      $_POST['register_message'] = 'The username must not be more than 32 characters.';
      return;
    }
    
    //Email validation
    if (empty($email)){
      $_POST['register_message'] = 'You must enter an email address.';
      return;
    }
    if (!is_email($email)){
      $_POST['register_message'] = 'Invalid email address.';
      return;
    }
    
    //Password validation
    if (empty($password)){
      $_POST['register_message'] = 'You must enter a password.';
      return;
    }
    if (empty($confirm)){
      $_POST['register_message'] = 'You must enter a password confirmation.';
      return;
    }
    if ($password != $confirm){
      $_POST['register_message'] = 'The password and the confirmation do not match.';
      return;
    }

    $user = wp_create_user($username, $password, $email);

    if (is_wp_error($user)){
      if (array_key_exists('existing_user_login', $user->errors)){
        $_POST['register_message'] = 'That username is taken.';
      } elseif (array_key_exists('existing_user_email', $user->errors)){
        $_POST['register_message'] = 'There is already an account using that email.';
      }
      return;
    }

    $logged_in = wp_signon(array(
      'user_login' => $username,
      'user_password' => $password,
      'remember' => true
    ), false);

    wp_clear_auth_cookie();
    do_action('wp_login', $user);
    wp_set_current_user($user);
    wp_set_auth_cookie($user, true);
    wp_redirect(get_author_posts_url($user));
    exit;
  }
}
add_action('after_setup_theme', 'handle_custom_register');

function handle_custom_logout(){
  if (isset($_POST['action']) AND $_POST['action'] == 'logout'){
    if (!isset($_POST['logout_nonce']) || !wp_verify_nonce($_POST['logout_nonce'], 'logout')){
      $_POST['logout_message'] = 'Invalid request.';
      return;
    }

    wp_logout();
    wp_redirect(home_url());
    exit;
  }
}
add_action('after_setup_theme', 'handle_custom_logout');

function handle_forgot_password(){
  if (isset($_POST['action']) AND $_POST['action'] == 'forgot_password'){
    if (!isset($_POST['forgot_password_nonce']) || !wp_verify_nonce($_POST['forgot_password_nonce'], 'forgot_password')){
      $_POST['forgot_password_message'] = 'Invalid request.';
      return;
    }

    $username_or_email = sanitize_text_field($_POST['forgot_password_user']);

    //Check username/email exists
    if (!$username_or_email){
      $_POST['forgot_password_message'] = 'Username/email field empty.';
      return;
    }

    //Determine if username or email and find relevant user account
    if (is_email($username_or_email)){
      $user = get_user_by('email', $username_or_email);
    } else {
      $user = get_user_by('login', $username_or_email);
    }

    do_action('lostpassword_post');
    
    //Check user account was successfully found
    if (!$user){
      $_POST['forgot_password_message'] = 'No account found with this username/email.';
      return;
    }

    //Generate temporary password and update account data
    $temp_password = wp_generate_password();
    $update_user = wp_update_user(array(
      'ID' => $user->ID,
      'user_pass' => $temp_password
      )
    );

    //Check user account was successfully updated
    if (is_wp_error($update_user)){
      $_POST['forgot_password_message'] = 'There was an error while resetting your password.';
      return;
    }

    //Setup and send email
    $from = get_option('admin_email');
    $to = $user->data->user_email;
    $subject = 'Your password for '.get_bloginfo('name').' has been reset';
    $message = hart_email('Your new password is: <strong>'.$temp_password.'</strong>');
    $headers[] = 'MIME-Version: 1.0' . "\r\n";
    $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers[] = "X-Mailer: PHP \r\n";
    $headers[] = 'From: '.get_bloginfo('name').' <'.$from.'>' . "\r\n";

    $sent = wp_mail($to, $subject, $message, $headers);

    if ($sent){
      wp_redirect(get_permalink(get_page_by_path('password-reset')));
      exit;
    } else {
      $_POST['forgot_password_message'] = 'There was a problem sending your new password. Please contact an admin for help.';
      return;
    }

  }
}
add_action('after_setup_theme', 'handle_forgot_password');

function handle_change_password(){
  if (isset($_POST['action']) AND $_POST['action'] == 'change_password' AND is_user_logged_in()){
    if (!isset($_POST['change_password_nonce']) || !wp_verify_nonce($_POST['change_password_nonce'], 'change_password')){
      $_POST['change_password_message'] = 'Invalid request.';
      return;
    }

    $new = sanitize_text_field($_POST['change_password_new']);
    $confirm = sanitize_text_field($_POST['change_password_confirm']);
    $user = wp_get_current_user();

    if (empty($new)){
      $_POST['change_password_message'] = 'You must enter a new password.';
      return;
    }

    if (empty($confirm)){
      $_POST['change_password_message'] = 'You must confirm the new password.';
      return;
    }

    if ($new != $confirm){
      $_POST['change_password_message'] = 'The new password and the confirmation do not match.';
      return;
    }

    if (strlen($new) < 8){
      $_POST['change_password_message'] = 'Your password must be at least 8 characters long.';
      return;
    }

    wp_set_password($new, $user->ID);
    wp_set_auth_cookie($user->ID);
    wp_set_current_user($user->ID);
    do_action('wp_login', $user->user_login, $user);
    wp_redirect(get_permalink(get_page_by_path('password-updated')));
    exit;
  }
}
add_action('after_setup_theme', 'handle_change_password');

function handle_change_picture(){
  if (isset($_POST['action']) AND $_POST['action'] == 'change_picture' AND is_user_logged_in()){
    if (!isset($_POST['change_picture_nonce']) || !wp_verify_nonce($_POST['change_picture_nonce'], 'change_picture')){
      $_POST['change_picture_message'] = 'Invalid request.';
      return;
    }
    
    if (!function_exists('media_handle_upload')) {
      require_once ABSPATH . 'wp-admin/includes/image.php';
      require_once ABSPATH . 'wp-admin/includes/file.php';
      require_once ABSPATH . 'wp-admin/includes/media.php';
    }

    if (!is_user_logged_in()){
      $_POST['change_picture_message'] = 'You must be logged in to perform that action.';
      return;
    }

    if (!isset($_FILES['change_picture_file'])){
      $_POST['change_picture_message'] = 'Image file not found.';
      return;
    }

    $user_id = get_current_user_ID();
    $file = $_FILES['change_picture_file']['size'] != 0 ? $_FILES['change_picture_file'] : null;
    $existing = isset($_POST['change_picture_existing']) ? $_POST['change_picture_existing'] : null;

    if ($file){
      $allowed = array('gif', 'png', 'jpg', 'jpeg');
      $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
      $ext = strtolower($ext);
      $size = $file['size'];

      if (!in_array($ext, $allowed)){
        $_POST['change_picture_message'] = 'Invalid file type - must be JPG, PNG or GIF.';
        return;
      }

      if ($size > 2 * 1024 * 1024){
        $_POST['change_picture_message'] = 'File too big - maximum file size 2MB.';
        return;
      }

      $uploaded_file = media_handle_upload('change_picture_file', 0);

      if (is_wp_error($uploaded_file)){
        $_POST['change_picture_message'] = 'There was an error while uploading the image.';
        return;
      }

      $old_image = get_field('image', 'user_'.$user_id);
      $updated = update_field('image', $uploaded_file, 'user_'.$user_id);

      if ($updated){
        if ($old_image){
          wp_delete_attachment($old_image['ID'], true);
        }

        wp_redirect(get_author_posts_url($user_id));
        exit;
      } else {
        $_POST['change_picture_message'] = 'Your profile picture could not be updated.';
        return;
      }
      
    } else {
      if (!$existing){
        $old_image = get_field('image', 'user_'.$user_id);
        if ($old_image){
          wp_delete_attachment($old_image['ID'], true);
        }
      }

      wp_redirect(get_author_posts_url($user_id));
      exit;
    }

  }
}
add_action('after_setup_theme', 'handle_change_picture');

function handle_contact(){
  if (isset($_POST['action']) AND $_POST['action'] == 'contact'){
    if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'contact')){
      $_POST['contact_message'] = 'Invalid request.';
      return;
    }

    $name = sanitize_text_field($_POST['contact_name']);
    $email = sanitize_text_field($_POST['contact_email']);
    $content = sanitize_textarea_field($_POST['contact_content']);

    if (empty($name)){
      $_POST['contact_message'] = 'You must enter a name.';
      return;
    }

    if (empty($email) OR !is_email($email)){
      $_POST['contact_message'] = 'You must enter a valid email address.';
      return;
    }

    if (empty($content)){
      $_POST['contact_message'] = 'You must enter a message.';
      return;
    }

     //Setup and send email
     $from = $email;
     $to = get_option('admin_email');
     $subject = 'Message from '.$name.' on TheHartAttack.com';
     $message = hart_email($content);
     $headers[] = 'MIME-Version: 1.0' . "\r\n";
     $headers[] = 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
     $headers[] = "X-Mailer: PHP \r\n";
     $headers[] = 'From: '.$name.' <'.$email.'>' . "\r\n";
 
     $sent = wp_mail($to, $subject, $message, $headers);
 
     if ($sent){
      $_POST['contact_message'] = 'Message sent!';
      $_POST['contact_success'] = true;
      return;
     } else {
      $_POST['contact_message'] = 'There was a problem sending your message. Please contact an admin for help.';
      return;
     }
  }
}
add_action('after_setup_theme', 'handle_contact');