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
	$url 	= site_get_assets();
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

	wp_enqueue_script( 	'jquery', $url . 'js/jquery-3.6.0.js', '', '', true );
	// wp_enqueue_script( 	'jquery', 'https://code.jquery.com/jquery-3.6.0.js', '', '', true );
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
	// wp_enqueue_style( 	'bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' );
	// wp_enqueue_style( 	'animate-css',	'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css' );
	// wp_enqueue_style( 	'jquery-ui', 	'https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css' );
	wp_enqueue_style( 	'bootstrap-icons', $url . 'css/bootstrap-icons.css' );
	wp_enqueue_style( 	'animate-css',	$url . 'css/animate.min.css' );
	wp_enqueue_style( 	'jquery-ui', 	$url . 'css/jquery-ui.css' );
	wp_enqueue_style( 	'slick', 		$url . 'css/slick.css' );
	wp_enqueue_style( 	'slick-theme', 	$url . 'css/slick-theme.css' );
	wp_enqueue_style( 	'main', 		$url . 'css/main.css?v='.$time );
	wp_enqueue_style( 	'custom', 		$url . 'css/custom.css?v='.$time );
	
	// Add JS
	// wp_enqueue_script( 	'popper', 	'https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js', '', '', true );
	// wp_enqueue_script( 	'bootstrap','https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js', '', '', true );
	// wp_enqueue_script( 	'axios', 	'https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js', '', '', true );
	// wp_enqueue_script( 	'jquery-ui',  'https://code.jquery.com/ui/1.13.1/jquery-ui.min.js', '', '', true );

	wp_enqueue_script( 	'popper', 	$url . 'js/popper.min.js', '', '', true );
	wp_enqueue_script( 	'bootstrap',	$url . 'js/bootstrap.min.js', '', '', true );
	wp_enqueue_script( 	'axios', 	$url . 'js/axios.min.js', '', '', true );
	wp_enqueue_script( 	'jquery-ui',  $url . 'js/jquery-ui.min.js', '', '', true );
	
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

/** MORE **/
//show_admin_bar(false);

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
    $replace = 'Hết hàng';

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


// Boost the score of products with the keyword "đèn" in the product name.
add_filter('fibosearch_score', function($score, $product, $search_keywords) {
    if (strpos($product->get_name(), 'phễu') !== false) {
        $score += 10;
    }
    return $score;
});


function enqueue_price_slider_scripts() {
    wp_enqueue_script('jquery-ui-slider');
    wp_enqueue_style('jquery-ui-smoothness', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css');
}

add_action('wp_enqueue_scripts', 'enqueue_price_slider_scripts');

function custom_filter_products_by_price($query) {
    if (is_shop() && isset($_GET['min_price']) && isset($_GET['max_price'])) {
        $min_price = intval($_GET['min_price']);
        $max_price = intval($_GET['max_price']);
        
        $meta_query = array(
            array(
                'key' => '_price',
                'value' => array($min_price, $max_price),
                'type' => 'NUMERIC',
                'compare' => 'BETWEEN',
            )
        );

        $query->set('meta_query', $meta_query);
    }
}

add_action('pre_get_posts', 'custom_filter_products_by_price');

// Add column for review image in WooCommerce admin
function add_review_image_column($columns) {
    $columns['review_image'] = 'Review Image';
    return $columns;
}

function add_comment_image_meta_box() {
    add_meta_box('images', 'Comment Image', 'display_comment_image', 'comment', 'normal', 'high');
}

add_action('add_meta_boxes_comment', 'add_comment_image_meta_box');

function display_comment_image($comment) {
    $comment_id = $comment->comment_ID;
    $comment_image_ids = explode(',', get_comment_meta($comment->comment_ID, 'images', true ));

    if (!empty($comment_image_ids)) {
        foreach ($comment_image_ids as $image_id) {
            $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
            $image_edit_link = get_edit_post_link($image_id);
            if ($image_url) {
                echo '<div class="comment-image">';
               	 echo '<a href="'.$image_edit_link.'" target="_blank"><img width="100px" src="' . esc_url($image_url) . '" style="max-width:100%; margin-right: 5px;"/></a>';
               	 //echo '(id:'.$image_id.')';
                echo '</div>';
            }
        }
    }
}


// Add a custom metabox for review comments
function add_review_comments_metabox() {
    add_meta_box(
        'review-comments-metabox',
        'Review Comments',
        'display_review_comments_metabox',
        'product',
        'normal',
        'default'
    );
}

// Callback function to display the review comments metabox
function display_review_comments_metabox($post) {
    // Retrieve the product ID
    $product_id = $post->ID;

    // Get review comments associated with this product
    $comments = get_comments(array(
        'post_id' => $product_id,
        'status' => 'approve', // Show only approved comments
    ));

    if (!empty($comments)) {
        foreach ($comments as $comment) {
            // Display review comments
            echo '<p><a href="' . admin_url('comment.php?action=editcomment&c=' . $comment->comment_ID) . '"><strong>' . $comment->comment_author . ':</strong> ' . $comment->comment_content . '</a></p>';
            // Check if there are any attached images
            $comment_image_ids = explode(',', get_comment_meta($comment->comment_ID, 'images', true ));
            if (!empty($comment_image_ids)) {
		        foreach ($comment_image_ids as $image_id) {
		            $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
		            $image_edit_link = get_edit_post_link($image_id);
		            if ($image_url) {
		                echo '<div class="comment-image">';
		               	 echo '<a href="'.$image_edit_link.'" target="_blank"><img width="100px" src="' . esc_url($image_url) . '" style="max-width:100%; margin-right: 5px;" /></a>';
		               	 //echo '(id:'.$image_id.')';
		                echo '</div>';
		            }
		        }
		    }
        }
    } else {
        echo 'No review comments for this product.';
    }
}

add_action('add_meta_boxes', 'add_review_comments_metabox');


add_filter('woocommerce_currency_symbol', 'change_existing_currency_symbol', 10, 2);
function change_existing_currency_symbol( $currency_symbol, $currency ) {
 switch( $currency ) {
 case 'VND': $currency_symbol = 'đ'; break;
 }
 return $currency_symbol;
}

function custom_price_format($price, $product) {
    $price = str_replace('.', ',', $price);
    return $price;
}

add_filter('woocommerce_get_price_html', 'custom_price_format', 10, 2);


function custom_login_redirect($redirect_to, $request, $user) {
    if (isset($request['action']) && ($request['action'] == 'login' || $request['action'] == 'logout' || $request['action'] == 'lostpassword')) {
        return wp_get_referer() ? wp_get_referer() : home_url();
    }
    return $redirect_to;
}

add_filter('login_redirect', 'custom_login_redirect', 10, 3);


// Add a custom meta box to the product edit screen
function add_custom_meta_box() {
    add_meta_box(
        'sale_off_meta_box',
        'Sale Off Price',
        'display_sale_off_meta_box',
        'product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_custom_meta_box');

// Display the content of the custom meta box
function display_sale_off_meta_box($post) {
    // Get the current sale_off value
    $saleoff_price = get_post_meta($post->ID, 'sale_off', true);

    // Use nonce for verification
    wp_nonce_field(basename(__FILE__), 'sale_off_nonce');

    // Display the field
    ?>
    <p>
        <label for="sale_off_price">Sale Off Price:</label>
        <input type="text" id="sale_off_price" name="sale_off_price" value="<?php echo esc_attr($saleoff_price); ?>" />
    </p>
    <?php
}

// Save the custom meta box data
function save_sale_off_meta_box($post_id) {
    // Check if nonce is set
    if (!isset($_POST['sale_off_nonce'])) {
        return;
    }

    // Verify nonce
    if (!wp_verify_nonce($_POST['sale_off_nonce'], basename(__FILE__))) {
        return;
    }

    // Check if the user has permission to save data
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save the data
    if (isset($_POST['sale_off_price'])) {
        update_post_meta($post_id, 'sale_off', sanitize_text_field($_POST['sale_off_price']));
    }
}
add_action('save_post', 'save_sale_off_meta_box');


// function custom_redirect_to_welcome_page() {
//     // Check if the user is not logged in
//     if ( ! is_user_logged_in() && ! is_page( 'welcome-vht' ) && ! is_admin() ) {
//         // Get the URL of your custom welcome page
//         $welcome_page_url = home_url( '/welcome-vht' ); // Make sure to include the trailing slash

//         // Redirect the user to the custom welcome page
//         wp_redirect( $welcome_page_url );
//         exit();
//     }
// }

// // Hook the function to the 'template_redirect' action
// add_action( 'template_redirect', 'custom_redirect_to_welcome_page' );


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
                $random_number = rand(300, 500);
                update_field('virtual_like_number', $random_number, $product_id);
            endwhile;
            wp_reset_postdata();
        }
    }

    wp_die();
}

function remove_dgwt_wcas_parameter() {
    // Check if the parameter is present and has the value 1
    if (isset($_GET['dgwt_wcas']) && $_GET['dgwt_wcas'] == 1) {
        // Remove the parameter
        unset($_GET['dgwt_wcas']);

        // Build the new URL without the parameter
        $new_url = remove_query_arg('dgwt_wcas', home_url(add_query_arg($_GET, $_SERVER['REQUEST_URI'])));

        // Redirect to the updated URL without the parameter
        wp_redirect($new_url);
        exit();
    }
}

add_action('template_redirect', 'remove_dgwt_wcas_parameter');

// Remove Product Short Description in Product Detail Editor
function remove_product_short_description() {
    remove_meta_box( 'postexcerpt', 'product', 'normal');
}
add_action('add_meta_boxes', 'remove_product_short_description', 999);


// Config for searching only for Product title, not search for description
function search_filter_by_title_only($search, &$wp_query) {
    global $wpdb;
    if (empty($search)) {
        return $search; // skip processing - no search term in query
    }
    if (!isset($wp_query->query_vars)) {
        return $search; // skip processing - not a search query
    }
    if ('product' != $wp_query->query_vars['post_type']) {
        return $search; // skip processing - not a product search
    }

    $q = $wp_query->query_vars;
    $n = !empty($q['exact']) ? '' : '%';
    $search = '';
    $searchand = '';
    foreach ((array)$q['search_terms'] as $term) {
        $term = esc_sql($wpdb->esc_like($term));
        $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
        $searchand = ' AND ';
    }
    if (!empty($search)) {
        $search = " AND ({$search}) ";
        if (!is_user_logged_in()) {
            $search .= " AND ($wpdb->posts.post_password = '') ";
        }
    }
    return $search;
}

add_filter('posts_search', 'search_filter_by_title_only', 500, 2);


// function custom_search_order_priority($clauses) {
//     global $wpdb, $wp_query;
//     if (!is_search() || empty($wp_query->query_vars['s']) || 'product' !== $wp_query->query_vars['post_type']) {
//         return $clauses; // Only modify search queries for products
//     }

//     $search_term = $wp_query->query_vars['s'];
//     // Adjust these conditions as needed for your specific case
//     $priority_conditions = "CASE WHEN {$wpdb->posts}.post_title LIKE ' %ổ cắm% ' THEN 1 ELSE 2 END";

//     // Modify the ORDER BY clause to include our custom prioritization
//     $clauses['orderby'] = $priority_conditions . ", " . $clauses['orderby'];

//     return $clauses;
// }
// add_filter('posts_clauses', 'custom_search_order_priority', 20, 1);
