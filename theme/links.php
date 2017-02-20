<?php /* Template Name: Links */ ?>

<?php get_header(); ?>

<!-- Main -->
<main id="page">
	<!-- Main -->
	<div id="main" class="container">
	  <?php if (have_posts()) : while (have_posts()) : the_post();  ?>
		<div class="row">
		  <?php the_content(); ?>
		</div>
      <?php endwhile;
        else :
          echo '<p>No content found</p>';
        endif; ?>
	</div>
	<!-- Main -->
</main>
<!-- /Main -->

<?php get_footer(); ?>


