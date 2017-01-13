<?php

class CBMAdmin {
  
  const NUM_LATEST_POSTS = 'number_latest_posts';
  
  const DEF_LATEST_POSTS = 2;
  
  public static function admin_add_page() {
    add_options_page('CBM Theme Page', 'CBM Theme Menu', 'manage_options', 'cbm_theme', 'CBMAdmin::options_page');
  }
  
  public static function options_page() {
    ?>
      <div>
          <h2>My custom plugin</h2>
          Options relating to the Custom Plugin.
          <form action="options.php" method="post">
              <?php settings_fields('plugin_options'); ?>
              <?php do_settings_sections('cbm_theme'); ?>
              <input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
          </form>
      </div>
      <?php
  }
  
  public static function admin_init(){
    /*
     * register_setting( $option_group, $option_name, $sanitize_callback );
     *
     *  $option_group
     *      (string) (required) A settings group name. Must exist prior to the register_setting call. This must match the group name in settings_fields()
     *          Default: None
     *
     *  $option_name
     *      (string) (required) The name of an option to sanitize and save.
     *          Default: None
     *
     *  $sanitize_callback
     *      (callback) (optional) A callback function that sanitizes the option's value.
     *          Default: None
     */
    register_setting( 'plugin_options', 'plugin_options', 'CBMAdmin::options_validate' );
    /*
     * add_settings_section( $id, $title, $callback, $page );
     *
     * $id
     *     (string) (required) String for use in the 'id' attribute of tags.
     *         Default: None
     *
     * $title
     *     (string) (required) Title of the section.
     *         Default: None
     *
     * $callback
     *     (string) (required) Function that fills the section with the desired content. The function should echo its output.
     *         Default: None
     *
     * $page
     *     (string) (required) The menu page on which to display this section. Should match $menu_slug from Function Reference/add theme page
     *         Default: None
     */
    add_settings_section('plugin_main', 'Main Settings', 'CBMAdmin::section_text', 'cbm_theme');
    /*
     * add_settings_field( $id, $title, $callback, $page, $section, $args );
     *
     * $id
     *     (string) (required) String for use in the 'id' attribute of tags.
     *         Default: None
     *
     * $title
     *     (string) (required) Title of the field.
     *         Default: None
     *
     * $callback
     *     (string) (required) Function that fills the field with the desired inputs as part of the larger form. Passed a single argument, the $args array. Name and id of the input should match the $id given to this function. The function should echo its output.
     *         Default: None
     *
     * $page
     *     (string) (required) The menu page on which to display this field. Should match $menu_slug from add_theme_page() or from do_settings_sections().
     *         Default: None
     *
     * $section
     *     (string) (optional) The section of the settings page in which to show the box (default or a section you added with add_settings_section(), look at the page in the source to see what the existing ones are.)
     *         Default: default
     *
     * $args
     *     (array) (optional) Additional arguments that are passed to the $callback function. The 'label_for' key/value pair can be used to format the field title like so: <label for="value">$title</label>.
     *         Default: array()
     */
    add_settings_field('plugin_text_string', 'Plugin Text Input', 'CBMAdmin::setting_string', 'cbm_theme', 'plugin_main');
    //add_settings_field('plugin_' . self::NUM_LATEST_POSTS, 'Number of latest posts displayed', 'CBMAdmin::setting_number_latest_posts', 'cbm_theme', 'plugin_main');
  }


  // validate our options
  public static function options_validate($input) {
    $options = get_option('plugin_options');
    $options['text_string'] = trim($input['text_string']);
    if(!preg_match('/^[a-z0-9]{32}$/i', $options['text_string'])) {
      $options['text_string'] = '';
    }
    return $options;
  }

  public static function section_text() {
    echo '<p>Main description of this section here.</p>';
  }
  

  public static function setting_string() {
    $options = get_option('plugin_options');
    echo "<input id='plugin_text_string' name='plugin_options[text_string]' size='40' type='text' value='{$options['text_string']}' />";
  }

  public static function setting_number_latest_posts() {
    $options = get_option('plugin_options');
    print_r($options);
    $key = self::NUM_LATEST_POSTS;
    $value = array_key_exists ( self::NUM_LATEST_POSTS , $options ) ? $options[self::NUM_LATEST_POSTS] : self::DEF_LATEST_POSTS;
    echo "<input id='$key' name='plugin_options[$key]' size='40' type='text' value='{$value}' />";
  }
  
  
}