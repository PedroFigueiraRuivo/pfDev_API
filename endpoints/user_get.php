<?php

function pfDev__api_user_get($request) {
  $user = wp_get_current_user();

  $user_id = $user->ID;

  if (!$user_id) {
    $response = new WP_Error('error', 'User dont have permission', ['status' => 401]);
  } else {
    $response = [
      'id' => $user_id,
      'username' => $user->user_login,
      'name' => $user->display_name,
      'email' => $user->user_email
    ];
  }

  return rest_ensure_response($response);
}

function pfDev__register_api_user_get() {
  $configRoutes = [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'pfDev__api_user_get'
  ];

  register_rest_route('api_v1', '/user', $configRoutes);
}

add_action('rest_api_init', 'pfDev__register_api_user_get');

?>