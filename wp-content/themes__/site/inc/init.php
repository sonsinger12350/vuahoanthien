<?php


// Get setting theme
require get_theme_file_path( '/inc/admin/columns.php' );
// require get_theme_file_path( '/inc/admin/setting.php' );
require get_theme_file_path( '/inc/admin/check-update-meta.php' );

// Functions use in theme
require get_theme_file_path( '/inc/functions/acf.php' ); // ACF
require get_theme_file_path( '/inc/functions/custom.php' );
require get_theme_file_path( '/inc/functions/cookie.php' ); // cookies
require get_theme_file_path( '/inc/functions/menu-url.php' );
// require get_theme_file_path( '/inc/functions/route.php' );
require get_theme_file_path( '/inc/functions/comments.php' );

require get_theme_file_path( '/inc/functions/Hoper_Wish_Walker_Nav_Menu.php' );
require get_theme_file_path( '/inc/functions/wishlist.php' );

require get_theme_file_path( '/inc/functions/woocommerce.php' );
require get_theme_file_path( '/inc/functions/wooimport.php' );
require get_theme_file_path( '/inc/functions/coupons.php' );
require get_theme_file_path( '/inc/functions/wps-points.php' );
require get_theme_file_path( '/inc/functions/translate.php' );

require get_theme_file_path( '/inc/functions/user.php' );
require get_theme_file_path( '/inc/functions/sms.php' );

// Custom type
require get_theme_file_path( '/inc/post_types/brand.php' );
// require get_theme_file_path( '/inc/post_types/testimonial.php' );