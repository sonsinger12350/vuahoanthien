<?php
/**
 * Template Name: Account
 *
 * Description: Twenty Twelve loves the no-sidebar look as much as
 * you do. Use this page template to remove the sidebar from any page.
 *
 * Tip: to remove the sidebar from all posts and pages simply remove
 * any active widgets from the Main Sidebar area, and the sidebar will
 * disappear everywhere.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

global $wp_query;

if( isset( $wp_query->query['lost-password'] ) ) 
{
  $reset_sent = sanitize_text_field( isset( $_GET['reset-link-sent'] ) ? $_GET['reset-link-sent'] : '' );
  
  if( $reset_sent == 'true' ) {
    get_template_part( 'parts/account/message');
  } else {
    get_template_part( 'parts/account/form');
  }
} else {

  get_template_part( 'parts/account/content');

}