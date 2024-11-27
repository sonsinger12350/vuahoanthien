<?php
/**
 * Template Name: List Product Custom
 *
 * Description: Twenty Twelve loves the no-sidebar look as much as
 * you do. Use this page template to remove the sidebar from any page.
 *
 * Tip: to remove the sidebar from all posts and pages simply remove
 * any active widgets from the Main Sidebar area, and the sidebar will
 * disappear everywhere.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
get_header();

// Output CSS styles
echo '<style>
    .product-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .product-entry {
        border: 1px solid #ccc;
        padding: 20px;
        text-align: center;
    }

    .product-entry img {
        max-width: 100%;
        height: auto;
    }

    .product-entry h2 {
        margin: 10px 0;
    }

    .edit-link {
        display: block;
        margin-top: 10px;
        color: #0073aa;
        text-decoration: none;
    }
</style>';

$args = array(
    'post_type'      => 'product',
    'posts_per_page' => -1, // Display all products
);

$products = new WP_Query( $args );
$count = 0;

if ( $products->have_posts() ) {
    echo '<div class="product-list">'; // Container for products

    while ( $products->have_posts() && $count < 50 ) {
        $products->the_post();

        // Check if the product has images in the gallery
        $product = wc_get_product();
        $gallery_images = $product->get_gallery_image_ids();

        if ( empty( $gallery_images ) ) {
            echo '<div class="product-entry">';
            
            // Display product avatar
            echo get_the_post_thumbnail( get_the_ID(), 'thumbnail' );

            // Display product title with link
            echo '<div><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></div>';

            // Get edit link for the product
            $edit_link = get_edit_post_link( get_the_ID() );
            echo '<a class="edit-link" href="' . esc_url( $edit_link ) . '">Edit Product</a>';

             // Add button to add thumbnail to gallery
            echo '<button class="add-thumbnail-button" onclick="addThumbnailToGallery(' . get_the_ID() . ')">Add Thumbnail to Gallery</button>';


            echo '</div>'; // Close product-entry div

            $count++;
        }
    }

    echo '</div>'; // Close product-list div

    wp_reset_postdata();
} else {
    echo 'No products found.';
}

?>

<script>
function addThumbnailToGallery(productId) {
    // Perform an AJAX request to add the thumbnail to the product's gallery
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl, // WordPress AJAX URL
        data: {
            action: 'add_thumbnail_to_gallery', // AJAX action name
            product_id: productId, // ID of the product
        },
        success: function(response) {
            if (response.success) {
                alert('Thumbnail added to gallery successfully.');
            } else {
                alert('Error adding thumbnail to gallery. Server response: ' + response.data.message);
            }
        },
        error: function(error) {
            alert('Error adding thumbnail to gallery.');
        }
    });
}
</script>

<?php

get_footer();