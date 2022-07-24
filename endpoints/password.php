<?php

function pfDev__api_password_lost($request) {
  $login = $request['login'];
  $url = $request['url'];

  if (empty($login)) {
    $response = new WP_Error('error', 'No e-mail or username', ['status' => 406]);
    return rest_ensure_response($response);
  }
  
  $user = get_user_by('email', $login);
  if (empty($user)) {
    $user = get_user_by('login', $login);
  }

  if (empty($user)) {
    $response = new WP_Error('error', 'User no exists', ['status' => 401]);
    return rest_ensure_response($response);
  }

  $user_login = $user->user_login;
  $user_email = $user->user_email;

  $key = get_password_reset_key($user);

  $message = "Use the link below to reset your password \r\n";
  $link_reset = esc_url_raw($url . '/?key=' . $key . '&login=' . rawurlencode($user_login) . "\r\n");
  $body_email = $message . $link_reset;

  $sent = wp_mail($user_email, 'Password Reset', $body_email);
  
  if ($sent) return rest_ensure_response('Email sent');
  else return rest_ensure_response('Failure to send');
}

function pfDev__register_api_password_lost() {
  $configRoutes = [
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'pfDev__api_password_lost'
  ];

  register_rest_route('api_v1', '/password/lost', $configRoutes);
}

add_action('rest_api_init', 'pfDev__register_api_password_lost');

?>