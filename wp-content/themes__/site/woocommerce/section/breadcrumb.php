<?php

extract(shortcode_atts(array(
  'breadcrumbs' => array(),
  'title' => get_the_title(),
), (array) $args ));


?>
<nav aria-label="breadcrumb" class="py-3">
  <ol class="breadcrumb mb-0">
    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
    <?php foreach( $breadcrumbs as $item ):?>
    <li class="breadcrumb-item"><a href="<?php echo $item['link'];?>"><?php echo $item['name'];?></a></li>
    <?php endforeach;?>
    <?php if( $title ):?>
    <li class="breadcrumb-item"><?php echo $title;?></li>
    <?php endif;?>
  </ol>
</nav>