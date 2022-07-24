<?php

function pfDev__api_stats_get($request) {
  $user = wp_get_current_user();
  $user_id = $user->ID;

  if (!$user_id) {
    $response = new WP_Error('error', 'User not found', ['status' => 401]);
    return rest_ensure_response($response);
  }

  $args = [
    'post_type' =>  'post',
    'author' => $user_id,
    'posts_per_page' => -1
  ];

  $query = new WP_Query($args);
  $posts = $query->posts;

  if ($posts) {
    $stats = [];
    foreach ($posts as $post) {
      $stats = [
        'id' => $post->ID,
        'title' => $post->post_title,
        'hits' => get_post_meta($post->ID, 'hits', true)
      ];
    }
    return rest_ensure_response($stats);
  } else {
    $response = new WP_Error('error', 'Posts not found', ['status' => 404]);
    return rest_ensure_response($response);
  }

}

function pfDev__register_api_stats_get() {
  $configRoutes = [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'pfDev__api_stats_get'
  ];

  register_rest_route('api_v1', '/stats', $configRoutes);
}

add_action('rest_api_init', 'pfDev__register_api_stats_get');

?>