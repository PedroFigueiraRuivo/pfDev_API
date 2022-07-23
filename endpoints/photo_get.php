<?php

$dirBase = get_template_directory();
require_once($dirBase . '/endpoints/helps/photo_data.php');

function pfDev__api_photo_get($request) {
  $post_id = $request['id'];
  $post = get_post($post_id);

  if (!isset($post) || empty($post_id)) {
    $response = new WP_Error('error', 'Post not found', ['status' => 404]);
  } else {
    $photo = pfDev__photo_data($post);
  
    $photo['hits'] = (int) $photo['hits'] + 1;
    update_post_meta($post_id, 'hits', $photo['hits']);
  
    $comments = get_comments([
      'post_id' => $post_id,
      'order' => 'ASC'
    ]);
  
    $response = [
      'photo' => $photo,
      'comments' => $comments
    ];
  }

  return rest_ensure_response($response);
}

function pfDev__register_api_photo_get() {
  $configRoutes = [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'pfDev__api_photo_get'
  ];

  register_rest_route('api_v1', '/photo/(?P<id>[0-9]+)', $configRoutes);
}

add_action('rest_api_init', 'pfDev__register_api_photo_get');

?>