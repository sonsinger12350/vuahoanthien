<?php



$query = new WC_Product_Query();

$query->set( 'limit', 10 );

$query->set( 'sku', 'combo' );

$products = $query->get_products();



?>

<!-- <section id="upgradeYourToilet" class="row my-5"> -->
<section id="upgradeYourToilet" class="section"> <!--my-5-->
  <div class="section-bg">
    <h2 class="section-header">

      <span>Combo thiết bị vệ sinh</span>

    </h2>

    <div class=""> <!--col-12 -->

      <div class="product-list flex-nowrap flex-md-wrap"> <!--row -->

        <?php foreach( $products as $product ): 
                 //var_dump($product);
                 //$product = wc_get_product( $product->ID );  
                 site_setup_product_data( $product ); 
        ?>

                <div class="col-6 col-md-4 col-lg-2 mb-4 col-product-item">
                  <?php wc_get_template_part( 'archive/product', 'item' ); ?>
                </div>
        <?php /* ?>
        <a href="<?php echo esc_url( $product->get_permalink() ); ?>" class="col-6 col-md-3 col-lg-2 text-center" title="<?php echo $product->get_title(); ?>">

          <img class="img-fluid w-100" src="<?php echo wp_get_attachment_image_url( $product->get_image_id(), 'medium' );?>" alt="<?php echo $product->get_title(); ?>" />

          <p class="mt-1">

            <small><?php echo $product->get_title(); ?></small>

          </p>

        </a>
        <?php */ ?>
        <?php endforeach; 
          site_reset_product_data();
        ?>

      </div>

    </div>
  </div>

</section>