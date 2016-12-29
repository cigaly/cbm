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
<!--
	<?php
		// $x = array_merge(
		// 	CBMTheme::getTaggedPosts( 'boom1' ),
		// 	CBMTheme::getTaggedPosts( 'boom2' ),
		// 	CBMTheme::getTaggedPosts( 'boom3' )
		// );
		// // print_r($x);
		// foreach ($x as $p)
		// {
		// 	echo $p->ID;
		// 	if (has_post_thumbnail( $p )) {
		// 		echo ' ' . get_the_post_thumbnail( $p );
		// 	} else {
		// 		echo ' No thumb';
		// 	}
		// 	echo "\n";
		// }
	?>
-->