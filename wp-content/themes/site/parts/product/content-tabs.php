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

$youtube_id = site_get_youtube_id( get_field('youtube_url') );

?>
<section class="section section-tabs single-product-tabs clearfix">
    <div class="col-tabs-menu hidden-sp">
        <span class="active">Mô tả sản phẩm</span>
        <?php if( $youtube_id != '' ) :?>
        <span>Video</span>
        <?php endif;?>
        <span>Feedback</span>
    </div>
    <div class="list-tabs">
        <div class="list-tab-item tab_content">
            <h3 class="hidden-pc">Mô tả sản phẩm</h3>
            <?php the_content();?>
        </div>
        <?php if( $youtube_id != '' ) :?>
        <div class="list-tab-item tab_content">
            <h3 class="hidden-pc">Video</h3>
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/<?php echo $youtube_id;?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
        <?php endif;?>
        <div class="list-tab-item tab_content">
            <h3 class="hidden-pc">Feedback</h3>
            <div class="fb-comments" data-href="<?php the_permalink();?>" data-numposts="5" data-width="100%"></div>
        </div>
    </div>
</section>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v7.0&appId=430614264059313&autoLogAppEvents=1" nonce="adGZPkAB"></script>