<?php get_header(); ?>

<!-- Main -->
<div id="page">
	<!-- Main -->
	<div id="main" class="container">
		<div class="row">
			<?php get_template_part( 'single_content', get_post_format() ); ?>
		</div>
	</div>
	<!-- Main -->
</div>
<!-- /Main -->

<?php get_footer(); ?>
