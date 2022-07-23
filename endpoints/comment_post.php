<?php

function pfDev__api_comment_post($request) {
  $user = wp_get_current_user();
  $user_id = (int) $user->ID;

  $post_id = $request['id'];
  $comment = sanitize_text_field($request['comment']);

  if (!$user_id) {
    $response = new WP_Error('error', 'No permision', ['status' => 401]);
  } else if (empty($post_id) || empty($comment)) {
    $response = new WP_Error('error', 'Data no found', ['status' => 422]);
  } else {
    $response = [
      'comment_author' => $user->user_login,
      'comment_content' => $comment,
      'comment_post_ID' => $post_id,
      'user_id' => $user_id
    ];

    $comment_id = wp_insert_comment($response);
    $response = get_comment($comment_id);
  }

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