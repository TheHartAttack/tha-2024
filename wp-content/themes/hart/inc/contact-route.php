<?php

function contactRoutes(){
  register_rest_route('hart/v1', 'contact', array(
    'methods' => 'POST',
    'callback' => 'handleContact',
    'permission_callback' => '__return_true'
  ));
}
add_action('rest_api_init', 'contactRoutes');

function handleContact($data){
  $name = sanitize_text_field($data['name']);
  $email = sanitize_text_field($data['email']);
  $content = sanitize_textarea_field($data['message']);

  if (empty($name)){
    die(json_encode(array(
      'success' => false,
      'message' => 'You must enter a name.'
    )));
  }

  if (empty($email) OR !is_email($email)){
    die(json_encode(array(
      'success' => false,
      'message' => 'You must enter a valid email address.'
    )));
  }

  if (empty($content)){
    die(json_encode(array(
      'success' => false,
      'message' => 'You must enter a message.'
    )));
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
      return array(
        'success' => true,
        'message' => "Message sent!"
      );
    } else {
      die(json_encode(array(
        'success' => false,
        'message' => 'Something went wrong.'
      )));
    }
}