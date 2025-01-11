<?php
/**
 * ABS functions and definitions
 *
 * @package WordPress
 * @subpackage ABS
 * @since ABS 1.0
 */
//@ini_set( 'upload_max_size' , '20M' );
//@ini_set( 'post_max_size', '20M');
//@ini_set( 'max_execution_time', '300' );

/**
 * Theme setup.
 */
function site_setup() {
	
	/*
	 * Make Twenty Fourteen available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Fourteen, use a find and
	 * replace to change 'twentyfourteen' to the name of your theme in all
	 * template files.
	 */
	load_theme_textdomain( 'twentyfourteen', get_template_directory() . '/languages' );
	
	// Add RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );
	
	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails, and declare two sizes.
	add_theme_support( 'post-thumbnails' );
	
	/* navigation position */
	add_theme_support ('menus');

	//add more size for pages
	// add_image_size( 'product-thumb', 285, 285, true );
	
	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
	
	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, and column width.
 	 */
	add_editor_style( array( 'assets/css/editor-style.css', get_template_directory() ) );
	
}
add_action( 'after_setup_theme', 'site_setup' );

/**
 * Register three Main widget areas.
 *
 * @since Main 1.0
 */
function site_widgets_init() {
	
	register_sidebar( array(
		'name'		  => __( 'Header Widget Area', 'twentyfourteen' ),
		'id'			=> 'header',
		'description'   => __( 'Appears in the header section of the site.', 'twentyfourteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'		  => __( 'Banner Widget Area', 'twentyfourteen' ),
		'id'			=> 'banner',
		'description'   => __( 'Appears in the banner section of the site.', 'twentyfourteen' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'		  => __( 'Footer Widget Area', 'twentyfourteen' ),
		'id'			=> 'footer',
		'description'   => __( 'Appears in the footer section of the site.', 'twentyfourteen' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'		  => __( 'Sidebar Widget Area', 'twentyfourteen' ),
		'id'			=> 'sidebar-widget-area',
		'description'   => __( 'Appears in the Sidebar section of the site.', 'twentyfourteen' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'		  => __( 'Wishlist Count Widget Area', 'twentyfourteen' ),
		'id'			=> 'wishlist-count-widget-area',
		'description'   => __( 'Appears in the Sidebar section of the site.', 'twentyfourteen' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

}
add_action( 'widgets_init', 'site_widgets_init' );


function site_wp_deregister_script() 
{
	// WP Core
	wp_deregister_style( 'wp-block-library' );
	wp_deregister_script( 'wp-block-library' );
	wp_deregister_script( 'wp-embed' );
	wp_deregister_script( 'jquery' );
	
	// YITH WooCommerce Wishlist
	// wp_deregister_style( 'yith-wcwl-font-awesome' );
	// wp_deregister_style( 'yith-wcwl-main' );
	// wp_deregister_style( 'jquery-selectBox' );
	// wp_deregister_script( 'jquery-selectBox' );
	// wp_deregister_script( 'jquery-yith-wcwl' );

	// Woocommerce
	wp_deregister_style( 'wc-blocks-style' );
	wp_deregister_style( 'wc-blocks-vendors-style' );
	// wp_deregister_style( 'woocommerce-layout' );
	// wp_deregister_style( 'woocommerce-smallscreen' );
	// wp_deregister_style( 'woocommerce-general' );
	// wp_deregister_script( 'woocommerce-js' );
	// wp_deregister_script( 'wc-cart-fragments' );
	// wp_deregister_script( 'prettyPhoto' );
	// wp_deregister_script( 'jquery-blockui' );
	// wp_deregister_script( 'js-cookie' );
	wp_deregister_script( 'wc-checkout' );

	wp_enqueue_script( 	'jquery', 'https://code.jquery.com/jquery-3.6.0.js', '', '', true );
}
/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Main 1.0
 */
function site_scripts() {
	
	$url 	= site_get_assets();
	
	$time 	= current_time( 'YmdHis' );
	//$time 	= '202210235';

	site_wp_deregister_script();

	// Add CSS
	wp_enqueue_style( 	'bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' );
	wp_enqueue_style( 	'animate-css',	'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css' );
	wp_enqueue_style( 	'jquery-ui', 	'https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css' );
	wp_enqueue_style( 	'slick', 		$url . 'css/slick.css' );
	wp_enqueue_style( 	'slick-theme', 	$url . 'css/slick-theme.css' );
	wp_enqueue_style( 	'main', 		$url . 'css/main.css?v='.$time );
	wp_enqueue_style( 	'custom', 		$url . 'css/custom.css?v='.$time );
	
	// Add JS
	wp_enqueue_script( 	'popper', 	'https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js', '', '', true );
	wp_enqueue_script( 	'bootstrap','https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js', '', '', true );
	wp_enqueue_script( 	'axios', 	'https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js', '', '', true );
	wp_enqueue_script( 	'jquery-ui',  'https://code.jquery.com/ui/1.13.1/jquery-ui.min.js', '', '', true );
	
	wp_enqueue_script( 	'custom', 	$url . 'js/custom.js?v='.$time,  '', $time, true );
	wp_localize_script( 'custom', 	'site_setting', array(
		'home_url' 		=> home_url(),
		'compare_url' 	=> site_compare_url(),
		'wcwl_ids' 		=> site_wcwl_get_products( 'id' ),
	));
	
	wp_enqueue_script( 	'slick', 	$url . 'js/slick.js',  '', '', true );
	wp_enqueue_script( 	'slider', 	$url . 'js/slider.js',  '', '', true );
	wp_enqueue_script( 	'navbar', 	$url . 'js/navbar.js?v='.$time,  '', $time, true );
	wp_enqueue_script( 	'form', 	$url . 'js/form-validation.js',  '', '', true );
	wp_enqueue_script( 	'birthday', $url . 'js/birthday.js',  '', '', true );
	wp_enqueue_script( 	'city', 	$url . 'js/city-select.js',  '', '', true );
	wp_enqueue_script( 	'app', 		$url . 'js/app.js',  '', '', true );

	if( get_query_var('product') ) {
		wp_enqueue_script( 	'resize', 	$url . 'js/window-resize.js',  '', '', true );
	}
}
add_action( 'wp_enqueue_scripts', 'site_scripts' );


/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Presence of header image except in Multisite signup and activate pages.
 * 3. Index views.
 * 4. Full-width content layout.
 * 5. Presence of footer widgets.
 * 6. Single views.
 * 7. Featured content layout.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function site_body_classes( $classes ) {
	
	if( site_is_mobile() ){
		$classes[] = 'mobile-device';
	} else {
		$classes[] = 'desktop-device';
	}

	return $classes;
}
add_filter( 'body_class', 'site_body_classes' );

/**
 * Extend the default WordPress post classes.
 *
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 *
 * @since main 1.0
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
function site_post_classes( $classes ) {
	if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) {
		$classes[] = 'has-post-thumbnail';
	}

	return $classes;
}
add_filter( 'post_class', 'site_post_classes' );

function site_body_class()
{
	global $classes;
	
	// if( empty($classes) ) {return body_class();}

	if( empty($classes) ) {
		$classes = [];
	}

	if( is_single() ) {
		$classes[] = 'post-id-' . get_the_ID();
	}

	if( get_current_user_id()>0 ) {
		$classes[] = 'is-user';
	}

	echo 'class="' . implode(' ', $classes) . '"';
}

function site_body_class_add( $className = '' )
{
	global $classes;
	
	if( empty($classes) ) {
		$classes = [];
	}
	
	if( $className != '' ) {
		$classes[] = $className;
	}
}

/** Show admin bar for Admin User **/
if (!current_user_can('administrator')) {
    show_admin_bar(false);
}

//Create Nav Menu
if (function_exists ('register_nav_menus')) {
	register_nav_menus (array(
		'primary' 	=> 'Main Menu',
		// 'left' 		=> 'Left Menu',
		// 'mobile' 	=> 'Mobile Menu',
	));
}

function site_get_template_directory_assets( $file = '' ){
	// return get_template_directory_uri().'/assets/'.$file ;
	return home_url( 'assets/'.$file );
}

function site_get_assets( $file = '' ){
	return site_get_template_directory_assets( $file );
}

function site_the_assets( $file = '' ){
	echo site_get_assets( $file );
}

function site_is_mobile(){
	return preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$_SERVER['HTTP_USER_AGENT'])||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SERVER['HTTP_USER_AGENT'],0,4));
}


// show in admin
if( is_admin() ):

	function site_theme_setup() {
		// Set default values for the upload none box
		update_option('image_default_link_type', 'none' );
	}
	add_action('after_setup_theme', 'site_theme_setup');
	

// show in front end
else:
	
	function getCurrentLanguageCode(){
		$lang = explode('_', get_locale());
		$lang = $lang[0];
		return $lang;
	}

	function loadSidebar($name, $before = '', $after = ''){
		if ( is_active_sidebar( $name ) )  {
			echo $before;
			dynamic_sidebar( $name );
			echo $after;
		}
	}

	
endif;

require get_theme_file_path( '/inc/init.php' );

// Add Column to show All Images of Products
add_filter( 'manage_product_posts_columns', 'add_product_images_column' );
add_action( 'manage_product_posts_custom_column', 'display_product_images_column', 10, 2 );

function add_product_images_column( $columns ) {
    $columns['product_images'] = __( 'Product Images', 'woocommerce' );
    return $columns;
}

function display_product_images_column( $column, $post_id ) {
    if ( $column == 'product_images' ) {
        global $product;
        $product = wc_get_product( $post_id );
        $attachment_ids = array();
        $images = $product->get_gallery_image_ids();
        $content = $product->get_description();
        preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches);
        foreach ($matches[1] as $image_url) {
            $attachment_id = attachment_url_to_postid( $image_url );
            if ( $attachment_id ) {
                $attachment_ids[] = $attachment_id;
            }
        }
        $attachment_ids = array_merge( array( $product->get_image_id() ), $images, $attachment_ids );
        if ( $attachment_ids ) {
            foreach ( $attachment_ids as $attachment_id ) {
                //$edit_link = get_edit_post_link( $attachment_id );
                //echo '<a href="' . $edit_link . '">' . wp_get_attachment_image( $attachment_id, 'thumbnail' ) . '</a>';
                //echo wp_get_attachment_image( $attachment_id, 'thumbnail' ) ;
                $image_edit_link = get_edit_post_link( $attachment_id );
                $image_html = wp_get_attachment_image( $attachment_id, 'thumbnail' );
                $image_html = '<a href="' . $image_edit_link . '">' . $image_html . '</a>';
                echo $image_html;
                //.'<a href="#" class="remove-product-image" data-attachment-id="' . $attachment_id . '">Remove</a>';
            }
        }
    }
}

add_filter( 'woocommerce_layered_nav_link', 'remove_duplicate_cats_from_url', 10, 2 );

function remove_duplicate_cats_from_url( $link, $term_slug ) {
    $link_parts = parse_url( $link );
    parse_str( $link_parts['query'], $query_args );
    if ( isset( $query_args['cats'] ) && is_array( $query_args['cats'] ) ) {
        $selected_cats = array_unique( $query_args['cats'] );
        $selected_cats_str = implode( ',', $selected_cats );
        $query_args['cats'] = $selected_cats_str;
        $link = esc_url_raw( add_query_arg( $query_args, $link_parts['path'] ) );
    }
    return $link;
}

// Enable multiple coupon codes
function enable_multiple_coupons() {
   return true;
}
add_filter('woocommerce_coupons_enabled', 'enable_multiple_coupons');

// Get the discount amount by points
function get_points_discount_amount($user_id) {
    $points = get_user_meta($user_id, '_wc_points_balance', true); // Get the user's points balance
    $conversion_rate = get_option('wc_points_rewards_conversion_rate'); // Get the conversion rate from the plugin settings
    $discount_amount = $points / $conversion_rate; // Calculate the discount amount based on the conversion rate
    
    return $discount_amount;
}

// Allowed Updload dwg file type
function custom_upload_mimes($mimes) {
    // Add or remove file types as needed
    $mimes['dwg'] = 'application/octet-stream';
    $mimes['dxf'] = 'application/dxf';
    $mimes['ds'] = 'application/octet-stream'; // Change this to the appropriate MIME type
    $mimes['max'] = 'application/octet-stream'; 
    return $mimes;
}
add_filter('upload_mimes', 'custom_upload_mimes');


function custom_replace_contact_text($content) {
    $search = 'Liên hệ';
    $replace = 'Liên hệ';

    return str_replace($search, $replace, $content);
}


function enqueue_custom_scripts() {
    wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/js/custom-script.js', array( 'jquery' ), null, true );
    wp_localize_script( 'custom-script', 'ajaxurl', admin_url( 'admin-ajax.php' ) );
}
add_action( 'wp_enqueue_scripts', 'enqueue_custom_scripts' );

function add_thumbnail_to_gallery() {
    error_log('AJAX request received');
    
    $product_id = $_POST['product_id'];
    error_log('Product ID: ' . $product_id);
    
    $thumbnail_id = get_post_thumbnail_id($product_id);
    error_log('Thumbnail ID: ' . $thumbnail_id);
    
    // Get existing gallery IDs
    $gallery_ids = explode(',', get_post_meta($product_id, '_product_image_gallery', true));
    
    // Add the thumbnail ID to the gallery if not already present
    if (!in_array($thumbnail_id, $gallery_ids)) {
        $gallery_ids[] = $thumbnail_id;
        update_post_meta($product_id, '_product_image_gallery', implode(',', $gallery_ids));
        wp_send_json_success(); // Success response
    }
    
    wp_send_json_error(array('message' => 'Error adding thumbnail to gallery'));
}
add_action('wp_ajax_add_thumbnail_to_gallery', 'add_thumbnail_to_gallery');
add_action('wp_ajax_nopriv_add_thumbnail_to_gallery', 'add_thumbnail_to_gallery');

// Fix Error text not translate
add_filter('login_errors','login_error_message');

    function login_error_message($error){
        //check if that's the error you are looking for
        $pos = strpos($error, 'incorrect');
        if (is_int($pos)) {
            //its the right error so you can overwrite it
            $error = "Số điện thoại hoặc mật khẩu không chính xác. Xin vui lòng kiểm tra lại.";
        }
        return $error;
    }


add_action('yith_wcwl_before_wishlist_title', 'custom_add_popup_links');
function custom_add_popup_links($var) {
    if (!is_user_logged_in()) {
        echo '<div class="wishlist-page-links">
                  <a href="#" class="create popup-btn" data-bs-toggle="modal" data-bs-target="#show-popup-ask-to-call" title="Tạo danh sách mới">Tạo danh sách mới</a>
              </div>';
              // <a href="#" class="manage popup-btn" data-bs-toggle="modal" data-bs-target="#show-popup-ask-to-call" title="Manage wishlists">Danh sách yêu thích của bạn</a>
    }
}



// Add this to your theme's functions.php file

add_action('wp_ajax_add_random_numbers', 'add_random_numbers_callback');

function add_random_numbers_callback() {
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    if ($category_id) {
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'id',
                    'terms'    => $category_id,
                ),
            ),
        );
        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) : $query->the_post();
                $product_id = get_the_ID();
                $random_number = rand(500, 1500);
                update_field('virtual_like_number', $random_number, $product_id);
            endwhile;
            wp_reset_postdata();
        }
    }

    wp_die();
}

function enable_product_revisions() {
    add_post_type_support('product', 'revisions');
}
add_action('init', 'enable_product_revisions'); 

function custom_woocommerce_price_html( $price, $product ) {
    // Check if product has no regular or sale price
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();

    // Check if both regular price and sale price are greater than 0
    if ( ( $regular_price <= 0 || empty( $regular_price ) ) && 
         ( $sale_price <= 0 || empty( $sale_price ) ) ) {
        // Replace the price with "Liên hệ"
        $price = '<span class="woocommerce-price-contact fw-bold fs-8 text-danger">Liên hệ</span>';
    }
    return $price;
}
add_filter( 'woocommerce_get_price_html', 'custom_woocommerce_price_html', 10, 2 );


function disable_wp_block_library_css() {
    wp_dequeue_style( 'wp-block-library' ); // Remove Gutenberg block library CSS
    wp_dequeue_style( 'wp-block-library-theme' ); // Remove Gutenberg theme styles
    wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block styles if WooCommerce is active
}
add_action( 'wp_enqueue_scripts', 'disable_wp_block_library_css', 100 );

function update_order_gift($order_id) {
	global $wpdb;

	$order = wc_get_order($order_id);
	$table = $wpdb->prefix.'user_gifts';
    $total = $order->total;
	$user_id = $order->get_user_id();
	$order_gift_old = get_post_meta($order_id, '_order_gift', true);
	$order_gift_old = !empty($order_gift_old) ? explode(',', $order_gift_old) : [];
	$list_gift = site_get_checkout_gift('DESC');
	$activeGift = '';
	$activeVoucher = '';

	foreach ($list_gift['coupons'] as $gift) {
		$condition_earn = get_metadata('post', $gift->id)['condition_earn'][0];

		if ($total > $condition_earn) {
			$activeGift = $gift->code;
			break;
		}
	}

	foreach ($list_gift['vouchers'] as $gift) {
		$condition_earn = get_metadata('post', $gift->id)['condition_earn'][0];

		if ($total > $condition_earn) {
			$activeVoucher = $gift->code;
			break;
		}
	}

	$order_gift_new = [$activeGift, $activeVoucher];

	if (!empty(array_diff($order_gift_old, $order_gift_new)) || empty($order_gift_new)) {
		$idsPlaceholder = implode(',', array_fill(0, count($order_gift_old), '%s'));
		$query = "DELETE FROM {$table} WHERE gift_code IN ($idsPlaceholder) AND user_id = %d AND order_id = %d";
		$prepareValues = array_merge($order_gift_old, [$user_id, $order_id]);
		$wpdb->query($wpdb->prepare($query, ...$prepareValues));
		$id_update = !empty($order_gift_new) ? implode(',', $order_gift_new) : '';
		update_post_meta($order_id, '_order_gift', $id_update);
	}
}

// Thêm loại coupon mới
function add_new_coupon_discount_type( $discount_types ) {
    $discount_types['voucher'] = __( 'Voucher', 'woocommerce' );
    return $discount_types;
}
add_filter( 'woocommerce_coupon_discount_types', 'add_new_coupon_discount_type' );

// Do action when order complete
function custom_order_status_changed( $order_id, $old_status, $new_status ) {
	global $wpdb;
    $order = wc_get_order( $order_id );
	$table = $wpdb->prefix.'user_gifts';
	$user_id = $order->get_user_id();
	$currentTime = current_time('mysql');

    if ( $new_status == 'completed' ) {
		$list_gift = site_get_checkout_gift('ASC', true);
		$order_gift = get_post_meta($order_id, '_order_gift', true);

		if (!empty(explode(',', $order_gift))) {
			foreach (explode(',', $order_gift) as $gift) {
				if (!empty($list_gift[$gift])) {
					$type = $list_gift[$gift]->discount_type == 'voucher' ? 'voucher' : 'coupon';
					$gift_id = $list_gift[$gift]->id;
					$dataExist = $wpdb->get_var(
						$wpdb->prepare(
							"SELECT id FROM {$table} WHERE user_id = %d AND gift_id = %d AND order_id = %d",
							$user_id, $gift_id, $order_id
						)
					);
		
					if (empty($dataExist)) {
						$data = array(
							'user_id' => $user_id,
							'gift_id' => $gift_id,
							'gift_code' => $gift,
							'order_id' => $order_id,
							'type' => $type,
							'status' => 0,
							'date_assigned' => $currentTime
						);
						$format = array('%d', '%d', '%s', '%d', '%s', '%d', '%s');
		
						$wpdb->insert($table, $data, $format);
					}
				}
			}
		}
    }

	if ($new_status == 'cancelled') {
		$dataExist = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT GROUP_CONCAT(id) FROM {$table} WHERE user_id = %d AND used_in_order = %d",
				$user_id, $order_id
			)
		);

		if (!empty($dataExist)) {
			$data = [
				'status' => 0,
				'used_date' => null,
				'used_in_order' => null,
			];
			$ids = explode(',', $dataExist);

			$idsPlaceholder = implode(',', array_fill(0, count($ids), '%d'));
			$query = "UPDATE {$table} SET status = %d, used_date = %s, used_in_order = %s WHERE id IN ($idsPlaceholder)";
			$prepareValues = array_merge(array_values($data), $ids);
			$wpdb->query($wpdb->prepare($query, ...$prepareValues));
		}
	}
}
add_action( 'woocommerce_order_status_changed', 'custom_order_status_changed', 10, 3 );

function action_after_order_total_changes($order_id, $items) {
	update_order_gift($order_id);
}
add_action('woocommerce_before_save_order_items', 'action_after_order_total_changes', 10, 2);

function action_after_save_order_items($order_id, $items) {
    update_order_gift($order_id);
}
add_action('woocommerce_save_order_items', 'action_after_save_order_items', 10, 2);

function custom_order_processing($order_id) {
	if (is_user_logged_in()) {
		$order = wc_get_order($order_id);
		$coupons = $order->get_used_coupons();

		if (!empty($coupons)) {
			global $wpdb;
			$table_name = $wpdb->prefix.'user_gifts';
			$user_id = $order->get_user_id();
			$currentTime = current_time('mysql');

			foreach ($coupons as $coupon) {
				$existData = $wpdb->get_results($wpdb->prepare(
					"SELECT id, used_in_order FROM $table_name WHERE gift_code = %s AND user_id = %s AND used_in_order IS NULL LIMIT 1",
					$coupon, $user_id
				), ARRAY_A);

				$existData = !empty($existData[0]) ? $existData[0] : [];

				if (!empty($existData) && $existData['used_in_order'] != $order_id) {
					$data = [
						'status' => 1,
						'used_date' => $currentTime,
						'used_in_order' => $order_id,
					];

					$where = ['id' => $existData['id']];
					$wpdb->update($table_name, $data, $where, array('%s'), array('%d'), array('%s'), array('%s'));
				}
			}
		}
	}
}
add_action('woocommerce_order_status_processing', 'custom_order_processing', 10, 1);

function get_modal_product_compare() {
	$products = [];
	$html = '';

	if (!empty($_GET['id'])) $products[] = wc_get_product($_GET['id']);

	if (!empty($products)) {
		$template_path = get_template_directory() . '/page-templates/modal-compare.php';
		ob_start();
		include $template_path;
		$html = ob_get_clean();
	}

    wp_send_json_success(['content' => $html]);
    die();
}
add_action( 'wp_ajax_get_modal_product_compare', 'get_modal_product_compare' );
add_action( 'wp_ajax_nopriv_get_modal_product_compare', 'get_modal_product_compare' );

function search_product_compare() {

	global $wpdb;

	if (empty($_GET['keyword']) || empty($_GET['product_type']) || empty($_GET['current_product'])) {
		wp_send_json_success(['content' => '<p class="text-center">Không tìm thấy sản phẩm</p>']);
    	die();
	}

	$productIds = [];
	$results = DGWT_WCAS()->nativeSearch->getSearchResults( $_GET['keyword'], true, 'product-ids' );
	if ( isset( $results['suggestions'] ) && is_array( $results['suggestions'] ) ) $productIds = wp_list_pluck( $results['suggestions'], 'ID' );

	if (!empty($productIds)) {
		$product_type = $_GET['product_type'];
		$current_product = $_GET['current_product'];

		$sql = "SELECT p.ID, p.post_title
			FROM {$wpdb->prefix}posts p
			INNER JOIN {$wpdb->prefix}postmeta pm ON p.ID = pm.post_id
			WHERE p.post_type = 'product'
			AND p.post_status = 'publish'
			AND p.ID IN (".implode(',', $productIds).")
			AND p.ID != %d
			AND pm.meta_key = 'product_type'
			AND pm.meta_value = %s
			GROUP BY p.ID
			LIMIT 10
		";

		$query = $wpdb->prepare($sql, $current_product, $product_type);
		$results = $wpdb->get_results($query);
		$data = '';
	}

	if (!empty($results)) {
		foreach ($results as $v) {
			$meta_values = get_post_meta($v->ID);
			$img = get_the_post_thumbnail_url($v->ID, 'full');
			$regular_price = !empty($meta_values['_regular_price'][0]) ? $meta_values['_regular_price'][0] : 0;
			$price = !empty($meta_values['_price'][0]) ? $meta_values['_price'][0] : 0;

			$data .= '<div class="item" data-id="'.$v->ID.'">
				<div class="image">
					<img src="'.$img.'" alt="'.$v->post_title.'">
				</div>
				<div class="info">
					<p class="name">'.$v->post_title.'</p>
					<p class="price">
						<del>'.number_format($regular_price, 0).'₫</del>
						<span>'.number_format($price, 0).'₫</span>
					</p>
				</div>
			</div>';
		}
	}

	if (empty($data)) $data = '<p class="text-center">Không tìm thấy sản phẩm</p>';

    wp_send_json_success(['content' => $data]);
    die();
}
add_action( 'wp_ajax_search_product_compare', 'search_product_compare' );
add_action( 'wp_ajax_nopriv_search_product_compare', 'search_product_compare' );

function my_custom_woocommerce_endpoint() {
    // Đăng ký endpoint
    add_rewrite_endpoint( 'kho-qua-tang', EP_PAGES );

    add_filter( 'woocommerce_get_query_vars', function( $vars ) {
        $vars['kho-qua-tang'] = 'kho-qua-tang';
        return $vars;
    });

    add_filter( 'woocommerce_account_menu_items', function( $items ) {
        $items['kho-qua-tang'] = __( 'Kho quà tặng', 'textdomain' );
        return $items;
    });

    add_action( 'woocommerce_account_kho-qua-tang_endpoint', function() {
        wc_get_template( 'myaccount/my-gift.php', array(), '', get_stylesheet_directory() . '/woocommerce/' );
    });
}
add_action( 'init', 'my_custom_woocommerce_endpoint' );

function register_user_gifts_submenu() {
    add_submenu_page(
        'woocommerce-marketing',    // Slug của menu chính 'Marketing'
        'Danh sách quà tặng',       // Tên của submenu
        'Danh sách quà tặng',       // Tên hiển thị trong menu
        'manage_options',           // Capability cần thiết để truy cập trang này
        'user-gifts-list',          // Slug của submenu
        'display_user_gifts_page'   // Hàm callback để hiển thị nội dung trang
    );
}
add_action('admin_menu', 'register_user_gifts_submenu');

function display_user_gifts_page() {
	require_once get_template_directory() . '/inc/admin/list-user-gift.php';
    echo '<div class="wrap">';
    echo '<h1 class="wp-heading-inline">Danh sách quà tặng của người dùng</h1>';
    
    $giftsListTable = new User_Gifts_List_Table();
    $giftsListTable->prepare_items();

    echo '<form method="post">';
    $giftsListTable->display();
    echo '</form>';

    echo '</div>';
}

function handle_delete_gift_action() {
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id']) && check_admin_referer('delete_gift')) {
        global $wpdb;

        $id = intval($_GET['id']);

        $wpdb->delete("{$wpdb->prefix}user_gifts", ['id' => $id]);

        wp_redirect(admin_url('admin.php?page=user-gifts-list'));
        exit;
    }
}
add_action('admin_init', 'handle_delete_gift_action');

function add_delete_confirmation_script() {
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var deleteLinks = document.querySelectorAll('a.delete-gift');

            deleteLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    if (!confirm('Bạn có chắc chắn muốn xóa quà tặng này không?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
    <?php
}
add_action('admin_footer', 'add_delete_confirmation_script');

function custom_override_coupon_validation() {
    add_filter('woocommerce_coupon_is_valid', 'custom_check_coupon_validity', 1, 2);
}
add_action('woocommerce_cart_loaded_from_session', 'custom_override_coupon_validation');

// Check coupon before apply
function custom_check_coupon_validity( $valid, $coupon ) {
	$mygift = site_get_my_gift(get_current_user_ID());

    if (!empty($mygift[$coupon->code])) {
		return true;
	}

	return false;
}
add_filter( 'woocommerce_coupon_is_valid', 'custom_check_coupon_validity', 10, 2 );

function save_gift_selection() {
	if ( !is_user_logged_in() ) wp_send_json_error(__('User not logged in.', 'your-textdomain'));

    if ( isset($_GET['order_gift']) ) {
		$total = WC()->cart->get_total(false);

		if (!empty($total)) {
			$order_gift = $_GET['order_gift'];
			$listGift = site_get_checkout_gift('ASC', true);

			foreach ($order_gift as $k => $v) {
				if (!empty($order_gift[$k])) continue;
				if ($listGift[$v]->minimum_amount > $total) unset($order_gift[$k]);
			}
			
			WC()->session->set('order_gift', $order_gift);
			wp_send_json_success(__('OK.', 'your-textdomain'));
		}
    } else {
        wp_send_json_error(__('Invalid request.', 'your-textdomain'));
    }
}
add_action('wp_ajax_save_gift_selection', 'save_gift_selection');
add_action('wp_ajax_nopriv_save_gift_selection', 'save_gift_selection');

function remove_gift_from_cart() {
    if (!empty($_POST['gift'])) {
		$cartGift = WC()->session->get('order_gift');
		$gifts = $_POST['gift'];

		foreach ($gifts as $gift) {
			if (array_search($gift, $cartGift) !== false) unset($cartGift[array_search($gift, $cartGift)]);
			WC()->session->set('order_gift', $cartGift);
		}

		wp_send_json_success(__('OK.', 'your-textdomain'));
    } else {
        wp_send_json_error(__('Invalid request.', 'your-textdomain'));
    }
}
add_action('wp_ajax_remove_gift_from_cart', 'remove_gift_from_cart');
add_action('wp_ajax_nopriv_remove_gift_from_cart', 'remove_gift_from_cart');

function save_gift_selection_to_order($order, $data) {
    if (is_user_logged_in()) {
		$order_gift = WC()->session->get('order_gift');
    
		if (!empty($order_gift)) {
			// Lưu dữ liệu vào đơn hàng
			$order->update_meta_data('_order_gift', implode(',', $order_gift));
			WC()->session->set('order_gift', null);
		}
	}
}
add_action('woocommerce_checkout_create_order', 'save_gift_selection_to_order', 20, 2);

function custom_wp_login_redirect($user_login, $user) {
    if (isset($_GET['redirect_to']) && !empty($_GET['redirect_to'])) {
        wp_safe_redirect($_GET['redirect_to']);
        exit;
    }
}
add_action('wp_login', 'custom_wp_login_redirect', 10, 2);

// Thêm vào file plugin hoặc file functions.php của theme
function handle_export_user_gifts() {
    if (!current_user_can('export')) {
        wp_die(__('Bạn không có quyền export dữ liệu.', 'textdomain'));
    }

    // Tạo file CSV
	$filename = 'user_gifts_' . date('Y-m-d') . '.csv';
	header('Content-Type: text/csv; charset=UTF-8');
	header('Content-Disposition: attachment; filename="' . $filename . '"');
	header("Pragma: no-cache");
	header("Expires: 0");
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    fputcsv($output, ['User', 'Quà tặng', 'Chi tiết', 'Ngày nhận']);

    // Lấy dữ liệu cần export
    global $wpdb;
		$sql = "SELECT g.*,p.post_excerpt gift_name,
		u.display_name, 
		u.user_nicename,
		(SELECT meta_value FROM {$wpdb->prefix}postmeta WHERE g.gift_id = post_id AND meta_key = '_thumbnail_id') img
		FROM {$wpdb->prefix}user_gifts g
		JOIN {$wpdb->prefix}users u ON g.user_id = u.ID
		JOIN {$wpdb->prefix}posts p ON g.gift_id = p.ID
		WHERE status = 0
		ORDER BY g.user_id, g.type
	";

	$results = $wpdb->get_results($wpdb->prepare($sql), ARRAY_A);

    // Thêm dữ liệu vào CSV
    foreach ($results as $item) {
		fputcsv($output, [$item['display_name'].'('.$item['user_nicename'].')', $item['gift_code'], $item['gift_name'], $item['date_assigned']]);
	}

    fclose($output);
    exit;
}
add_action('admin_post_export_user_gifts', 'handle_export_user_gifts');

function ajax_replace_coupon() {
    if ( isset( $_POST['coupon'] ) ) {
        $new_coupon = sanitize_text_field( $_POST['coupon'] );
        $applied_coupons = WC()->cart->get_applied_coupons();
		
        foreach( $applied_coupons as $coupon ) {
            WC()->cart->remove_coupon( $coupon );
        }

        $result = WC()->cart->apply_coupon( $new_coupon );
        // WC()->cart->calculate_totals();

        if ( $result ) {
            wp_send_json_success( array( 'message' => 'Coupon applied successfully.' ) );
        }
		else {
            wp_send_json_error( array( 'message' => 'Failed to apply coupon.' ) );
        }
    }

    wp_die();
}

add_action( 'wp_ajax_replace_coupon', 'ajax_replace_coupon' );
add_action( 'wp_ajax_nopriv_replace_coupon', 'ajax_replace_coupon' );

add_action('woocommerce_process_shop_order_meta', 'handle_admin_edit_order', 10, 2);

function handle_admin_edit_order($post_id, $post) {
	$orderHookAction = new OrderHookAction();
	$orderHookAction->order_update($post_id);
}

add_filter('wc_order_is_editable', function($is_editable, $order_id) {
    $order = wc_get_order($order_id);
    if ($order && $order->get_status() === 'processing') {
        $is_editable = true;
    }
    return $is_editable;
}, 10, 2);

// Thêm cột mới vào danh sách đơn hàng
add_filter('manage_edit-shop_order_columns', 'custom_shop_order_column', 20);
function custom_shop_order_column($columns) {
    $new_columns = [];

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

		if ($key == 'order_number') $new_columns['kiotviet_code'] = __('Mã ĐH KiotViet', 'text-domain');
    }

    return $new_columns;
}

// Hiển thị nội dung cho cột mới
add_action('manage_shop_order_posts_custom_column', 'custom_shop_order_column_content', 20, 2);
function custom_shop_order_column_content($column, $post_id) {
	if ('kiotviet_code' === $column) {
		global $wpdb;

		$data = $wpdb->get_var("SELECT data_raw FROM vhd_kiotviet_sync_orders WHERE order_id = $post_id");

		if (!empty($data)) {
			$data = json_decode($data);
			echo '#'.$data->code;
		}
    }
}

// Đăng ký cron job
if ( ! wp_next_scheduled( 'clear_old_search_history' ) ) {
    wp_schedule_event( time(), 'daily', 'clear_old_search_history' ); // Chạy mỗi ngày
}

// Hàm xóa lịch sử tìm kiếm cũ
add_action( 'clear_old_search_history', 'delete_old_search_history' );

function delete_old_search_history() {
    global $wpdb;

	$table = "{$wpdb->prefix}search_history";
	$maxRecord = 5;

	$sql = "SELECT user_id, COUNT(1) total FROM $table GROUP BY user_id";
	$data = $wpdb->get_results($sql);

	if (!empty($data)) {
		foreach ($data as $v) {
			if ($v->total > $maxRecord) {
				$removeQty = $v->total - $maxRecord;
				$ids = $wpdb->get_var("SELECT GROUP_CONCAT(id ORDER BY created_at ASC LIMIT $removeQty) FROM $table WHERE user_id = {$v->user_id}");
	
				if (!empty($ids)) {
					$wpdb->query(
						$wpdb->prepare(
							"DELETE FROM {$wpdb->prefix}search_history WHERE id IN ($ids)"
						)
					);
				}
			}
		}
	}

    // Đảm bảo rằng cron job sẽ không chạy khi không có dữ liệu cần xóa
    if ( ! wp_next_scheduled( 'clear_old_search_history' ) ) {
        wp_schedule_event( time(), 'daily', 'clear_old_search_history' );
    }
}

function ajax_get_search_history() {
	global $wpdb;

	if (is_user_logged_in()) $user_id = get_current_user_id();
	else $user_id = $_COOKIE['guest_user_id'];

	$results = $wpdb->get_results("SELECT keyword FROM vhd_search_history WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 5");
	$content = "";

	if (!empty($results)) {
		foreach ($results as $v) {
			$content .= "<a class='item' href='".home_url()."?s={$v->keyword}&post_type=product&dgwt_wcas=1'>{$v->keyword}</a>";
		}
	}
	else {
		$content = "<p class='no-data'>Chưa có lịch sử tìm kiếm</p>";
	}

	wp_send_json_success( $content );

    wp_die();
}

add_action( 'wp_ajax_get_search_history', 'ajax_get_search_history' );
add_action( 'wp_ajax_nopriv_get_search_history', 'ajax_get_search_history' );

function ajax_save_search_history() {
	global $wpdb;

	$keyword = sanitize_text_field($_POST['keyword']);

	if (empty($keyword)) return false;

	if (is_user_logged_in()) {
		$isGuest = 0;
        $user_id = get_current_user_id();
    } else {
		$isGuest = 1;

        if (!isset($_COOKIE['guest_user_id'])) {
            $user_id = intval(rand(100000, 999999));
            setcookie('guest_user_id', $user_id, time() + (30 * DAY_IN_SECONDS), COOKIEPATH, COOKIE_DOMAIN);
        }
		else {
            $user_id = $_COOKIE['guest_user_id'];
        }
    }

	// Save search history
	$wpdb->insert(
		$wpdb->prefix . 'search_history',
		array(
			'keyword' => $keyword,
			'user_id' => $user_id,
			'is_guest' => $isGuest
		)
	);
}

add_action( 'wp_ajax_save_search_history', 'ajax_save_search_history' );
add_action( 'wp_ajax_nopriv_save_search_history', 'ajax_save_search_history' );

function my_enqueue_admin_scripts() {
    wp_enqueue_script( 'my-custom-admin-script', get_template_directory_uri().'/assets/js/custom-admin.js', array(), time(), true );
}
add_action( 'admin_enqueue_scripts', 'my_enqueue_admin_scripts' );

class Custom_WC_Widget_Price_Filter extends WC_Widget_Price_Filter {

    /**
     * Ghi đè hàm get_filtered_price để thêm điều kiện taxonomy hiện tại.
     */
    protected function get_filtered_price() {
		global $wpdb;

		$args       = WC()->query->get_main_query()->query_vars;
		$tax_query  = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
		$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();

		if ( ! is_post_type_archive( 'product' ) && ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
			$tax_query[] = WC()->query->get_main_tax_query();
		}

		foreach ( $meta_query + $tax_query as $key => $query ) {
			if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
				unset( $meta_query[ $key ] );
			}
		}

		$queried_object = get_queried_object();

		if (is_a($queried_object, 'WP_Term')) {
			$tax_query[] = [
				'taxonomy' => $queried_object->taxonomy,
				'field' => 'term_id',
				'terms' => [$queried_object->term_id],
			];
		}
		
		$meta_query = new WP_Meta_Query( $meta_query );
		$tax_query  = new WP_Tax_Query( $tax_query );
		$search     = WC_Query::get_main_search_query_sql();

		$meta_query_sql   = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql    = $tax_query->get_sql( $wpdb->posts, 'ID' );
		$search_query_sql = $search ? ' AND ' . $search : '';

		$sql = "
			SELECT min( min_price ) as min_price, MAX( max_price ) as max_price
			FROM {$wpdb->wc_product_meta_lookup}
			WHERE product_id IN (
				SELECT ID FROM {$wpdb->posts}
				" . $tax_query_sql['join'] . $meta_query_sql['join'] . "
				WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_post_type', array( 'product' ) ) ) ) . "')
				AND {$wpdb->posts}.post_status = 'publish'
				" . $tax_query_sql['where'] . $meta_query_sql['where'] . $search_query_sql . '
			)';

		$sql = apply_filters( 'woocommerce_price_filter_sql', $sql, $meta_query_sql, $tax_query_sql );

		return $wpdb->get_row( $sql ); // WPCS: unprepared SQL ok.
	}
}

// Hủy widget gốc và đăng ký widget mới
add_action('widgets_init', 'replace_wc_price_filter_widget');

function replace_wc_price_filter_widget() {
    unregister_widget('WC_Widget_Price_Filter');
    register_widget('Custom_WC_Widget_Price_Filter');
}
