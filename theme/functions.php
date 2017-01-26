<?php
  remove_action ( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

  require_once 'CBMTheme.php';
  add_theme_support ( 'post-thumbnails' );

  require_once 'CBMAdmin.php';
  // add the admin options page
  add_action("admin_menu", "CBMAdmin::add_theme_menu_item");

  // add the admin settings and such
  add_action("admin_init", "CBMAdmin::display_theme_panel_fields");

  add_action('pre_get_posts', 'CBMTheme::homeSelectFeaturedPosts' );
  add_action( 'add_meta_boxes', 'CBMAdmin::add_meta_box' );
