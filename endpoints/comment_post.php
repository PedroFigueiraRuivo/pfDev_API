<?php

function pfDev__api_comment_post($request) {
  $user = wp_get_current_user();
  $user_id = $user->ID;

  if ($user_id === 0) {
    $response = new WP_Error('error', 'Sem permisão.', ['status' => 401]);
    return rest_ensure_response($response);
  }

  $comment = sanitize_text_field($request['comment']);
  $post_id = $request['id'];

  if (empty($comment)) {
    $response = new WP_Error('error', 'Dados incompletos.', ['status' => 422]);
    return rest_ensure_response($response);
  }

  $response = [
    'comment_author' => $user->user_login,
    'comment_content' => $comment,
    'comment_post_ID' => $post_id,
    'user_id' => $user_id,
  ];

  $comment_id = wp_insert_comment($response);
  $comment = get_comment($comment_id);

  return rest_ensure_response($comment);
}

function pfDev__register_api_comment_post() {
  $configRoutes = [
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'pfDev__api_comment_post'
  ];

  register_rest_route('api_v1', '/comment/(?P<id>[0-9]+)', $configRoutes);
}

add_action('rest_api_init', 'pfDev__register_api_comment_post');

?>