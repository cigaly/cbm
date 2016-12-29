<section class="sidebar">
	<?php
	foreach (CBMTheme::getLastPosts() as $item) {
		echo CBMTheme::sidebarPost($item);
	}
	?>
</section>
