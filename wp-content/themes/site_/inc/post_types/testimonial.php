<?php

/**
 * Register a Testimonial post type.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_post_type
 */
function site_custom_testimonial_init() 
{

	$labels = array(
		'name'               => _x( 'Testimonial', 'post type general name', 'site' ),
		'singular_name'      => _x( 'Testimonial', 'post type singular name', 'site' ),
		'menu_name'          => _x( 'Testimonial', 'admin menu', 'site' ),
		'name_admin_bar'     => _x( 'Testimonial', 'add new on admin bar', 'site' ),
		'add_new'            => _x( 'Add New', 'Testimonial', 'site' ),
		'add_new_item'       => __( 'Add New Testimonial', 'site' ),
		'new_item'           => __( 'New Testimonial', 'site' ),
		'edit_item'          => __( 'Edit Testimonial', 'site' ),
		'view_item'          => __( 'View Testimonial', 'site' ),
		'all_items'          => __( 'All Testimonial', 'site' ),
		'search_items'       => __( 'Search Testimonial', 'site' ),
		'parent_item_colon'  => __( 'Parent Testimonial:', 'site' ),
		'not_found'          => __( 'No Testimonials found.', 'site' ),
		'not_found_in_trash' => __( 'No Testimonials found in Trash.', 'site' )
	);

	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description', 'site' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => false,
		'rewrite'            => false,
		'taxonomies'    	 => array(),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 20, // 20: below Pages
		// 'menu_icon'      	 => 'dashicons-warning', // book https://developer.wordpress.org/resource/dashicons/#megaphone
		'supports'           => array( 'title', 'editor', 'revisions', 'thumbnail' ) 
	);

	register_post_type( 'testimonial', $args );
}
add_action( 'init', 'site_custom_testimonial_init' );