<?php
	$x = array_merge(
		CBMTheme::getTaggedPosts( 'boom1' ),
		CBMTheme::getTaggedPosts( 'boom2' ),
		CBMTheme::getTaggedPosts( 'boom3' )
	);
	foreach ($x as $p)
	{
		echo CBMTheme::displayMiddlePost( $p );
	}
?>
