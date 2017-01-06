<?php
    add_theme_support( 'post-thumbnails' );
    
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
