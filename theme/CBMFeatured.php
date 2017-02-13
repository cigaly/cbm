<?php

class CBMFeatured {
  
  private static $capability = 'edit_others_posts';
  
  public static function init() {

    $post_types = get_post_types();
    foreach( $post_types as $post_type) {
      if ($post_type == 'post') {
        echo "<!--\nPost type:";print_r($post_type);
        add_submenu_page('edit.php', __('Featured Posts', 'featured-posts'), __('Featured Posts', 'featured-posts'), self::$capability, 'featured-posts', 'CBMFeatured::edit_featured' );
        echo "\n-->\n\n";
      }
      }

  }
  
  public static function edit_featured() {
    echo "Edit featured!";
    $meta_key = get_option( CBMAdmin::FEATURE_META_KEY, CBMAdmin::DEF_FEATURE_META_KEY ) ;
    $number_featured = get_option( CBMAdmin::NUM_FEATURED_POSTS, CBMAdmin::DEF_FEATURED_POSTS );
    foreach ($_POST as $key => $value) {
      if ($value) {
        update_post_meta((int)substr($key, 2), $meta_key, $value);
      } else {
        delete_post_meta((int)substr($key, 2), $meta_key);
      }
    }
    global $post;
    $count = 0;
    $query = CBMTheme::queryFeaturedPosts();
    ?>
    <form method="post"><table>
    <?php 
    while ( $query->have_posts() ) {
      ++$count;
      $query->the_post();
      $cat = get_the_category( $post->ID )[0];
      ?><tr>
      <td><?= $count ?></td>
      <td><?= $post->ID ?></td>
      <td><?= $post->post_date ?></td>
      <td><?= $post->post_title ?></td>
      <td><?= get_the_author() ?></td>
      <td><?= $cat->name ?></td>
      <td>
        <input class="feature_value" type="text" id="f_<?= $post->ID ?>" size="2" value="<?= get_post_meta( $post->ID, $meta_key, true ) ?>" />
        <input class="up_feature" data-id="<?= $post->ID ?>" type="image" src="<?= get_template_directory_uri() ?>/images/up.gif" />
        <input class="down_feature" data-id="<?= $post->ID ?>" type="image" src="<?= get_template_directory_uri() ?>/images/down.gif" />
      </td>
      </tr><?php  
    }
    ?><tr><td colspan="7"><hr /></td></tr><?php
    $query = CBMTheme::queryNonFeaturedPosts();
    while ( $query->have_posts() ) {
      ++$count;
      $query->the_post();
      $cat = get_the_category( $post->ID )[0];
      ?><tr>
  	  <td><?= $count ?></td>
      <td><?= $post->ID ?></td>
      <td><?= $post->post_date ?></td>
      <td><?= $post->post_title ?></td>
      <td><?= get_the_author() ?></td>
      <td><?= $cat->name ?></td>
      <td>
        <input class="feature_value" id="f_<?= $post->ID ?>" type="text" size="2" value="" />
        <input class="up_feature" data-id="<?= $post->ID ?>" type="image" src="<?= get_template_directory_uri() ?>/images/up.gif" />
        <input class="down_feature" data-id="<?= $post->ID ?>" type="image" src="<?= get_template_directory_uri() ?>/images/down.gif" />
      </td>
      </tr><?php  
    }
    ?>
	<tr><td colspan="7">
	  <input id="reorder" type="button" value="Reorder" />
	  <input type="submit" value="OK" />
	</td></tr>    
    </table>
    </form>
    <script>
      (function($) {
          var number_featured = <?= $number_featured ?>;
          $(function() {
            function add_name(el, name) {
                if (!el.attr('name')) {
                    el.attr('name', name || el.attr('id'));
                }
            }
            $('.up_feature').click(function(event) {
                var tgt = $(event.target);
                var fid = 'f_' + tgt.data('id');
                var old_value = parseInt($('#' + fid).val(), 10);
                if (isNaN(old_value)) {
                    $('.feature_value').each(function(n, el) {
                      var e = $(el);
                      if (e.attr('id') != fid) {
                        var s = e.val();
                        if (s) {
                          var v = parseInt(s, 10);
                          if (v < number_featured) {
                            e.val(v + 1);
                          } else {
                        	e.val('');
                          }
                          add_name(e);
                        }
                      }
                    });
                	$('#' + fid).val(1);
                	add_name($('#' + fid), fid);
                } else if (old_value > 1) {
                    var new_value = old_value - 1;
                    $('.feature_value').each(function(n, el) {
                        var e = $(el);
                        if (e.attr('id') != fid) {
                          var s = e.val();
                          if (s) {
                            var v = parseInt(s, 10);
                            if (v == new_value) {
                              e.val(v + 1);
                              add_name(e);
                            }
                          }
                        }
                      });
                  	$('#' + fid).val(new_value);
                  	add_name($('#' + fid), fid);
                }
                
                return false;
            });
            $('.down_feature').click(function(event) {
            	var tgt = $(event.target);
                var fid = 'f_' + tgt.data('id');
                var old_value = parseInt($('#' + fid).val(), 10);
                if (old_value < number_featured) {
                    var new_value = old_value + 1;
                    $('.feature_value').each(function(n, el) {
                        var e = $(el);
                        if (e.attr('id') != fid) {
                          var s = e.val();
                          if (s) {
                            var v = parseInt(s, 10);
                            if (v == new_value) {
                              e.val(v - 1);
                              add_name(e);
                            }
                          }
                        }
                      });
                  	$('#' + fid).val(new_value);
                  	add_name($('#' + fid), fid);
                }
                return false;
            });
          });
      })(jQuery);
    </script>
    <?php
  }

}
