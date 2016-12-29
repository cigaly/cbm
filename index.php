<?php
spl_autoload_register(function ($class_name) {
    include $class_name . '.php';
});
?>

<!DOCTYPE HTML>
<!--
	Ex Machina by TEMPLATED
    templated.co @templatedco
    Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
-->
<html>
	<?php get_header(); ?>

	<?php get_template_part( 'content', get_post_format() ); ?>

	<?php get_footer(); ?>
</html>
