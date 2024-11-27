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

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

?>
<div class="single-product-form-contact" style="display: none;">
<?php 
	echo do_shortcode('[contact-form-7 id="2028" title="Contact form 1"]');
?>
</div>
<?php
	if( get_current_user_id()>0 ) {
		echo do_shortcode('[yith_wcwl_add_to_wishlist]');
	}
?>
<div class="single-product-form-contact_footer">
	<img src="<?php site_the_assets('img/icon-ship.png');?>" alt="">
	<span>
		GIAO HÀNG NHANH<br/>
		TRONG NGÀY
	</span>
</div>