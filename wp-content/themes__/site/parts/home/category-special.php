<?php



$page_on_front = (int) get_option( 'page_on_front', 0 );

$viehome_mall_title = get_field('viehome_mall_title', $page_on_front);

$viehome_mall = (array) get_field('viehome_mall', $page_on_front);



$hot_sale = (array) get_field('hot_sale', $page_on_front);

$category = (array) $hot_sale['category'];



?>

<div class="container">
  <div class="section category-special">

    <div class="section-bg">

      <h2 class="section-header">

        <span><?php echo $viehome_mall_title; ?></span>

      </h2>

      <div class="row mx-0">

        <?php foreach( $viehome_mall as $i => $item ) : //$item['link'];
                $link_cate = get_term_link($category['term_id']);
                if($item['link']) {
                    $link_cate = $item['link'];
                }
        ?>

          <a href="<?php echo $link_cate; ?>" class="col-6 col-md-3 category-special-item text-hover-primary text-decoration-none" title="<?php echo $item['title'];?>">

            <img class="img-fluid" src="<?php echo wp_get_attachment_image_url( $item['image'], 'medium' );?>" />

            <p class="title"><?php echo $item['title'];?></p>

          </a>

          <?php endforeach;?>

      </div>
    </div>
  </div>

</div>