<?php

// add_filter( 'pre_option_comments_per_page', function(){ return 2; } );

$product = wc_get_product();
$post_id = $product->get_id();

$paged = get_query_var( 'cpage' ) ? get_query_var( 'cpage' ) : 1;

$per_page = get_option('comments_per_page');

$main_args = $args = array(
  'number'  => $per_page,
  'status'  => 'approve',
  'type'    => 'review',
  'post_id' => $product->get_id(),
  'paged'   => $paged,
  'parent'  => 0,
  'order'   => 'DESC'
);

$csort = sanitize_text_field( isset($_GET['csort']) ? $_GET['csort'] : '' );
if( $csort!='' ) {
  if( $csort == 'oldest' ) {
    $args['order'] = 'ASC';
  } else if( preg_match('/rate/i', $csort ) ) {
    $args['orderby']  = 'meta_value_num';
    $args['meta_key'] = 'rating';
    if( $csort == 'lowestRate' ) {
      $args['order'] = 'ASC';
    }
  } else if( $csort == 'mostHelpful' ) {
    $args['orderby']  = 'meta_value_num';
    $args['meta_key'] = 'suggest';
  } else if( $csort == 'photo' ) {
    $args['orderby']  = 'meta_value';
    $args['meta_key'] = 'images';
  }
}

$cstars = [];
for( $i=5; $i>0; $i-- ) {
  $key = 'cfilter_star_' . $i;
  $value  = sanitize_text_field( isset($_GET[$key]) ? $_GET[$key] : '' );
  if( $value!='' ) {
    $cstars[] = $i;
  }  
}
if( count($cstars)>0 ) {
  $meta = array(
    'key' => 'rating',
    'compare' => 'IN',
    'value' => $cstars,
  );

  if( isset($args['meta_query']) ) {
    $args['meta_query'][] = $meta;
  } else {
    $args['meta_query'] = [$meta];
  }
}

$csearch    = sanitize_text_field( isset($_GET['csearch']) ? $_GET['csearch'] : '' );
if( $csearch!='' ) {
  $args['search'] = $csearch;
}

$comments = get_comments($args);

$args['count'] = true;
$args['paged'] = 1;
$total = (int) get_comments($args);

$commenter = wp_get_current_commenter();
$comment_author = '';
if( isset($commenter['comment_author']) ) {
  $comment_author = $commenter['comment_author'];
}

$max_page = intval( $total/$per_page );
if( $total % $per_page > 0 ) {
  $max_page++;
}

$all_images = [];
foreach( $comments as $i => $comment ){
  $images = explode(',', get_comment_meta( $comment->comment_ID, 'images', true ) );
  $comment->images = $images[0] != '' ? $images : [];

  if( count($comment->images)>0 ) {
    $all_images = array_merge($all_images, $comment->images);
  }
}

$suggest_count = 0;

if( $total>0 ) {
  $suggest_count = get_comments( array_merge( $main_args, array(
    'count' => true,
    'paged' => 1,
    'meta_query' => array(
      array(
        'key' => 'suggest',
        'compare' => '=',
        'value' => 1
      )
    )
  ) ) );
}

// Check User
$user = wp_get_current_user();
if($user->ID>0) { 
    $user_name = ucwords($user->display_name);
}

// Initialize an array to keep track of the count for each star rating.
$star_counts = array(
    '5' => 0,
    '4' => 0,
    '3' => 0,
    '2' => 0,
    '1' => 0,
);

// Loop through the comments and count them by star rating.
foreach ($comments as $comment) {
    $rating = get_comment_meta($comment->comment_ID, 'rating', true);
    // Make sure the rating is a valid number.
    if ($rating >= 1 && $rating <= 5) {
        $star_counts[$rating]++;
    }
}


?>
<div class="section">
  <div class="container">
      <div class="row" id="comments">
        <form class="reviews-form" action="<?php the_permalink(); ?>#comments" method="get">
        <section class="containers section-clickable-sm-down" id="customerReviews">
          <!-- pb-3 -->
          <div class="row bg-light">
            <div class="col-12 ">
              <div class="section-bg">
                <h2 class="section-header border-0 col-12">
                  <span>Đánh giá của khách hàng</span>
                </h2>
                <div class="row review-summary">
                  <div class="col-lg-3 py-3 d-flex align-items-center justify-content-center">
                    <div class="review-summary-info d-flex flex-lg-column w-100 align-items-end align-items-lg-start">
                      <div class="d-flex align-items-end me-auto">
                        <b class="review-summary-info-point me-2"><?php echo number_format($product->get_average_rating(), 1); ?></b>
                        <div class="review-summary-info-rating">
                          <div class="d-block fs-4 text starts" style="--rating: <?php site_wc_the_stars_percent($product);?>%;"></div>
                          trên <?php echo $product->get_review_count();?> đánh giá
                        </div>
                      </div>
                      <!-- <p class="mb-0 fs-5"><b><?php //echo $total>0 ? round( $suggest_count * 100 / $total, 0) : '0';?>%</b> gợi ý sản phẩm này</p> -->
                    </div>
                  </div>
                  <div class="col-12 col-lg-6 py-3">
                    <div class="review-summary-rating">
                      <?php for( $i=5; $i>0; $i-- ):
                        $star_count = 0;
                        if( $total>0 ){
                          $star_count = get_comments( array_merge( $main_args, array(
                            'count' => true,
                            'paged' => 1,
                            'meta_query' => array(
                              array(
                                'key' => 'rating',
                                'compare' => '=',
                                'value' => $i
                              )
                            )
                          ) ) );
                        }
                      ?>
                      <div class="d-flex align-items-center rating-item">
                        <span class="me-2"><?php echo $i;?></span><i class="bi bi-star-fill text-primary me-2"></i>
                        <div class="progress flex-grow-1 me-2">
                          <div class="progress-bar" role="progressbar" style="width: <?php echo $total>0 ? round( $star_count / $total * 100, 0) : '0';?>%"></div>
                        </div>
                      </div>
                      <?php endfor;?>
                    </div>
                  </div>
                  <div class="col-12 col-lg-3 col-xl-3 py-3 d-flex align-items-center justify-content-center">
                    <button type="button" class="btn btn-outline-primary btn-lg px-xl-5 rounded" data-bs-toggle="modal"
                      data-bs-target="#writeReview">
                      Viết đánh giá
                    </button>
                  </div>
                </div>
              </div>
            </div>  
            <?php /* ?>
            <div class="col-12">
              <h3>Hình ảnh từ khách hàng</h3>
              <div class="review-images" id="customerImg">
                <?php foreach( $all_images as $img_id ):?>
                <div class="customer-image">
                  <img src="<?php echo wp_get_attachment_image_url( $img_id, 'medium' );?>" />
                </div>
                <?php endforeach;?>
              </div>
            </div>
            <?php */ ?>
          </div>
          <div class="row bg-light align-items-center">
            <div class="col-12 col-lg-3 py-2">
              <div class="search-box input-group">
                <input class="search-input form-control" name="csearch" value="<?php echo $csearch;?>" placeholder="Tìm kiếm" />
                <button class="btn btn-search text-primary" type="submit"><i class="bi bi-search"></i></button>
              </div>
            </div>
            <div class="col-12 col-lg-9 py-2">
              <!-- Lọc theo: -->
              <div class="d-flex gap-2">
                <?php for( $i=5; $i>0; $i-- ): ?>
                <label class="btn-filter" data-target="#filterReview">
                  <input type="checkbox" name="cfilter_star_<?php echo $i;?>" value="<?php echo $i;?>"
                    <?php echo in_array($i,$cstars)?'checked':'';?>
                    title="<?php echo $i;?> star" hidden />
                  <span>
                    <?php echo $i;?> <i class="bi bi-star-fill text-primary"></i>
                    <?php //if($star_counts[$i]>0): ?>
                      <span class="comment-number">(<?php echo $star_counts[$i]; ?>)</span>
                    <?php //endif; ?>  
                  </span>
                </label>
                <?php endfor;?>
              </div>
            </div>
          </div>
          <div class="section-bg">
            <div class="row review-paged">
              <!-- bg-light-grey -->
              <div class="col-12 py-2 border-bottom-1">
                <!-- bg-grey-lightest -->
                <div class="row align-items-center">
                  <span class="col-6 col-lg-3">
                    Trang <b><?php echo $paged;?></b> / <b><?php echo $max_page; ?></b>
                  </span>
                  <div class="col-12 col-lg-6 order-3 order-lg-2" id="filterReview">
                    <?php
                      foreach( $cstars as $v ) {
                        echo '<span class="badge badge-filter" data-value="'.$v.'">'.$v.' star</span>';
                      }
                    ?>
                  </div>
                  <div class="col-6 col-lg-3 order-2 order-lg-3">
                    <select class="form-select" name="csort" onchange=" this.form.submit(); ">
                      <?php foreach( site_wc_get_comment_sorts() as $value => $title ):?>
                      <option value="<?php echo $value;?>" <?php echo ( $csort == $value ? 'selected' : ''); ?> ><?php echo $title;?></option>
                      <?php endforeach;?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <?php
              foreach( $comments as $i => $comment ):
                $comment_id = $comment->comment_ID;
                $date     = get_comment_date('M d, Y', $comment_id );
                $rating   = site_wc_get_stars_percent( (int) get_comment_meta( $comment_id, 'rating', true ) );
                // $quality  = site_wc_get_stars_percent( (int) get_comment_meta( $comment_id, 'quality', true ) );
                // $value    = site_wc_get_stars_percent( (int) get_comment_meta( $comment_id, 'value', true ) );
                $images    = $comment->images;
                
                $modal_id = '';
                if( count($images)>0 ) {
                  $modal_id = 'imagePost' . $comment_id;
                }
            ?>
            <div class="row bg-light review-post pt-2 pb-2">
              <div class="col-12 col-lg-8 py-3 review-post-content border-end">
                <div class="d-flex justify-content-between post-header">
                  <div class="d-block fs-4 text starts" style="--rating: <?php echo $rating; ?>%;"></div>
                  <span><?php echo $date;?></span>
                </div>
                <div class="post-content">
                  <p class="mb-2"><b><?php echo site_hide_phone($comment->comment_author); ?></b></p>
                  <p><?php echo $comment->comment_content; ?></p>
                  <?php
                    $replies = get_comments(array(
                      'type' => 'comment',
                      'number'  => '1',
                      'parent' => $comment_id
                    ));
                    foreach( $replies as $reply ):
                  ?>
                  <div class="card card-body">
                    <div class="d-flex justify-content-between">
                      <b>Phản hồi của Vua Hoàn Thiện</b>
                    </div>
                    <div id="reply-<?php echo $reply->comment_ID;?>">
                      <p><?php echo get_comment_date('M d, Y', $reply->comment_ID );?></p>
                      <p><?php echo $reply->comment_content;?></p>
                    </div>
                  </div>
                  <?php endforeach;?>
                </div>
              </div>
              <div class="col-12 col-lg-4 review-post-images py-3">
                <?php foreach( $images as $img_id ):?>
                <span class="post-image">
                  <button type="button" style="background-image: url('<?php echo wp_get_attachment_image_url( $img_id, 'full' );?>');"
                    data-bs-toggle="modal" data-bs-target="#<?php echo $modal_id?>"></button>
                </span>
                <?php endforeach;?>
              </div>
            </div>
            <?php if( $modal_id!='' ):?>
            <div class="modal" id="<?php echo $modal_id;?>">
              <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-lg-down">
                <div class="modal-content">
                  <div class="modal-header border-0">
                    <h5 class="modal-title h4">Hình ảnh từ khách hàng <small>(<?php echo count($images);?>)</small></h5>
                    <button type="button" class="btn" data-bs-dismiss="modal">
                      Đóng <i class="bi bi-x-lg text-primary"></i>
                    </button>
                  </div>
                  <div class="modal-body carousel review-post" id="<?php echo $carousel_id = 'carouselPostImage'. $comment_id;?>">
                    <div class="row">
                      <div class="col-12 col-lg-12">
                        <div class="review-post-images position-relative">
                          <div class="carousel-inner">
                            <?php foreach( $images as $i => $img_id ):?>
                            <div class="carousel-item<?php echo $i == 0 ? ' active' : '';?>">
                              <img src="<?php echo wp_get_attachment_url( $img_id );?>" alt="" />
                            </div>
                            <?php endforeach;?>
                          </div>
                          <button class="carousel-control-prev" type="button" data-bs-target="#<?php echo $carousel_id;?>"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Trước</span>
                          </button>
                          <button class="carousel-control-next" type="button" data-bs-target="#<?php echo $carousel_id;?>"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Tiếp</span>
                          </button>
                        </div>
                      </div>
                      <div class="col-12 col-lg-12 d-flex flex-column justify-content-between py-3">
                        <!-- px-lg-5 -->
                        <div class="review-post-content">  
                          <p><b><?php echo site_hide_phone($comment->comment_author); ?></b></p>
                          <div class="d-block fs-4 text starts" style="--rating: <?php echo $rating;?>%;"></div>
                          <p class="mt-3"><b><?php echo $date;?></b></p>
                          <p><?php echo $comment->comment_content; ?></p>
                        </div>
                        <div class="">
                          <b class="d-block">Hình ảnh trong đánh giá này</b>
                          <?php foreach( $images as $i => $img_id ):?>
                          <span class="post-image">
                            <button type="button" data-bs-target="#<?php echo $carousel_id;?>" data-bs-slide-to="<?php echo $i;?>"
                              class="active"
                              style="background-image: url('<?php echo wp_get_attachment_image_url( $img_id, 'medium' );?>');">
                            </button>
                          </span>
                          <?php endforeach;?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
          </div>
          <?php
            // Pagination      
            global $wp_rewrite;

            $base = add_query_arg( 'cpage', '%#%' );
            if ( $wp_rewrite->using_permalinks() ) {
              $base = user_trailingslashit( trailingslashit( get_permalink() ) . $wp_rewrite->comments_pagination_base . '-%#%', 'commentpaged' );
            }

            $links = paginate_links( array(
              'base'      => $base,
              'prev_text' => '<span aria-hidden="true"><i class="bi bi-chevron-left"></i></span>',
              'next_text' => '<span aria-hidden="true"><i class="bi bi-chevron-right"></i></span>',
              'total'     => $max_page,
              'current'   => $paged,
              'add_fragment' => '#comments',
              'type'      => 'array'
            ) );
            
            if( $links ):
          ?>
          <nav class="mt-3">
            <ul class="pagination pagination-review justify-content-center">
              <?php foreach( $links as $text) :
                $text = str_replace("page-numbers","page-link", $text);
              ?>
              <li class="page-item<?php echo preg_match('/current/i', $text)?' active':'';?>"><?php echo $text;?></li>
              <?php endforeach;?>
            </ul>
          </nav>
          <?php endif;?>
          <?php /* ?><div class="text-center page-note">Trang <b><?php echo $paged;?></b> / <b><?php echo $max_page; ?></b></div><?php */ ?>
        </section>
        </form>
        <form action="<?php echo home_url('wp-comments-post.php'); ?>" method="post" enctype="multipart/form-data" class="comment-form">
        <div class="modal fade" id="writeReview">
          <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header border-0">
                <h3 class="modal-title">Đánh giá</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="row mb-2 pb-3 border-bottom">
                  <div class="col-12 col-md-4">
                    <img class="thumb-product" src="<?php echo wp_get_attachment_image_url( $product->get_image_id(), 'full' );?>" class="img-fluid"
                      alt="<?php echo $product->get_title(); ?>" />
                  </div>
                  <div class="col-12 col-md-8">
                    <span class="fw-bold fs-4"><?php site_the_product_brand();?></span>
                    <p><?php the_title();?></p>
                    <div class="row align-items-center mb-2">
                      <div class="col-4 col-md-3">
                        <label class="form-label">Đánh giá</label>
                      </div>
                      <div class="col-8 col-md-9">
                        <div class="star-rating">
                          <input class="d-none" id="star-5" type="radio" name="rating" value="5" />
                          <label class="fs-3" for="star-5" title="5 stars">
                            <i class="active bi bi-star-fill" aria-hidden="true"></i>
                          </label>
                          <input class="d-none" id="star-4" type="radio" name="rating" value="4" />
                          <label class="fs-3" for="star-4" title="4 stars">
                            <i class="active bi bi-star-fill" aria-hidden="true"></i>
                          </label>
                          <input class="d-none" id="star-3" type="radio" name="rating" value="3" />
                          <label class="fs-3" for="star-3" title="3 stars">
                            <i class="active bi bi-star-fill" aria-hidden="true"></i>
                          </label>
                          <input class="d-none" id="star-2" type="radio" name="rating" value="2" />
                          <label class="fs-3" for="star-2" title="2 stars">
                            <i class="active bi bi-star-fill" aria-hidden="true"></i>
                          </label>
                          <input class="d-none" id="star-1" type="radio" name="rating" value="1" />
                          <label class="fs-3" for="star-1" title="1 stars">
                            <i class="active bi bi-star-fill" aria-hidden="true"></i>
                          </label>
                        </div>
                      </div>
                    </div>
                    <!-- <div class="row align-items-center mb-2">
                      <div class="col-3">
                        <label class="form-label">Chất lượng</label>
                      </div>
                      <div class="col-9">
                        <div class="star-rating">
                          <input class="d-none" id="quality-5" type="radio" name="quality" value="5" />
                          <label class="fs-3" for="quality-5" title="5 stars">
                            <span class="d-block shape"></span>
                          </label>
                          <input class="d-none" id="quality-4" type="radio" name="quality" value="4" />
                          <label class="fs-3" for="quality-4" title="4 stars">
                            <span class="d-block shape"></span>
                          </label>
                          <input class="d-none" id="quality-3" type="radio" name="quality" value="3" />
                          <label class="fs-3" for="quality-3" title="3 stars">
                            <span class="d-block shape"></span>
                          </label>
                          <input class="d-none" id="quality-2" type="radio" name="quality" value="2" />
                          <label class="fs-3" for="quality-2" title="2 stars">
                            <span class="d-block shape"></span>
                          </label>
                          <input class="d-none" id="quality-1" type="radio" name="quality" value="1" />
                          <label class="fs-3" for="quality-1" title="1 stars">
                            <span class="d-block shape"></span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="row align-items-center mb-2">
                      <div class="col-3">
                        <label class="form-label">Giá trị</label>
                      </div>
                      <div class="col-9">
                        <div class="star-rating">
                          <input class="d-none" id="value-5" type="radio" name="value" value="5" />
                          <label class="fs-3" for="value-5" title="5 stars">
                            <span class="d-block shape"></span>
                          </label>
                          <input class="d-none" id="value-4" type="radio" name="value" value="4" />
                          <label class="fs-3" for="value-4" title="4 stars">
                            <span class="d-block shape"></span>
                          </label>
                          <input class="d-none" id="value-3" type="radio" name="value" value="3" />
                          <label class="fs-3" for="value-3" title="3 stars">
                            <span class="d-block shape"></span>
                          </label>
                          <input class="d-none" id="value-2" type="radio" name="value" value="2" />
                          <label class="fs-3" for="value-2" title="2 stars">
                            <span class="d-block shape"></span>
                          </label>
                          <input class="d-none" id="value-1" type="radio" name="value" value="1" />
                          <label class="fs-3" for="value-1" title="1 stars">
                            <span class="d-block shape"></span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="row align-items-center g-2">
                      <div class="col-auto">
                        <label class="fw-bold">Bạn có muốn gợi ý sản phẩm này cho người khác?</label>
                      </div>
                      <div class="col-auto d-flex">
                        <div class="form-check">
                          <input class="form-check-input rounded-circle" name="suggest" value="1" type="radio" name="flexRadioDefault"
                            id="yes">
                          <label class="form-check-label pe-2" for="yes">
                            Có
                          </label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input rounded-circle" name="suggest" value="" type="radio" name="flexRadioDefault" id="no">
                          <label class="form-check-label" for="no">
                            Không
                          </label>
                        </div>
                      </div>
                    </div> -->
                  </div>
                </div>
                <div class="row pb-3 border-bottom">
                  <div class="col-12">
                    <label for="yourReview" class="form-label">Nhận xét của bạn</label>
                  </div>
                  <div class="col-12 col-md-8">
                    <div class="mt-2">
                      <textarea class="form-control" name="comment" rows="8" maxlength="65525" id="yourReview" rows="10"></textarea>
                    </div>
                  </div>
                  <div class="d-none d-sm-block col-4">
                    <div class="form-text fs-12 form-suggest-text">
                      <div class="fw-bold">Gợi ý:</div>
                      <span class="btn-text">Chất lượng sản phẩm tuyệt vời</span>
                      <span class="btn-text">Đóng gói sản phẩm rất đẹp và chắc chắn</span>
                      <span class="btn-text">Shop phục vụ rất tốt</span>
                      <span class="btn-text">Rất đáng tiền</span>
                      <span class="btn-text">Thời gian giao hàng rất nhanh</span>
                    </div>
                  </div>
                </div>
                <div class="my-3 pb-3 border-bottom uploadImage-box">
                  <!-- <label for="imagesUpload" class="form-label">Thêm ảnh</label>  -->
                  <label class="btn btn-dark rounded" for="my-file-selector">
                      <input id="my-file-selector" type="file" name="reviewfile[]" 
                        multiple class="d-none" accept="image/jpeg" />
                      Thêm ảnh
                    </label>
                  <span class="form-text mb-3">(Tối đa 6 ảnh ở định dạng JPEG. Độ phân giải thấp nhất 400px x 400px)</span>
                  <div id="image-preview-container"></div>


                 <script>
                      // Function to handle file input change
                      document.getElementById("my-file-selector").addEventListener("change", function (e) {
                          const previewContainer = document.getElementById("image-preview-container");
                          previewContainer.innerHTML = ""; // Clear previous previews

                          const files = e.target.files;
                          const maxImageCount = 6; // Maximum number of images allowed

                          for (let i = 0; i < files.length; i++) {
                              if (i >= maxImageCount) {
                                  alert("Tối đa 6 ảnh được chọn.");
                                  break;
                              }

                              const file = files[i];
                              if (file.type.startsWith("image/")) {
                                  const reader = new FileReader();

                                  reader.onload = function (e) {
                                      const img = document.createElement("img");
                                      img.className = "preview-image";
                                      img.src = e.target.result;
                                      previewContainer.appendChild(img);
                                  };

                                  reader.readAsDataURL(file);
                              } else {
                                  alert("Chỉ chấp nhận ảnh định dạng JPEG.");
                              }
                          }
                      });
                  </script>


                  <!-- <div>
                    
                  </div> -->  
                </div>
                <div class="row<?php //echo $user_name!='' ? ' d-none' : '';?>">
                  <div class="col-12 col-md-8">
                    <div class="mb-3">
                      <label for="yourDisplayName" class="form-label">Tên của bạn</label>
                      <input type="text" name="author" <?php echo ($user_name)?'disabled':''; ?> value="<?php echo $user_name;?>" class="form-control" id="yourDisplayName" aria-describedby="diplayNameHelp">
                      <div id="diplayNameHelp" class="form-text hide">Không sử dụng tên đầy đủ của bạn. Chỉ sử dụng các chữ cái và số (không có khoảng trắng hoặc ký hiệu).</div>
                    </div>
                  </div>
                  <div class="col-12 col-md-4 col-submitbox">
                    <div class="mb-3">
                      <button class="btn btn-primary btn-submit rounded" type="submit" name="submit" value="Submit">Đăng bình luận</button>
                      <input type="hidden" name="url" value="" />
                      <input type="hidden" name="comment_post_ID" value="<?php echo $post_id;?>" />
                      <input type="hidden" name="comment_parent" value="0" />
                      <input type="hidden" name="email" value="guest@viet.depot" />
                      <?php wp_nonce_field( 'commentoken', 'comtoken' );?>
                    </div>
                  </div>  
                  <!-- <div class="col-12 col-md-6">
                    <div class="mb-3">
                      <label for="yourEmailAddress" class="form-label">Địa chỉ email</label>
                      <input type="email" class="form-control" id="yourEmailAddress" aria-describedby="emailHelp">                
                    </div>
                  </div> -->
                </div>
                <?php /*?>
                <div class="row<?php echo $user_name!='' ? ' d-none' : '';?>">
                  <div class="col-12">
                    <div id="emailHelp" class="form-text">Địa chỉ email của bạn sẽ không được hiển thị công khai. Email chỉ được sử dụng cho mục đích thông báo.</div>
                  </div>
                </div>   
               
                <div class="row hide">
                  <div class="col-12">
                    <div class="form-check mb-2">
                      <input class="form-check-input" name="wp-comment-cookies-consent" type="checkbox" value="yes" id="remember-review">
                      <label class="form-check-label" for="remember-review">
                        Ghi nhớ cho lần đánh giá sau
                      </label>
                    </div>
                    <div class="d-inline-block">
                      <a href="#" class="text-primary text-hover-underline fs-12" data-bs-toggle="modal"
                        data-bs-target="#termsAndConditions">Các điều khoản và điều kiện</a>
                      <a href="#" class="ms-2 text-primary text-hover-underline fs-12" data-bs-toggle="modal"
                        data-bs-target="#reviewGuidelines">Quy định đánh giá</a>
                    </div>
                  </div>
                </div>
                <?php */?> 
              </div>
              <!-- <div class="modal-footer">
                
              </div> -->
            </div>
          </div>
        </div>
        </form>
        <?php /*/?>
        <div class="modal fade" id="termsAndConditions">
          <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header border-0">
                <h3 class="modal-title">Các điều khoản và điều kiện</h3>
                <button type="button" class="btn-close" data-bs-target="#writeReview" data-bs-toggle="modal"
                  data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p>These Terms and Conditions (“T&C”) govern your submission of Content on websites maintained by The
                  Home Depot, Inc. and its subsidiaries, divisions, and affiliates (collectively, “Home Depot”). Your
                  submission of Content constitutes your agreement and acceptance of these T&C. Please read the
                  following carefully and completely. If you do not agree and decline to accept these T&C, do not
                  submit Content. From time to time, Home Depot may change these T&C by posting an updated copy. Your
                  submission of Content is also governed by the homedepot.com Terms of Use, found at (the “Terms of
                  Use”), which are incorporated by reference herein.</p>
                <p>By submitting Content, you represent and warrant that you your Content complies with the
                  requirements in the “Uploaded Content” section of the Terms of Use. You agree to indemnify and hold
                  Home Depot (and its officers, directors, agents, subsidiaries, joint ventures, employees and
                  third-party service providers, including but not limited to, Bazaarvoice, Inc.), harmless from all
                  claims, demands, and damages (actual and consequential) of every kind and nature, known and unknown
                  including reasonable attorneys' fees, arising out of a breach of your representations and warranties
                  set forth above, or your violation of any law or the rights of a third party.</p>
              </div>
            </div>
          </div>
        </div>
        <div class="modal fade" id="reviewGuidelines">
          <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header border-0">
                <h3 class="modal-title">Quy định đánh giá</h3>
                <button type="button" class="btn-close" data-bs-target="#writeReview" data-bs-toggle="modal"
                  data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p>For product reviews ("Reviews"), please focus on the product and your invidual experience with it:
                </p>
                <ul>
                  <li>For Shipping / Damaged / Delivery item issue, click on Feedback Tab, click on Website Feedback,
                    click on Feedback topic, click on Shipping/Delivery, add your Feedback, or contact us at
                    1-800-430-3376, do not post on product review</li>
                  <li>What features stand out most?</li>
                  <li>What do you like or dislike about it?</li>
                  <li>How long have you had it?</li>
                  <li>Does it meet your expectations?</li>
                  <li>Do not include any personal information about yourself or others, such as social security
                    numbers, credit or debit card numbers, payment card data, financial account numbers, account
                    passwords, or government issued identification information.</li>
                  <li>Avoid promoting or sharing opinions or beliefs about political issues, or religious
                    affiliations.</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <?php /*/?>
      </div>
  </div>    
</div>    