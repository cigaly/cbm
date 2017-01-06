<?php
/*
Plugin Name: Page Session Restrict
Plugin URI: http://biberius.net/pljugin
Description: Restrict number of visited pages in one session
Author: Cedomir Igaly
Author URI: http://biberius.net
Version: 0.0.7

*/

function pr_get_opt ( $option ) {
    $pr_options = get_option ( 'pr_options' );
    // clean up PHP warning for in_array() later when they have not been saved
//    if ( $option == 'posts' || $option == 'pages' ) {
//        if ( !is_array($pr_options[$option]) ) {
//            $pr_options[$option] = array();
//        }
//    }
    return $pr_options[$option];
}

add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');
add_filter ( 'the_content' , 'pr_page_restrict' , 50 );

function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function myEndSession() {
    session_destroy ();
}


/*

Now the session is yours to use as you wish in your code

To save some data into the session

$_SESSION['myKey'] = "Some data I need later";

And to get that data out at a later time

if(isset($_SESSION['myKey'])) {
    $value = $_SESSION['myKey'];
} else {
    $value = '';
}

*/

function pr_page_restrict ( $pr_page_content ) {
    global $post;
    if(isset($_SESSION['myKey'])) {
        $value = $_SESSION['myKey'];
    } else {
        $value = 0;
    }
    $_SESSION['myKey'] = ++$value;
    if ($value > 99999) {
        return "Verboten!";
    } else {
        return
/*
"<!-- " . print_r($post, true) . " -->"
.
*/
$pr_page_content
;
    }
}
