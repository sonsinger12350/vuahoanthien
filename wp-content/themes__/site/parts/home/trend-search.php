<?php



$page_on_front = (int) get_option( 'page_on_front', 0 );

$categories = (array) get_field('categories_trend', $page_on_front);



if( $categories ):

  $n = count($categories);

  $m = $n/5;

?>

<div class="section trend-search-section">

  <div class="container">

    <h2 class="section-header">

      <span>Xu hướng tìm kiếm</span> 

    </h2>

    <div class="explore-more-search-trend "> <!--pb-5-->

      <div class="collapse" id="searchTrends" style="height: 100%; min-height: 100px;">

        <div class="row">
          <!-- row-cols-2 row-cols-sm-2 row-cols-md-5 -->
          

            <?php foreach( $categories as $i => $term ):?>
              <div class="cols d-flex flex-column">
                  <a class="btn bg-light btn-outline-secondary rounded-pill" href="<?php echo get_term_link($term);?>"><i class="bi bi-search text-primary"></i> <?php echo $term->name;?></a>

              </div>  
            <?php 
 
              // if( ($i+1)%$m==0 && $i<$n ) {

              //   echo '</div><div class="col d-flex flex-column">';  //mb-3

              // }

            endforeach;

            ?>


        </div>

      </div>

      <?php if( $n>10 ):?>

     <!--  <div class="explore-more-action">

        <a class="btn btn-link btn-viewmore" data-bs-toggle="collapse" href="#searchTrends" role="button"

          aria-expanded="false" aria-controls="searchTrends">

          Xem Thêm

        </a>

      </div> -->

      <?php endif;?>

    </div>

  </div>

</div>

<?php endif;