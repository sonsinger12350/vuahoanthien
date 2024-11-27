<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post_news_item clearfix'); ?>>
	<?php 
	if( has_post_thumbnail() ){		
		echo '<div class="post_thumb"><a href="'.get_permalink().'">';
			the_post_thumbnail('large');
		echo '</a></div>';
	}
	?>
	<div class="post_info">
		<?php 
		the_title('<div class="post_title"><a href="'.get_permalink().'">','</a></div>');
		echo '<div class="post_author_date">';
			echo '<span class="post_author">';
				echo 'Đăng bởi: ' . esc_html( get_the_author() );
			echo '</span>';
			echo '<span class="spacer">|</span>';
			echo '<span class="post_date">';
				echo 'Đăng vào ngày:  ' . esc_html( get_the_date('d/m/Y') );
			echo '</span>';
		echo '</div>';
		echo '<div class="post_excerpt">';
			if( has_excerpt() )
				echo wp_trim_words( get_the_excerpt(), 30 );
			else
				echo wp_trim_words( get_the_content(), 30 );			
		echo '</div>';
		?>
		<div class="readmore"><a href="<?php the_permalink();?>">XEM CHI TIẾT</a></div>
	</div>	
</article><!-- #post-## -->