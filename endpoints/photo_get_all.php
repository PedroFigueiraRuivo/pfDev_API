<?php

$dirBase = get_template_directory();
require_once($dirBase . '/endpoints/helps/photo_data.php');


function pfDev__api_photos_get($request) {
  $_total = sanitize_text_field($request['_total']) ?: 6;
  $_page = sanitize_text_field($request['_page']) ?: 1;
  $_user = sanitize_text_field($request['_user']) ?: 0;

  if (!is_numeric($_user)) {
    $user = get_user_by('login', $_user);

    if (!$user) {
      $response = new WP_Error('error', 'User not found', ['status' => 404]);
      return rest_ensure_response($photos);
    }
    $_user = $user->ID;
  }

  $args = [
    'post_type' => 'post',
    'author' => $_user,
    'posts_per_page' => $_total,
    'paged' => $_page,
  ];

  $query = new WP_Query($args);
  $posts = $query->posts;
  
  $photos = [];
  if ($posts) {
    foreach ($posts as $post){
      $photos[] = pfDev__photo_data($post);
    }
  }

  return rest_ensure_response($photos);
}

function pfDev__register_api_photos_get() {
  $configRoutes = [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'pfDev__api_photos_get'
  ];

  register_rest_route('api_v1', '/photo', $configRoutes);
}

add_action('rest_api_init', 'pfDev__register_api_photos_get');


?>