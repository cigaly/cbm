<?php

class CBMAdmin {
  
  const NUM_LATEST_POSTS = 'CBM_number_latest_posts';
  
  const DEF_LATEST_POSTS = 3;
  
  const FEATURE_META_KEY = 'CBM_feature_meta_key';
  
  const DEF_FEATURE_META_KEY = 'feature';
  
  const NUM_FEATURED_POSTS = 'CBM_number_featured_posts';
  
  const DEF_FEATURED_POSTS = 3;
  
  // validate our options
  public static function options_validate($input) {
    $options = get_option('plugin_main');
    $options['plugin_text_string'] = trim($input['plugin_text_string']);
    if(!preg_match('/^[a-z0-9]{32}$/i', $options['plugin_text_string'])) {
      $options['plugin_text_string'] = '';
    }
    return $options;
  }

  public static function section_text() {
    echo '<p>Main description of this section here.</p>';
  }
  
  private static function setting_input( $key, $default = false ) {
    ?>
    <input type="text" name="<?= $key ?>" id="<?= $key ?>" value="<?php echo get_option( $key, $default ); ?>" />
    <?php
  }
  

  public static function setting_feature_meta_key() {
    self::setting_input( self::FEATURE_META_KEY, self::DEF_FEATURE_META_KEY );
  }

  public static function setting_number_latest_posts() {
    self::setting_input( self::NUM_LATEST_POSTS, self::DEF_LATEST_POSTS );
  }

  public static function setting_number_fatured_posts() {
    self::setting_input( self::NUM_FEATURED_POSTS, self::DEF_FEATURED_POSTS );
  }
  
  public static function options_page() {
    ?>
    <div class="wrap">
  	  <h2>My custom plugin</h2>
  	  Options relating to the Custom Plugin.
  	  <form method="post" action="options.php">
  	    <?php
  	      settings_fields("plugin_main");
  	      do_settings_sections("cbm_theme");
  	      submit_button();
  	    ?>
  	  </form>
  	</div>
    <?php
  }
  
  public static function add_theme_menu_item() {
    add_menu_page ( "CBM Theme Menu Page", "CBM Theme Menu", "manage_options", "theme-panel", "CBMAdmin::options_page", null, 99 );
  }
  
  public static function add_theme_page() {
    add_theme_page( "CBM Theme Page", "CBM Theme Page", "manage_options", "theme-panel", "CBMAdmin::options_page" );
  }
  
  public static function add_options_page() {
    add_options_page( "CBM Theme Options Page", "CBM Theme Options ", "manage_options", "theme-panel", "CBMAdmin::options_page" );
  }
  
  public static function add_meta_box() {
    /*
     *
     * $screen
     *     (string|array|WP_Screen) (Optional) The screen or screens on which to show the box (such as a post type, 'link', or 'comment'). Accepts a single screen ID, WP_Screen object, or array of screen IDs. Default is the current screen.
     *     Default value: null
     * $context
     *     (string) (Optional) The context within the screen where the boxes should display. Available contexts vary from screen to screen. Post edit screen contexts include 'normal', 'side', and 'advanced'. Comments screen contexts include 'normal' and 'side'. Menus meta boxes (accordion sections) all use the 'side' context. Global
     *     Default value: 'advanced'
     * $priority
     *     (string) (Optional) The priority within the context where the boxes should show ('high', 'low').
     *     Default value: 'default'
     * $callback_args
     *     (array) (Optional) Data that should be set as the $args property of the box array (which is the second parameter passed to your callback).
     *     Default value: null
     */
     add_meta_box( "CBMTheme", "CBM Theme Meta Box", "CBMAdmin::posts_page" );
  }
  
  public static function posts_page( $post ) {
    echo "Hello, " . $post->ID . "!";
  }
  
  public static function display_layout_element() {
    ?>
    <input type="checkbox" name="theme_layout" value="1" <?php checked(1, get_option('theme_layout'), true); ?> />
    <?php
  }

  public static function display_theme_panel_fields() {
    add_settings_section ( 'plugin_main', 'Main Settings', 'CBMAdmin::section_text', 'cbm_theme' );
    
    add_settings_field ( self::FEATURE_META_KEY, 'Feature Meta Key', 'CBMAdmin::setting_feature_meta_key', 'cbm_theme', 'plugin_main' );
    add_settings_field ( self::NUM_FEATURED_POSTS, 'Number of fatured posts displayed', 'CBMAdmin::setting_number_fatured_posts', 'cbm_theme', 'plugin_main' );
    add_settings_field ( self::NUM_LATEST_POSTS, 'Number of latest posts displayed', 'CBMAdmin::setting_number_latest_posts', 'cbm_theme', 'plugin_main' );
    // add_settings_field("theme_layout", "Do you want the layout to be responsive?", "CBMAdmin::display_layout_element", "cbm_theme", "plugin_main");
    
    register_setting ( 'plugin_main', self::FEATURE_META_KEY /* , 'CBMAdmin::options_validate' */ );
    register_setting ( 'plugin_main', self::NUM_FEATURED_POSTS );
    register_setting ( 'plugin_main', self::NUM_LATEST_POSTS );
    // register_setting("plugin_main", "theme_layout");
  }

}