<?php


// https://github.com/woocommerce/woocommerce/wiki/Product-CSV-Importer-&-Exporter

/**
 * Add the custom column to the exporter and the exporter column menu.
 *
 * @param array $columns
 * @return array $columns
 */
function site_wc_add_export_column( $columns ) {

	// column slug => column name
	$columns['brand'] = 'Brands';
	$columns['brands'] = 'Brands';
    
	return $columns;
}
add_filter( 'woocommerce_product_export_column_names', 'site_wc_add_export_column' );
add_filter( 'woocommerce_product_export_product_default_columns', 'site_wc_add_export_column' );

/**
 * Provide the data to be exported for one item in the column.
 *
 * @param mixed $value (default: '')
 * @param WC_Product $product
 * @return mixed $value - Should be in a format that can be output into a text file (string, numeric, etc).
 */
function site_wc_add_export_data( $value, $product ) {
	// $value = $product->get_meta( 'post_name', true, 'edit' );

    $terms = wp_get_post_terms( $product->get_id(), 'product-brand' );
    if( count($terms) ) {
        $value = implode(', ', array_column($terms, 'name') );
    }
	
	return $value;
}
// Filter you want to hook into will be: 'woocommerce_product_export_product_column_{$column_slug}'.
add_filter( 'woocommerce_product_export_product_column_brands', 'site_wc_add_export_data', 10, 2 );

/**
 * Register the 'Custom Column' column in the importer.
 *
 * @param array $options
 * @return array $options
 */
function site_wc_add_import_column( $options ) {

	// column slug => column name
	$options['brands'] = 'Brands';
	$options['brand'] = 'Brands';
    
	return $options;
}
add_filter( 'woocommerce_csv_product_import_mapping_options', 'site_wc_add_import_column' );

/**
 * Add automatic mapping support for 'Custom Column'. 
 * This will automatically select the correct mapping for columns named 'Custom Column' or 'custom column'.
 *
 * @param array $columns
 * @return array $columns
 */
function site_wc_add_column_to_mapping_screen( $columns ) {
	
	// potential column name => column slug
	$columns['Brands'] = 'brands';
	$columns['brands'] = 'brands';
	$columns['Brand'] = 'brands';
	$columns['brand'] = 'brands';

	return $columns;
}
add_filter( 'woocommerce_csv_product_import_mapping_default_columns', 'site_wc_add_column_to_mapping_screen' );

function site_wc_add_import_data( $object, $data ) {
	// $value = $product->get_meta( 'brands', true, 'edit' );

    // update_meta_value($product->get_id(), 'brands', $value );

	if ( ! empty( $data['brands'] ) ) {
		// $object->update_meta_data( 'custom_column', $data['custom_column'] );
        $tax   = 'product-brand';

        $names = explode(',', $data['brands']);

        $tags  = array();

		foreach ( $names as $name ) {
			$term = get_term_by( 'name', $name, $tax );

			if ( ! $term || is_wp_error( $term ) ) {
				$term = (object) wp_insert_term( $name, $tax );
			}

			if ( ! is_wp_error( $term ) ) {
				$tags[] = $term->term_id;
			}
		}

        if( count($tags)>0 ) {
            wp_set_post_terms( $object->get_id(), $tags, $tax, $append = false );
        }
	}

	return $object;
}
add_filter( 'woocommerce_product_import_pre_insert_product_object', 'site_wc_add_import_data', 10, 2 );