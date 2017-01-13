<div class="9u">
  <?php
  if (have_posts()) {
    global $post;
    the_post();
    CBMTheme::displaySinglePost( $post );
  } else {
    /* TODO : What if no artcles? */
  }
  ?>
</div>
<div class="3u">
	<?php get_template_part( 'rightside', get_post_format() ); ?>
</div>
