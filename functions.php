<?php
/*
 * Remove all native endpoints
 * 
 * Used to remove access to native wordpress api. 
 * Disabled to give freedom to manage internal pages.
 */
// remove_action('rest_api_init', 'create_initial_rest_routes', 99);

/*
 * Remove access to api responsible for general 
 * or specific user data
 *  
 * Not required if previous function is enabled
 */
// remove endpoints of the users
add_filter('rest_endpoints', function ($endpoints) {
  unset($endpoints['/wp/v2/users']);
  unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);

  return $endpoints;
});


/*
 * Remove admin bar for all users
*/
add_filter( 'show_admin_bar', function () {
  return false;
});


/*
 * Import methods to api manage 
 */

// Add main directory to the one variable
$dirBase = get_template_directory();

// Require methods for users manage
require_once($dirBase . '/endpoints/user_get.php');
require_once($dirBase . '/endpoints/user_post.php');

// Require methods for posts manage
require_once($dirBase . '/endpoints/photo_get.php');
require_once($dirBase . '/endpoints/photo_get_all.php');
require_once($dirBase . '/endpoints/photo_post.php');
require_once($dirBase . '/endpoints/photo_delete.php');

// Require methods for comments of posts manage
require_once($dirBase . '/endpoints/comment_get.php');
require_once($dirBase . '/endpoints/comment_post.php');

require_once($dirBase . '/endpoints/password.php');


/*
 * Setting images in wide size
 */
update_option( 'large_size_w', 1000);
update_option( 'large_size_h', 1000);
update_option( 'large_crop', 1);


/* 
 * Change url path of api
 */
 function pfDev__change_api() {
  return 'json';
}
add_filter('rest_url_prefix', 'pfDev__change_api');


/* 
 * Expire access token 24h after
 */
 function pfDev__expire_token() {
  return time() + (60 * 60 * 24);
}
add_action('jwt_auto_expire', 'pfDev__expire_token');
?> 