<?php
/**
 * Template Name: List Product Custom Like Number
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
?>

<form method="get" action="">
    <?php
    $args = array(
        'show_option_all' => 'Select a Category',
        'taxonomy'        => 'product_cat',
        'name'            => 'product_category',
        'orderby'         => 'name',
        'selected'        => isset($_GET['product_category']) ? $_GET['product_category'] : '',
        'hierarchical'    => true,
        'depth'           => 3,
        'show_count'      => false,
        'hide_empty'      => false,
        'hide_if_empty'   => false,
        'class'           => 'cat',
    );
    wp_dropdown_categories($args);
    ?>
    <input type="submit" value="Filter">
</form>

<?php

$category_id = isset($_GET['product_category']) ? $_GET['product_category'] : '';
$args = array(
    'post_type'      => 'product',
    'posts_per_page' => -1, // Display all products
    'tax_query'      => array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'id',
            'terms'    => $category_id,
        ),
    ),
);


$products = new WP_Query( $args );
$count = 0;

if ( $products->have_posts() ) {
    echo '<div class="product-list">'; // Container for products
    echo '<button id="addRandomNumberButton">Add Random Numbers</button>';
    while ( $products->have_posts() && $count < 50 ) {
        $products->the_post();

        // Check if the product has images in the gallery
        $product = wc_get_product();
       
        if ( empty( $gallery_images ) ) {
            echo '<div class="product-entry">';
            
            // Display product avatar
            echo get_the_post_thumbnail( get_the_ID(), 'thumbnail' );

            // Display product title with link
            echo '<div><a href="' . esc_url( get_permalink() ) . '">' . get_the_title() . '</a></div>';

            // Get edit link for the product
            $edit_link = get_edit_post_link( get_the_ID() );
          
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
    jQuery(document).ready(function ($) {
        $('#addRandomNumberButton').on('click', function () {
            // Make an AJAX request to add random numbers to all products in the category
            $.ajax({
                type: 'post',
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: {
                    action: 'add_random_numbers',
                    category_id: <?php echo json_encode($category_id); ?>,
                },
                success: function (response) {
                    alert('Random numbers added successfully.');
                },
            });
        });
    });
</script>

<?php

get_footer();