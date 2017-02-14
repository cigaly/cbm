<?php

class CBMFeatured {
  
  private static $capability = 'edit_others_posts';
  
  public static function init() {

    $post_types = get_post_types();
    foreach( $post_types as $post_type) {
      if ($post_type == 'post') {
        add_submenu_page('edit.php', 'Featured Posts', 'Featured Posts', self::$capability, 'featured-posts', 'CBMFeatured::edit_featured' );
      }
    }

  }
  
  public static function edit_featured() {
    $meta_key = get_option( CBMAdmin::FEATURE_META_KEY, CBMAdmin::DEF_FEATURE_META_KEY ) ;
    $number_featured = get_option( CBMAdmin::NUM_FEATURED_POSTS, CBMAdmin::DEF_FEATURED_POSTS );
    foreach ($_POST as $key => $value) {
      $post_id = (int)substr($key, 2);
      if ($value) {
        update_post_meta($post_id, $meta_key, $value);
      } else {
        delete_post_meta($post_id, $meta_key);
      }
    }
    global $post;
    $query = CBMTheme::queryFeaturedPosts();
    ?>
    <form method="post"><table class="posts">
    <thead>
    <tr>
    <th class="manage-column column-title">Title</th>
    <th class="manage-column column-author">Author</th>
    <th class="manage-column column-categories">Categories</th>
    <!-- <th class="manage-column column-tags">Tags</th> -->
    <th class="manage-column column-date">Date</th>
    <th class="manage-column">Featured</th>
    </tr>
    </thead>
    <tbody>
    <?php 
    while ( $query->have_posts() ) {
      $query->the_post();
      ?><tr class="post featured" id="post_<?= $post->ID ?>">
      <td class="column-title"><?php edit_post_link( $post->post_title ); ?></td>
      <td class="column-author"><?php the_author_posts_link(); ?></td>
      <td class="column-categories"><?php the_category(); ?></td>
<!-- <td class="column-tags">Tags?</td> -->
      <td class="column-date"><?= get_the_date() ?></td>
      <td>
        <input class="feature_value" type="text" id="f_<?= $post->ID ?>" size="2" value="<?= get_post_meta( $post->ID, $meta_key, true ) ?>" readonly="readonly" />
        <input class="up_feature" data-id="<?= $post->ID ?>" type="image" src="<?= get_template_directory_uri() ?>/images/up.gif" />
        <input class="down_feature" data-id="<?= $post->ID ?>" type="image" src="<?= get_template_directory_uri() ?>/images/down.gif" />
      </td>
      </tr><?php  
    }
    $query = CBMTheme::queryNonFeaturedPosts();
    while ( $query->have_posts() ) {
      $query->the_post();
      ?><tr class="post" id="post_<?= $post->ID ?>">
      <td class="column-title"><?php edit_post_link( $post->post_title ); ?></td>
      <td class="column-author"><?php the_author_posts_link(); ?></td>
      <td class="column-categories"><?php the_category(); ?></td>
<!-- <td class="column-tags">Tags?</td> -->
      <td class="column-date"><?= get_the_date() ?></td>
      <td>
        <input class="feature_value" id="f_<?= $post->ID ?>" type="text" size="2" value="" readonly="readonly" />
        <input class="up_feature" data-id="<?= $post->ID ?>" type="image" src="<?= get_template_directory_uri() ?>/images/up.gif" />
        <input class="down_feature" data-id="<?= $post->ID ?>" type="image" src="<?= get_template_directory_uri() ?>/images/down.gif" />
      </td>
      </tr><?php  
    }
    ?>
	</tbody><tfoot><tr><td colspan="6">
	  <input type="submit" value="OK" />
	</td></tr></tfoot>
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
                var id = tgt.data('id');
                var fid = 'f_' + id;
                var old_value = parseInt($('#' + fid).val(), 10);
                var row = $('#post_' + id);
                if (isNaN(old_value)) {
                  $('table.posts tbody tr.featured').each(function(n, el) {
                    var inp = $(el).find('input.feature_value');
                    var v = parseInt(inp.val(), 10);
                    if (v < number_featured) {
                      inp.val(v + 1);
                    } else {
                      inp.val('');
                      $(el).removeClass('featured');
                    }
                    add_name(inp);
                  });
                  row.insertBefore($('table.posts tbody tr.featured:first'));
                  row.addClass('featured');
                  $('#' + fid).val(1);
                  add_name($('#' + fid), fid);
                } else if (old_value > 1) {
                  var new_value = old_value - 1;
			      var before;
                  $('table.posts tbody tr.featured').each(function(n, el) {
                    var inp = $(el).find('input.feature_value');
                    var v = parseInt(inp.val(), 10);
                    if (v == new_value) {
                      if (typeof before == 'undefined') {
                        before = el;
                      }
                      inp.val(v + 1);
                      add_name(inp);
                    }
                  });
                  if (typeof before != 'undefined') {
                	row.insertBefore(before);
                  }
              	  $('#' + fid).val(new_value);
                  add_name($('#' + fid), fid);
                }
                
                return false;
            });
            $('.down_feature').click(function(event) {
            	var tgt = $(event.target);
            	var id = tgt.data('id');
                var fid = 'f_' + id;
                var row = $('#post_' + id);
                var old_value = parseInt($('#' + fid).val(), 10);
                if (old_value < number_featured) {
                  var new_value = old_value + 1;
                  var after;
                  $('table.posts tbody tr.featured').each(function(n, el) {
                    var inp = $(el).find('input.feature_value');
                    var v = parseInt(inp.val(), 10);
                    if (v == new_value) {
                      if (typeof after == 'undefined') {
                        after = el;
                      }
                      inp.val(v - 1);
                      add_name(inp);
                    }
                  });
                  if (typeof after != 'undefined') {
                    row.insertAfter(after);
                  }
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
