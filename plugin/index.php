<?php
/*
Plugin Name: Page Session Restrict
Plugin URI: http://biberius.net/pljugin
Description: Restrict number of visited pages in one session
Author: Cedomir Igaly
Author URI: http://biberius.net
Version: 0.0.7

*/

include_once('includes/PostAccessRestriction.php');

register_activation_hook( __FILE__, array( 'PostAccessRestriction', 'install_db' ) );

add_action( 'init', 'PostAccessRestriction::start_session', 1 );
add_action( 'wp_logout', 'PostAccessRestriction::end_session' );
add_action( 'wp_login', 'PostAccessRestriction::end_session' );
add_filter( 'the_content' , 'PostAccessRestriction::page_restrict' , 50 );
