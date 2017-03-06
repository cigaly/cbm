<?php /* Template Name: Execute Agreement */ ?>

<?php get_header(); ?>

<main id="page">
	<div id="main" class="container">
	<?php
	print_r($_GET);
	if (key_exists('success', $_GET) && $_GET['success'] == 'true' && key_exists('token', $_GET)) {
	  execute_agreement($_GET['token']);
	}
	?>
	</div>
</main>

<?php get_footer(); ?>


