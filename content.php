<!-- Main -->
<div id="page">
	<?php get_template_part( 'navigation', get_post_format() ); ?>
		
	<!-- Main -->
	<div id="main" class="container">
		<div class="row">
			<div class="3u">
				<?php get_template_part( 'leftside', get_post_format() ); ?>
			</div>
			<div class="6u">
				<?php get_template_part( 'middle', get_post_format() ); ?>
			</div>

			<div class="3u">
				<?php get_template_part( 'rightside', get_post_format() ); ?>
			</div>
		</div>
	</div>
	<!-- Main -->

</div>
<!-- /Main -->
