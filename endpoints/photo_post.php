<?php

function pfDev__api_photo_post($request) {
  $user = wp_get_current_user();

  $user_id = $user->ID;

  if (!$user_id) {
    $response = new WP_Error('error', 'User dont have permission', ['status' => 401]);
  } else {
    $name = sanitize_text_field($request['name']);
    $weight = sanitize_text_field($request['weight']);
    $age = sanitize_text_field($request['age']);
    $files = $request->get_file_params();

    if (empty($name) || empty($weight) || empty($age) || empty($files)) {
      $response = new WP_Error('error', 'Incomplete data', ['status' => 422]);
    } else {
      $response = [
        'post_author' => $user_id,
        'post_type' => 'post',
        'post_status' => 'publish',
        'post_title' => $name,
        'post_content' => $name,
        'files' => $files,
        'meta_input' => [
          'weight' => $weight,
          'age' => $age,
          'hits' => 0
        ]
      ];
      $post_id = wp_insert_post($response);

      require_once ABSPATH . 'wp-admin/includes/image.php';
      require_once ABSPATH . 'wp-admin/includes/file.php';
      require_once ABSPATH . 'wp-admin/includes/media.php';


      $photo_id = media_handle_upload('img', $post_id);
      update_post_meta( $post_id, 'img', $photo_id);
    }
  }

  return rest_ensure_response($response);
}

function pfDev__register_api_photo_post() {
  $configRoutes = [
    'methods' => WP_REST_Server::CREATABLE,
    'callback' => 'pfDev__api_photo_post'
  ];

  register_rest_route('api_v1', '/photo', $configRoutes);
}

add_action('rest_api_init', 'pfDev__register_api_photo_post');

?>