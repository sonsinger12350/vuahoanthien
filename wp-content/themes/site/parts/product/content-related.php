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

extract(shortcode_atts(array(
    'size'     => 'medium',
    'price'    => 1,
    'desc'     => 0,
    'words'    => 4,
), (array) $site_params ));

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

// echo $product->get_price_html();

?>
<div class="item-product">
    <div class="item-thumbnail">
        <a href="<?php the_permalink() ?>">
            <?php the_post_thumbnail( $size );?>
        </a>
    </div>
    <div class="item-info">
        <div class="item-title">
            <a href="<?php the_permalink();?>"><?php 
                the_title();
            ?></a>
        </div>
        <?php 
        if( $price ) {
            // echo $product->get_price_html();
            echo site_woocommerce_get_price_html('',$product,'related');
        }
        if( $desc ) :
        ?>
        <div class="item-excerpt">
            <?php
            if( has_excerpt() )
                echo wp_trim_words( get_the_excerpt(), $words );
            else
                echo wp_trim_words( get_the_content(), $words );
            ?>
        </div>
        <?php
        endif;
        ?>
    </div>
</div>