<?php

// $total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
// $current = get_query_var( 'paged' ) ? (int) get_query_var( 'paged' ) : 1;

// $links = paginate_links( array(
//   'prev_text' => '<span aria-hidden="true"><i class="bi bi-chevron-left"></i></span>',
//   'next_text' => '<span aria-hidden="true"><i class="bi bi-chevron-right"></i></span>',
//   'type' => 'array'
// ) );

$links = site_wc_pagi_links();

?>
<?php if( $links ) : ?>
<section class="mt-5">
  <nav class="d-flex justify-content-center" aria-label="Page navigation">
    <ul class="pagination">
      <?php foreach( $links as $text) :
        // $text = str_replace('page-numbers','page-link', $text);
      ?>
      <li class="page-item<?php // echo preg_match('/current/i', $text)?' active':'';?>"><?php echo $text;?></li>
      <?php endforeach;?>
    </ul>
  </nav>
</section>
<?php 
endif;