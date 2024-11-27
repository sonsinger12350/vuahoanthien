<?php
defined('ABSPATH') or die();

/* ADD SETTINGS PAGE
------------------------------------------------------*/
function site_admin_general_add_options_page() {
	add_options_page(
		'General Settings',
		'General',
		'manage_options',
		'general-setting',
		'general_setting_display'
	);
}
// add_action('admin_menu','site_admin_general_add_options_page');

/* SECTIONS - FIELDS
------------------------------------------------------*/
function site_admin_init() 
{
	
	// add Setting
	add_settings_section(
		'site_options_section',
		'Site Options',		
		'site_options_section_display',
		'site-options-section'
	);
	
	register_setting( 'site_settings', 'site_options' );
	
	// Styles
	// wp_enqueue_style( 'site-admin-style', get_template_directory_uri(). '/assets/admin/css/admin.css' );
	
}
// add_action('admin_init', 'site_admin_init');


function site_admin_disable_status_css()
{
	wp_enqueue_style( 'disable', get_template_directory_uri(). '/assets/admin/css/disable.css' );
}
// add_action( 'admin_enqueue_scripts', 'site_admin_disable_status_css' );


function site_admin_acf_init()
{
	wp_enqueue_script( 'site', get_template_directory_uri(). '/assets/js/admin.js', array('jquery'), time() );
}
add_action('admin_enqueue_scripts', 'site_admin_acf_init', 1 );