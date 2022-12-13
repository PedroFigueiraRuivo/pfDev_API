<?php

function pfDev__api_test_exclusive($request) {
  
  return rest_ensure_response($request);

}

function pfDev__register_api_test_exclusive() {
  $configRoutes = [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'pfDev__api_test_exclusive'
  ];

  register_rest_route('api_v1', '/exclusive', $configRoutes);
}

add_action('rest_api_init', 'pfDev__register_api_test_exclusive');

?>