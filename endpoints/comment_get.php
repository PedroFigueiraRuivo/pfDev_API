<?php

function pfDev__api_comment_get($request) {
  $post_id = $request['id'];

  $comments = get_comments([
    'post_id' => $post_id
  ]);

  return rest_ensure_response($comments);
}

function pfDev__register_api_comment_get() {
  $configRoutes = [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'pfDev__api_comment_get'
  ];

  register_rest_route('api_v1', '/comment/(?P<id>[0-9]+)', $configRoutes);
}

add_action('rest_api_init', 'pfDev__register_api_comment_get');

?>