<?php

$cat = get_queried_object();

$tax = $cat->taxonomy;

$terms = get_terms(array(
  // 'parent' => $cat->term_id,
  'number' => 7,
  'taxonomy' => $tax,
  'hide_empty' => false,
));

?>
<div class="d-flex mb-3 header-categories">
  <?php foreach( $terms as $term ):?>
  <div class="card border-0 me-2">
    <a href="<?php echo get_term_link($term->term_id);?>" class="p-2 text-decoration-none">
      <img class="w-100" src="<?php site_the_assets();?>images/categories/bathroom-faucets.webp" />
    </a>
    <div class="card-body text-center">
      <a href="<?php echo get_term_link($term->term_id);?>" class="text-decoration-none"><?php echo $term->name;?></a>
    </div>
  </div>
  <?php endforeach;?>
</div>