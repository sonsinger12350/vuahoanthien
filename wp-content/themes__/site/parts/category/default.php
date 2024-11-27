<?php
/**
 * The template for displaying Category pages
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
get_header();

$cat_ID 		= (int)get_query_var('cat');
$columns 		= 1;
$tp_name 		= 'news';//get_term_meta($cat_ID, 'template_part_name', $single = true);


?>
<div class="section-category category-layout category-layout-default">
	<div class="container">
		<div class="row">
			<div class="col-md-9 col-content">
				<h1 class="hide"><?php single_cat_title(); ?></h1>
				<?php
				if ( have_posts() ) : 
					// If has more than one columns 
					echo '<div class="list-posts">';
						$i = 0;
						// Start the Loop.
						while ( have_posts() ) : the_post();
							
							echo '<div class="list-posts-item list-posts-item-'.($i%$columns+1).'">';
								site_get_template_part( 'parts/post/content' );
							echo '</div>';
							
							$i++;
						endwhile;
					echo '</div>';
					
					// custom.php
					// site_category_pagination();
					
				else :
					// If no content, include the "No posts found" template.
					site_get_template_part( 'content', 'none' );

				endif;
				?>
			</div>
			<div class="col-md-3 sidebar-right">
				<?php
					site_get_template_part( 'parts/section/right');
				?>
			</div>
		</div>
	</div>	
</div>
<?php

site_get_template_part( 'parts/section/related');

get_footer(); 