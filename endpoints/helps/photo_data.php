<?php

function pfDev__photo_data($post) {
  $post_meta = get_post_meta($post->ID);
  $src = wp_get_attachment_image_src($post_meta['img'][0], 'large')[0];
  $user = get_userdata($post->post_author);
  $total_comments = get_comments_number($post->ID);

  return [
    'id' => $post->ID,
    'author' => $user->user_login,
    'title' => $post->post_title,
    'date' => $post->post_date,
    'src' => $src,
    'weight' => $post_meta['weight'][0],
    'age' => $post_meta['age'][0],
    'hits' => $post_meta['hits'][0],
    'total_comments' => $total_comments
  ];
}

?>