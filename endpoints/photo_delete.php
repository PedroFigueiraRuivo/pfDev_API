<?php

function pfDev__api_photo_delete($request) {
  $post_id = $request['id'];
  
  $user = wp_get_current_user();
  $post = get_post($post_id);
  
  $user_id = (int) $user->ID;
  $author_id = (int) $post->post_author;

  if ($user_id !== $author_id || !isset($post)) {
    $response = new WP_Error('error', 'No permision', ['status' => 401]);
  } else {
    $attachment_id = get_post_meta($post_id, 'img', true);
    wp_delete_attachment($attachment_id, true);
    wp_delete_post($post_id, true);
    $response = 'deleted post';
  }

  return rest_ensure_response($response);
}

function pfDev__register_api_photo_delete() {
  $configRoutes = [
    'methods' => WP_REST_Server::DELETABLE,
    'callback' => 'pfDev__api_photo_delete'
  ];

  register_rest_route('api_v1', '/photo/(?P<id>[0-9]+)', $configRoutes);
}

add_action('rest_api_init', 'pfDev__register_api_photo_delete');

?>