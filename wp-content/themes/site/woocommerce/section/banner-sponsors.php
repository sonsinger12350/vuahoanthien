<?php

$page_on_front = (int) get_option( 'page_on_front', 0 );
$slider = (array) get_field('slider', $page_on_front);

?>
<div class="banner-sponsors">
  <div id="sponsors1" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <?php foreach( $slider as $i => $item ) : 
        
        $image = wp_get_attachment_image_url( $item['image'], 'full' );
        // $image_sp = wp_get_attachment_image_url( $item['image_sp'], 'full'  );
        $image_sp = $image;
        
      ?>
      <a href="<?php echo $item['link'];?>" class="carousel-item <?php echo $i == 0 ? 'active' : '';?>">
        <picture>
          <source media="(min-width: 640px)" srcset="<?php echo $image;?>">
          <source media="(max-width: 640px)" srcset="<?php echo $image_sp;?>">
          <img src="<?php echo $image;?>" class="img-fluid w-100" alt="" />
        </picture>
      </a>
      <?php endforeach;?>
    </div>
  </div>
</div>