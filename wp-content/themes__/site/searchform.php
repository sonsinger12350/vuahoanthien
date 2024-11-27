<?php
/**
 * Template for displaying search forms in Twenty Seventeen
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>

<?php $unique_id = esc_attr( uniqid( 'search-form-' ) ); ?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<button type="submit" class="search-submit"></button>
	<input type="search" id="<?php echo $unique_id; ?>" class="search-field" placeholder="Tìm kiếm sản phẩm" value="<?php echo get_search_query(); ?>" name="s" />
	<input type="hidden" name="post_type" value="product" />
</form>