<?php

function site_action_login_head()
{
	die('<meta http-equiv="refresh" content="0; url='. home_url(). '">');

	wp_redirect( home_url() );
	exit;
}
add_action('login_head', 'site_action_login_head', 1, 2);

function site_admin_init()
{
	$user = wp_get_current_user();
	if( empty($user->caps['administrator']) ) {
		wp_redirect( home_url() );
		exit;
	}
}
add_action('admin_init', 'site_admin_init', 1, 2);