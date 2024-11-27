<?php
/**
 * Plugin Name: Import Excel for WooCommerce
 * Description: Support Import Excel for WooCommerce
 * Version: 1.0.0
 * Author: DevUI
 * Text Domain: customers
 * Requires at least: 5.2
 * Requires PHP: 7.0
 *
 * @package Customers
 */

defined( 'ABSPATH' ) || exit;

define('WI_DIR', __DIR__ );

require( 'Excel/import.php' );

function woo_importer_upload_form_handler()
{
    if ( isset( $_FILES['import'] ) )
    {
        // file_put_contents( ABSPATH . '/t-1.json', json_encode($_FILES['import']) );

        $file = $_FILES['import'];

        if( substr( strtolower($file['name']), -5) == '.xlsx' ) 
        {
            $file['name'] .= '.csv';

            $content = woo_importer_convert_to_csv($file['tmp_name']);

            if( $content!='' ) {
                file_put_contents($file['tmp_name'], $content);
                // file_put_contents(ABSPATH . '/' . $file['name'], $content);
                
                $_FILES['import'] = $file;
            }
        }
    }
}
add_action('admin_init', 'woo_importer_upload_form_handler');

function woo_importer_test()
{
    if ( isset( $_GET['test'] ) && $_GET['test'] == 'abs' )
    {
        $content = woo_importer_convert_to_csv( ABSPATH . '/wc-product-test.xlsx' );

        echo $content;

        exit();
    }
}
add_action('admin_init', 'woo_importer_test');

function woo_importer_csv_product_import_valid_filetypes( $filetypes = array() )
{
    // $filetypes['xlsx'] = "application/vnd.ms-excel";
    $filetypes['xlsx'] = "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";

    return $filetypes;
}
add_filter('woocommerce_csv_product_import_valid_filetypes', 'woo_importer_csv_product_import_valid_filetypes', 10, 1);