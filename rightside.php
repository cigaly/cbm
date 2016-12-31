<section class="sidebar">
	<header>
		<h2><span class="padbott10">LATEST NEWS</span></h2>
	</header>
	<?php
		CBMTheme::displayLatestPosts();
		// foreach (CBMTheme::getLatestPosts() as $post)
		// {
		// 	setup_postdata( $post );
		// 	CBMTheme::displayLatestPost(  );
		// 	wp_reset_postdata();
		// }
	?>
</section>
<section class="sidebar buletin"><?php get_template_part( 'sidebar', 'bulletin' ); ?></section>
