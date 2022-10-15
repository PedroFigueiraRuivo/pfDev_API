<?php

function pfDev__api_user_post($request) {
  $email = sanitize_email($request['email']);
  $username = sanitize_text_field($request['username']);
  $password = $request['password'];

  if (empty($email) || empty($username) || empty($password)) {
    $response = new WP_Error('error', 'Dados incompletos', ['status' => 406]);
  } else if (username_exists($username) || email_exists($email)) {
    $response = new WP_Error('error', 'E-mail já cadastrado', ['status' => 403]);
  } else {
    $response = wp_insert_user([
      'user_email' => $email,
      'user_login' => $username,
      'user_pass' => $password,
      'role' => 'subscriber'
    ]);
  }

  return rest_ensure_response($response);
}

function pfDev__register_api_user_post() {
  $configRoutes = [
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'pfDev__api_user_post'
  ];

  register_rest_route('api_v1', '/user', $configRoutes);
}

add_action('rest_api_init', 'pfDev__register_api_user_post');

?>