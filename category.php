<?php get_header(); ?>

<!-- Main -->
<div id="page">
	<?php get_template_part( 'navigation', get_post_format() ); ?>

	<!-- Main -->
	<div id="main" class="container">
		<div class="row">
			<?php get_template_part( 'cat_content', get_post_format() ); ?>
		</div>
	</div>
	<!-- Main -->
</div>
<!-- /Main -->

<?php get_footer(); ?>
