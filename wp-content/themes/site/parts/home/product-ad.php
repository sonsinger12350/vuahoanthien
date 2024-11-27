<?php



$page_on_front = (int) get_option( 'page_on_front', 0 );

$slider = (array) get_field('slider', $page_on_front);



?>

<div class="container">

  <div id="productAds" class="carousel slide " data-bs-ride="carousel">
<!-- p-3 -->
    <div class="carousel-inner">

      <?php foreach( $slider as $i => $item ) :  

        

        $image = wp_get_attachment_image_url( $item['image'], 'full' );

        $image_sp = wp_get_attachment_image_url( $item['image_mobile'], 'full'  );

      //  $image_sp = $image;

      ?>

      <a href="<?php echo $item['link'];?>" class="carousel-item <?php echo $i == 0 ? 'active' : '';?>">

        <picture>
          <source media="(max-width:767px)" srcset="<?php echo $image_sp;?>">
          <source media="(min-width:768px)" srcset="<?php echo $image;?>">
          
          <img src="<?php echo $image;?>" class="w-100" alt="" />

        </picture>

      </a>

      <?php endforeach;?>

    </div>

    <!-- <button class="carousel-control-prev" type="button" data-bs-target="#productAds" data-bs-slide="prev">

      <span class="carousel-control-prev-icon" aria-hidden="true"></span>

      <span class="visually-hidden">Previous</span>

    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#productAds" data-bs-slide="next">

      <span class="carousel-control-next-icon" aria-hidden="true"></span>

      <span class="visually-hidden">Next</span>

    </button> -->



    <div class="carousel-indicators">

      <?php for( $i = 0; $i< count($slider); $i++ ):?>

      <button type="button" data-bs-target="#productAds" data-bs-slide-to="<?php echo $i;?>" class="<?php echo $i == 0 ? 'active' : '';?>"></button>

      <?php endfor;?>

    </div>

  </div>

</div>