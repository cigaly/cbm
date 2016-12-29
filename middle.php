	<?php
		$x = array_merge(
			CBMTheme::getTaggedPosts( 'boom1' ),
			CBMTheme::getTaggedPosts( 'boom2' ),
			CBMTheme::getTaggedPosts( 'boom3' )
		);
		// // print_r($x);
		foreach ($x as $p)
		{
			echo CBMTheme::displayMiddlePost( $p );
		// 	echo $p->ID;
		// 	if (has_post_thumbnail( $p )) {
		// 		echo ' ' . get_the_post_thumbnail( $p );
		// 	} else {
		// 		echo ' No thumb';
		// 	}
		// 	echo "\n";
		}
	?>

<!--<section>
	<header>
		<img src="<?php bloginfo('template_directory');?>/images/clanci/dummy.jpg" alt="image">
		<span class="banking">Banking</span>
		<h2>The quick brown fox</h2>
	</header>
	<p><strong>Sed etiam vestibulum velit, euismod lacinia quam nisl id lorem.</strong> Quisque erat. Vestibulum ćčšđž ĆČŠĐŽ pellentesque, justo mollis pretium suscipit, justo nulla blandit libero, in blandit augue justo quis nisl. Fusce mattis viverra elit. Fusce quis tortor. Consectetuer adipiscing elit. Nam pede erat, porta eu, lobortis eget lorem ipsum dolor. Donec placerat odio vel elit. Nullam ante orci, pellentesque eget, tempus quis, ultrices in, est. Curabitur sit amet nulla. Nam in massa. Sed vel tellus. Curabitur sem urna, consequat. Sed etiam vestibulum velit, euismod lacinia quam nisl id lorem. Quisque erat.</p>
	
	<p><span class="articledate">12.02.2016</span> <a href="#" class="button">Read More </a></p>	
</section>
<section>
	<header>
		<span class="industry">Industry</span>
		<h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h2>
	</header>
	<p><strong>Sed etiam vestibulum velit, euismod lacinia quam nisl id lorem.</strong> Quisque erat. Vestibulum pellentesque, justo mollis pretium suscipit, justo nulla blandit libero, in blandit augue justo quis nisl. Fusce mattis viverra elit. Fusce quis tortor. Consectetuer adipiscing elit. Nam pede erat, porta eu, lobortis eget lorem ipsum dolor. Donec placerat odio vel elit. Nullam ante orci, pellentesque eget, tempus quis, ultrices in, est. Curabitur sit amet nulla. Nam in massa. Sed vel tellus. Curabitur sem urna, consequat. Sed etiam vestibulum velit, euismod lacinia quam nisl id lorem. Quisque erat.</p>
	
	<p><span class="articledate">12.02.2016</span> <a href="#" class="button">Read More </a></p>	
</section>
<section>
	<header>
		<img src="<?php bloginfo('template_directory');?>/images/clanci/dummy2.jpg" alt="image">
		<span class="insurance">Insurance</span>
		<h2>Jumps over the lazy nasty dog</h2>
	</header>
	<p><strong>Sed etiam vestibulum velit, euismod lacinia quam nisl id lorem.</strong> Quisque erat. Vestibulum pellentesque, justo mollis pretium suscipit, justo nulla blandit libero, in blandit augue justo quis nisl. Fusce mattis viverra elit. Fusce quis tortor. Consectetuer adipiscing elit. Nam pede erat, porta eu, lobortis eget lorem ipsum dolor. Donec placerat odio vel elit. Nullam ante orci, pellentesque eget, tempus quis, ultrices in, est. Curabitur sit amet nulla. Nam in massa. Sed vel tellus. Curabitur sem urna, consequat. Sed etiam vestibulum velit, euismod lacinia quam nisl id lorem. Quisque erat.</p>
	
	<p><span class="articledate">12.02.2016</span> <a href="#" class="button">Read More </a></p>	
</section>-->
