<?php
/**
 * Menu Manager Ultra
 *
 * @package JK
 * @author JK Plugins
 *
 * Plugin Name:       Menu Manager Ultra
 * Description:       A feature-packed menu management tool for WordPress
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           1.0.9
 * Author:            JK Plugins
 * Text Domain:       mm_ultra
 * Author URI:        https://www.jkplugins.com
 * Update URI:        https://www.jkplugins.com/wp-menu-manager/pro
 *
 * @package           jk-plugins
 */

require __DIR__ . '/vendor/autoload.php';


$this_dir_base_path = dirname(__FILE__). DIRECTORY_SEPARATOR;

require_once($this_dir_base_path . 'src' . DIRECTORY_SEPARATOR . 'util' . DIRECTORY_SEPARATOR . 'constants.php');
require_once($this_dir_base_path . 'src' . DIRECTORY_SEPARATOR . 'util' . DIRECTORY_SEPARATOR . 'menu-manager-array-utils.php');
require_once($this_dir_base_path . DIRECTORY_SEPARATOR . 'menu-manager-routes.php');

add_action( 'admin_menu', 'mmultra_init_menu' );

/**
 * Init Admin Menu.
 *
 * @return void
 */
function mmultra_init_menu() {

  add_theme_page(
    "Menu Manager Ultra",
    "Menu Manager Ultra",
    'edit_theme_options',
    'menu-manager-ultra',
    'mmultra_admin_page'
  );

}

/**
 * Init Admin Page.
 *
 * @return void
 */
function mmultra_admin_page() {
    require_once plugin_dir_path( __FILE__ ) . 'templates/app.php';
}

add_action( 'admin_enqueue_scripts', 'mmultra_admin_enqueue_scripts' );

/**
 * Enqueue scripts and styles.
 *
 * @return void
 */
function mmultra_admin_enqueue_scripts() {
    wp_enqueue_style( 'mm-ultra-style', plugin_dir_url( __FILE__ ) . 'build/main.css' );
    wp_enqueue_script(
        'mm-ultra-script', plugin_dir_url( __FILE__ ) . 'build/index.js',
        array('wp-element', 'wp-api-fetch'),
        '1.0.0', true
    );
}

/**
 * Create custom post type for revisions
 */
function mmultra_custom_post_types() {
  
  register_post_type( 'mmu_revision',
      array(
          'labels' => array(
              'name' => __( 'Menu Revisions' ),
              'singular_name' => __( 'Menu Revision' )
          ),
          'public' => false,
          'has_archive' => false,
          'show_in_rest' => true,

      )
  );
}

add_action( 'init', 'mmultra_custom_post_types' );

/**
 * Freemius integration code
 */
if ( ! function_exists( 'mmultra_fs' ) ) {
  // Create a helper function for easy SDK access.
  function mmultra_fs() {
      global $mmultra_fs;

      if ( ! isset( $mmultra_fs ) ) {
          // Include Freemius SDK.
          require_once dirname(__FILE__) . '/freemius/start.php';

          $mmultra_fs = fs_dynamic_init( array(
            'id'                  => '11982',
            'slug'                => 'menu-manager-ultra',
            'type'                => 'plugin',
            'public_key'          => 'pk_a6319041fb80c1585655b9be45407',
            'is_premium'          => true,
            'premium_suffix'      => 'Pro',
            // If your plugin is a serviceware, set this option to false.
            'has_premium_version' => true,
            'has_addons'          => false,
            'has_paid_plans'      => true,
            'trial'               => array(
                'days'               => 14,
                'is_require_payment' => false,
            ),
            'menu'                => array(
                'slug'           => 'menu-manager-ultra',
                'support'        => false,
                'parent'         => array(
                    'slug' => 'themes.php',
                ),
            ),
              // Set the SDK to work in a sandbox mode (for development & testing).
              // IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
              'secret_key'          => '02bde5c1c82defa33991f01dbad5f00e',
          ) );
      }

      return $mmultra_fs;
  }

  // Init Freemius.
  mmultra_fs();
  // Signal that SDK was initiated.
  do_action( 'mmultra_fs_loaded' );

  function mmultra_add_licensing_helper() {
    echo '<script type="text/javascript">
        (function(){
            window.MMU = {};
            window.MMU.upgrade_url = ' . json_encode(mmultra_fs()->get_upgrade_url()) . ';
            window.MMU.can_use_premium_code = ' . json_encode( mmultra_fs()->can_use_premium_code() ) . ';
        })();
    </script>';
  }
  
  add_action('admin_head', 'mmultra_add_licensing_helper');
}


