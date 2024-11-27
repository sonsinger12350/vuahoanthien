<?php

$tax = 'product_tag';

$page_on_front = (int) get_option( 'page_on_front', 0 );
$terms = get_field('categories_top', $page_on_front);

if( $terms == null || count($terms) == 0 ) {
  $terms = get_categories(array(
    'limit' => 10,
    'taxonomy' => $tax,
    'hide_empty' => false,
  ));  
}

?>
<div class="section">
  <div class="container">
    <h2 class="section-header">
      <span>Tìm kiếm hàng đầu</span>
    </h2>
    <?php if( $terms ):?>
    <div class="slide-multiple" id="homeCategory">
      <?php foreach( $terms as $term ):  
        $src = wp_get_attachment_image_url( get_field('image', $tax .'_'. $term->term_id), 'medium' );        
      ?>
      <!-- <a href="<?php //echo get_term_link($term) ;?>" class="px-3 text-center"> -->
       <a href="<?php echo str_replace('product-tag', 'product-category', get_term_link($term)); ?>" class="px-3 text-center">
        <?php if( $src!='' ):?>
        <img class="img-fluid w-100" src="<?php echo $src;?>" />
        <?php endif;?>
        <p class="mb-0 mt-1"><?php echo $term->name;?></p>
      </a>
      <?php endforeach;?>
    </div>
    <?php endif;?>
  </div>
</div>