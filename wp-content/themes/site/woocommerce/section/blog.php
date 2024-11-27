<?php



$posts = get_posts(array(

  'posts_per_page' => 8

));



if( count($posts) ):

  $cat_link = '#';

  $categories = get_the_category($posts[0]->ID); 

  //var_dump($categories);

  if ( ! empty( $categories ) ) {

    $cat_link = esc_url( get_term_link($categories[0]->term_id ) );   

  }

?>

<section class="blog section"> <!--my-5 -->
  <div class="section-bg">
    <h2 class="section-header"> 

      <span>Blog tư vấn</span>

    </h2>

    <div class="col-12">

      <div class="row">

        <?php foreach( $posts as $p ): ?>

        <div class="col-6 col-lg-3">     
          <!-- mt-3 -->
          <a class="card card-blog rounded h-100" href="<?php echo get_permalink($p->ID);?>" title="<?php echo $p->post_title;?>">

            <div class="card-img-top" style="background-image: url('<?php echo get_the_post_thumbnail_url($p, 'medium')?>')"></div>

            <div class="card-body">

              <b><?php echo $p->post_title;?></b>

            </div>

          </a>

        </div>

        <?php endforeach;?>

      </div>

    </div>

    <div class="text-center mt-3">

      <a href="<?php echo $cat_link;?>" class="btn btn-lg py-1 px-5 fw-bold btn-primary rounded">Xem tất cả</a>

    </div>
  </div>
</section>

<?php 



endif;