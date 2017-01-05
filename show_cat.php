<?php
	while (have_posts())
	{
		the_post();
		echo CBMTheme::displayMiddlePost( $post );
	}
?>
