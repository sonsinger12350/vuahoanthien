<?php



$product = wc_get_product();



$img_main = '';

$youtube_items = array();
$youtube_links = get_field('youtube_links');
$youtube_list_link = array();

if($youtube_links):
  foreach($youtube_links as $youtube_item) {
    $youtube_list_link[] = site_getYoutubeIdFromUrl( $youtube_item["youtube_url"] );
  }
endif;

//var_dump($youtube_list_link);

//$youtube_id = site_getYoutubeIdFromUrl( get_field('youtube_url') );

//echo $youtube_id;

$gallery = array();

foreach( $product->get_gallery_image_ids() as $img_id ) {



  $img = array(

    'type' => 'image',

    'full' => wp_get_attachment_image_url( $img_id, 'full' ),

    'thumbnail' => wp_get_attachment_image_url( $img_id, 'thumbnail' ),

  );


  if ($img["full"]) {
    // code...
     $gallery[] = $img;
  }

 



  if( $img_main == '' ) {

    $img_main = '<img src="'. $img['full'] .'" class="img-fluid" />';


  if ($youtube_list_link ) {  
    if( count($youtube_list_link) > 0 ) {
        foreach ($youtube_list_link as $k => $item) {
          // code...
          $gallery[] = array(

            'type' => 'youtube',

            'full' => 'https://www.youtube.com/embed/' . $item,

            'thumbnail' => 'https://i.ytimg.com/vi/'.$item.'/hqdefault.jpg',

          );
        }
      
      }

    }
  }

}

//var_dump($gallery);

if (count($gallery)==0) {
  $mainImg = array(

    'type' => 'image',

    'full' => wp_get_attachment_image_url( $product->get_image_id(), 'full' ),

    'thumbnail' => wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ),

  );
  $gallery[] = $mainImg;
  $img_main = '<img style="max-height: 425px;" src="'. wp_get_attachment_image_src( $product->get_image_id(), 'full' )[0] .'" class="img-fluid" />';

  if( count($youtube_list_link) > 0 ) {
    foreach ($youtube_list_link as $k => $item) {
      $gallery[] = array(

        'type' => 'youtube',

        'full' => 'https://www.youtube.com/embed/' . $item,

        'thumbnail' => 'https://i.ytimg.com/vi/'.$item.'/hqdefault.jpg',

      );
    }  

  }

}

// Reorder the gallery array to have image types first, followed by video types
$orderedGallery = array();

foreach ($gallery as $item) {
    if ($item['type'] == 'image') {
        $orderedGallery[] = $item;
    }
}

foreach ($gallery as $item) {
    if ($item['type'] == 'youtube') {
        $orderedGallery[] = $item;
    }
}

//var_dump($gallery);

?>

<div class="thumbnail-image" style="display: none;">
    <?php echo wp_get_attachment_image_src( $product->get_image_id(), 'full' )[0]; ?>
</div>  

<?php /*if (count($gallery)==0): ?>
        <div class="thumbnail-image">
            <img src="<?php echo wp_get_attachment_image_src( $product->get_image_id(), 'full' )[0]; ?>" alt="" />
        </div>  
<?php endif; */?>  

<div class="row slider-images">

  <div class="col-12 col-md-2">

    <div class="d-nones d-sm-flex flex-column h-100 slider-thumbnails">

    

        <?php foreach( $orderedGallery as $i => $img ):?>

          <?php /* if( $i > 3 ): ?>

          <a class="d-block position-relative thumbnail" href="#"

            data-type="<?php echo $img['type'];?>"

            data-bg="<?php echo $img['full'];?>"

            slide-index="<?php echo $i; ?>"

            data-bs-toggle="modal" data-bs-target="#imagesModal">

             <img src="<?php echo $img['thumbnail'];?>" class="img-thumbnail" alt="" /> <!-- thumbnail -->

            <span

              class="d-flex align-items-center bg-dark position-absolute top-0 bottom-0 start-0 end-0 opacity-50 text-light text-center fw-bold fs-4">

              Xem thêm

            </span>

          </a>

          <?php break; else: */ ?>

          <?php if ($img['full']): ?>
              <a class="d-flex align-items-center thumbnail image <?php echo $img['type'];?> <?php echo ($i==0)?"selected":""; ?>" href="#" 

              data-type="<?php echo $img['type'];?>" 

              slide-index="<?php echo $i; ?>"

              data-bg="<?php echo $img['full'];?>">

              <?php if ($img['type'] == "youtube"): ?>
                  <i class="bi bi-play-btn"></i>
              <?php endif ?>   

              <img src="<?php echo $img['thumbnail'];?>" class="img-thumbnail" alt="" /> <!-- thumbnail -->

            </a>
          <?php endif ?>

            

          <?php //endif;?>

        <?php endforeach;?>
 
    </div>

    <div class="d-none d-flex w-100 section-clickable-sm-down overflow-scroll mt-2 d-sm-none"

      data-bs-toggle="modal" data-bs-target="#mbProductImageModal">

      <?php foreach( $orderedGallery as $i => $img ):?>

      <a href="#" class="me-1 img-thumbnail">
        <!-- h-100  -->
        <img src="<?php echo $img['full'];?>" class="h-300-px" alt="" />

      </a> 

      <?php endforeach;?>

    </div>

  </div>

  <div class="col-12 col-md-10"> <!-- d-nones d-sm-block -->

    <div class="text-center main-images">

      <iframe id="youtube-player" height="360" style="width: 100%;" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      
      <div class="tb-mb table">
        <div class="tb-cell-mb table-cell">
            <a href="#" data-bg="<?php echo wp_get_attachment_image_src( $product->get_image_id(), 'full' )[0]; ?>" data-bs-toggle="modal" data-bs-target="#imagesModal">
              <?php echo $img_main;?>    
            </a>  
        </div>
      </div>
      

    </div>

  </div>

</div>

<!-- Modal -->

<div class="modal fade modal-images" id="imagesModal" tabindex="-1" aria-labelledby="imagesModalLabel"

  aria-hidden="true">

  <div class="modal-dialog modal-xl" style="max-width: 800px;">
 <!-- h-100 -->
    <div class="modal-content">

      <div class="modal-header">

        <div class="product-title">
            <strong><?php the_title();?></strong>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

      </div>

      <div class="modal-body">

        <div class="row">

          <div class="col-12 pt-2">

            <div class="m-auto main-images-slide">

              <?php //var_dump($gallery); ?>

              <!-- <iframe height="360" style="width: 100%;" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> -->

              <?php //echo $img_main;?>
              <?php foreach( $orderedGallery as $i => $img ):?>
                <?php if ($img['full']): ?>
                    <div class="img-slide">
                        <div class="img-slide-inside">
                          <?php if ($img["type"] == 'youtube'): ?>
                            <iframe class="youtube-player-modal" id="youtube-player-modal" width="100%" height="400" src="<?php echo $img['full'];?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                          <?php elseif ($img["type"] == 'image'): ?>  
                            <img src="<?php echo $img['full'];?>" class="imgmain" alt="" />
                          <?php endif ?>

                        </div>
                    </div>  
                <?php endif ?>
                
              
              <?php endforeach;?>

              <a class="rounded prev">&#10094;</a>
              <a class="rounded next">&#10095;</a>

            </div>

          </div>

          <style type="text/css">
            .main-images-slide {
              position: relative;
              width: fit-content;
              height: 30vw;
              width: 100%;
              margin: 0 auto;
              overflow: hidden;
            }

            .img-slide {
              display: none;
              text-align: center;
              width: 100%;
              height: 100%;
            }
            .img-slide .img-slide-inside {
              display: flex;
              align-items: center;
              width: 100%;
              height: 100%;
            } 

            .imgmain {
              display: block;
              max-width: 90%;
              max-height: 100%;
              height: auto;
              margin: 0 auto;
            }

            .modal-images .prev, 
            .modal-images .next {
              position: absolute;
              top: 50%;
              transform: translateY(-50%);
              font-size: 24px;
              padding: 10px;
              background-color: #000;
              color: #fff;
              cursor: pointer;
              z-index: 1;
            }
            .modal-images .prev {
              left: 10px;
            }
            .modal-images .next {
              right: 10px;
            }
            .modal-images .prev:hover, 
            .modal-images .next:hover {
              background-color: var(--color-primary);
              color: #fff;
            }

            /* Add your CSS styles here */
            #stop-button {
              display: block;
              margin-top: 10px;
              padding: 10px 20px;
              background-color: #FF5733;
              color: white;
              border: none;
              cursor: pointer;
            }

            @media (max-width: 768px) {  
                .main-images-slide {
                  height: 100vw;
                }
                .imgmain {
                  max-width: 100%;
                }
                .modal-images .prev {
                  left: 0;
                }
                .modal-images .next {
                  right: 0;
                }
            }
          </style>

          <script>
            var slideIndex = 0;
            var player = document.getElementById("youtube-player");
            var playerModal = document.getElementsByClassName("youtube-player-modal");

            showSlide(slideIndex);

            function showSlide(n) {
              var slides = document.getElementsByClassName("img-slide");
              var prevButton = document.getElementsByClassName("prev")[0];
              var nextButton = document.getElementsByClassName("next")[0];

              // Hide all slides
              for (var i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
              }

              // Update slide index
              if (n < 0) {
                slideIndex = slides.length - 1;
              } else if (n >= slides.length) {
                slideIndex = 0;
              } else {
                slideIndex = n;
              }

              // Show current slide
              slides[slideIndex].style.display = "block";

              // Update button visibility
              if (slides.length > 1) {
                prevButton.style.display = "block";
                nextButton.style.display = "block";
              } else {
                prevButton.style.display = "none";
                nextButton.style.display = "none";
              }
            }

            function plusSlides(n) {
              showSlide(slideIndex + n);
              playerModal.src = playerModal.src;
            }

            // Event listeners for previous and next buttons
            document.getElementsByClassName("prev")[0].addEventListener("click", function() {
              plusSlides(-1);
              //player.src = player.src;
            });

            document.getElementsByClassName("next")[0].addEventListener("click", function() {
              plusSlides(1);
              //player.src = player.src;
            });

            // Event listener for thumbnail clicks
            var thumbnails = document.getElementsByClassName("thumbnail");
            for (var i = 0; i < thumbnails.length; i++) {
              thumbnails[i].addEventListener("click", function() {
                var index = parseInt(this.getAttribute("slide-index"));
                activateSlide(index);
                player.src = player.src;
              });
            }

            // Function to activate specific slide item from a thumbnail click
            function activateSlide(index) {
              showSlide(index);
            }

            // Load the YouTube IFrame API asynchronously
            // var tag = document.createElement('script');
            // tag.src = "https://www.youtube.com/iframe_api";
            // var firstScriptTag = document.getElementsByTagName('script')[0];
            // firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);


            // document.addEventListener("DOMContentLoaded", function () {
            //   // var player = document.getElementById("youtube-player");
            //   var stopButton = document.getElementById("stop-button");

            //   document.getElementsByClassName("thumbnail").addEventListener("click", function() {
            //     player.src = player.src;
            //   });  


            //   stopButton.addEventListener("click", function () {
            //     // Pause the video by changing the source URL
            //     player.src = player.src;
            //   });
            // });

          </script>         

        </div>

      </div>

    </div>

  </div>

</div>