<?php
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});
?>

<!DOCTYPE HTML>
<?php
	/*
	Ex Machina by TEMPLATED
    templated.co @templatedco
    Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
	*/
?>
<html>
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
</html>
