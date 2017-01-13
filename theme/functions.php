<?php
  remove_action ( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

  require_once 'CBMTheme.php';
  add_theme_support ( 'post-thumbnails' );

  require_once 'CBMAdmin.php';
  // add the admin options page
  add_action ( 'admin_menu', 'CBMAdmin::admin_add_page' );

  // add the admin settings and such
  add_action ( 'admin_init', 'CBMAdmin::admin_init' );
