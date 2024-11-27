<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */


get_header(); 

?>
<div class="section-single-post">
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-content">
				<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();
					
					// Include the page content template.
					get_template_part( 'parts/post/content', 'detail' );

				endwhile;
				?>
			</div>
			<div class="col-md-3">
                <?php get_sidebar('news'); ?>
            </div>
		</div>
	</div>
</div>
<?php

get_footer();