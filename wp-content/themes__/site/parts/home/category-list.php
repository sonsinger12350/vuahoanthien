<?php



$page_on_front = (int) get_option( 'page_on_front', 0 );

$products = (array) get_field('products', $page_on_front);



$categories = $products['categories'];



// $tax = 'product_cat';

// $terms = get_categories(array(

//   'number' => 15,

//   'taxonomy' => $tax,

//   'hide_empty' => false,

// ));



?>

<div class="container">
  <div class="section category-list">
    <div class="section-bg">

      <h2 class="section-header">

        <span>Danh mục sản phẩm</span>

      </h2>

      <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 mx-0">

        <?php foreach( $categories as $term ):

          $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );

          $src = wp_get_attachment_image_url( $thumbnail_id, 'thumbnail' );

        ?>

        <div class="col py-2">

          <a href="<?php echo get_term_link($term);?>" class="category-list-item" title="<?php echo $term->name;?>">

            <img class="img-fluid" src="<?php echo $src;?>" />

            <span><?php echo $term->name;?></span>

          </a>

        </div>

        <?php endforeach;?>

      </div>

    </div>
  </div>

</div>