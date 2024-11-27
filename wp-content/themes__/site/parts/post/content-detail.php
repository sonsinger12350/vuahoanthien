<?php
/**
 * The template used for displaying single content
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post_news_item clearfix'); ?>>
	<?php 
	if( has_post_thumbnail() ){		
		echo '<div class="post_thumb">';
			the_post_thumbnail('full');
		echo '</div>';
	}
	?>
	<div class="post_info">
		<?php 
		the_title('<h1 class="post_title">','</h1>');
		// echo '<div class="post_author_date">';
		// 	echo '<span class="post_author">';
		// 		echo 'Đăng bởi: ' . esc_html( get_the_author() );
		// 	echo '</span>';
		// 	echo '<span class="spacer">|</span>';
		// 	echo '<span class="post_date">';
		// 		echo 'Đăng vào ngày:  ' . esc_html( get_the_date('d/m/Y') );
		// 	echo '</span>';
		// echo '</div>';
		echo '<div class="post_excerpt">';
			the_content();
		echo '</div>';
		?>
	</div>
</article><!-- #post-## -->