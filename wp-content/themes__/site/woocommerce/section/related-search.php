<?php

$tags = array();

$limit = 6;

$cats = get_the_terms( get_the_ID(), 'product_cat' );

if( count($cats)>0 ) 
{
  $term = site_wc_get_terms_to_root( $cats[0], 1 );
  if( isset($term->name) ) {
    $tags = site_the_category_terms( $term->name, 'product_tag', $limit );
  }
}
else 
{
  $tags = get_the_terms( get_the_ID(), 'product_tag' );
}

?><div class="container mb-5"> 
  <h2 class="section-header text-center border-0">
    <span>Mọi người cũng tìm kiếm</span>
  </h2>
  <div class="row">
    <div class="related-search mb-4">
      <?php
        $i = 1;
        foreach( $tags as $tag ):
      ?>
      <a href="<?php echo get_term_link($tag->term_id);?>" class="btn btn-outline-secondary rounded-pill bg-lights">
        <i class="bi bi-search text-primary"></i>
        <span><?php echo $tag->name;?></span>
      </a>
      <?php
          if( $i++ == 3 ) 
            echo '</div><div class="related-search">';
          // if( $i>6 )
          //   break;
        endforeach;
      ?>
    </div>
  </div>
</div>