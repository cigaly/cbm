<?php
	// $x = array_merge(
	// 	CBMTheme::getTaggedPosts( 'boom1' ),
	// 	CBMTheme::getTaggedPosts( 'boom2' ),
	// 	CBMTheme::getTaggedPosts( 'boom3' )
	// );
	// foreach ($x as $p) 
	// {
	// 	CBMTheme::displayMiddlePost( $p );
	// }

	foreach ( array(  'boom1', 'boom2', 'boom3' ) as $tag )
	{
		foreach ( CBMTheme::getTaggedPosts( $tag ) as $p )
		{
			$query = CBMTheme::queryTaggedPosts( $tag );
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					CBMTheme::displayMiddlePost(  );
				} // end while
			} // end if
			$query->reset_postdata();
		}
	}
?>
