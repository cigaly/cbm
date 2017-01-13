<?php get_header(); ?>

<!-- Main -->
<main id="page">
	<!-- Main -->
	<div id="main" class="container">
		<div class="row">
			<?php get_template_part( 'content', get_post_format() ); ?>
		</div>
	</div>
	<!-- Main -->
</main>
<!-- /Main -->

<?php get_footer(); ?>
