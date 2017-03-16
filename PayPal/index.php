<?php
/*
Plugin Name: PayPal plugin
Plugin URI: http://biberius.net/PayPal
Description: PayPal management plugin
Author: Cedomir Igaly
Author URI: http://biberius.net
Version: 0.0.7

*/

include_once('PayPalPlugin.php');

add_action("admin_menu", "PayPalPlugin::add_menu_item");

// // add the admin settings and such
// add_action("admin_init", "CBMAdmin::display_theme_panel_fields");

// add_action('pre_get_posts', 'CBMTheme::homeSelectFeaturedPosts' );
// add_action( 'add_meta_boxes', 'CBMAdmin::add_meta_box' );

// require_once 'CBMFeatured.php';
// add_action ( 'admin_menu', 'CBMFeatured::init' );

// register_activation_hook( __FILE__, array( 'PostAccessRestriction', 'install_db' ) );

// add_action( 'init', 'PostAccessRestriction::start_session', 1 );
// add_action( 'wp_logout', 'PostAccessRestriction::end_session' );
// add_action( 'wp_login', 'PostAccessRestriction::end_session' );
// add_filter( 'the_content' , 'PostAccessRestriction::page_restrict' , 50 );

// include_once 'pagetemplater.php';

// $pt = PageTemplater::get_instance();
