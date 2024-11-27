<?php

/**
 * Register a Brand post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function site_custom_brand_init() 
{
	register_taxonomy( 'product-brand', 'product', array(
		'hierarchical' => true,
		'labels' => array(
			'name'                       => _x( 'Brands', 'taxonomy general name' ),
			'singular_name'              => _x( 'Brand', 'taxonomy singular name' ),
			'search_items'               => __( 'Search Brand' ),
			'popular_items'              => __( 'Popular Brand' ),
			'all_items'                  => __( 'All Brands' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => __( 'Edit Brand' ),
			'update_item'                => __( 'Update Brand' ),
			'add_new_item'               => __( 'Add New Brand' ),
			'new_item_name'              => __( 'New Brand Name' ),
			'separate_items_with_commas' => __( 'Separate Brand with commas' ),
			'add_or_remove_items'        => __( 'Add or remove Brand' ),
			'choose_from_most_used'      => __( 'Choose from the most used Brand' ),
			'not_found'                  => __( 'No Brand found.' ),
			'menu_name'                  => __( 'Brands' ),
		),
		
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'hierarchical'      	=> false,
    	'has_archive'           => false,
		'rewrite'               => true,
	     
	) );
}

add_action( 'init', 'site_custom_brand_init' );

