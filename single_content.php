<?php
	global $post;
	if (have_posts()) {
		while (have_posts()) {
			the_post();
        	$slug = get_the_category( $post->ID )[0]->slug;
?>
			<div class="9u"><?php CBMTheme::displaySinglePost( $post ); ?></div>

			<div class="3u">
				<section class="sidebar">
					<blockquote class="color<?= $slug ?>">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus ipsum velit, ullamcorper sollicitudin</blockquote>
				</section>
			</div>
<?php
		}
	}
?>
