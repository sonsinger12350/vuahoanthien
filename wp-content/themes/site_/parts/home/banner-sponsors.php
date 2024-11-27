<?php

$page_on_front = (int) get_option( 'page_on_front', 0 );
$products = (array) get_field('products', $page_on_front);

?>
<div class="container">
  <div class="banner-sponsors">
    <div id="sponsors1" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <?php echo $products['description']?>
      </div>
    </div>
  </div>
</div>