<?php

/**
 * Submiting Data
 */
function site_user_submiting()
{
    /*
     * Send SMS 
     */
    if ( isset( $_POST['site_lost_password'] ) && wp_verify_nonce( $_POST['site_lost_password'], 'site-lost-password' ) )
    {
        $code = 403;

        $user_login = sanitize_text_field( isset($_POST['user_phone']) ? $_POST['user_phone'] : '' );

        $user = get_user_by('login', $user_login );

        if( is_object($user) && isset($user->ID) ) {
            
            $new_pass = 'vht2022';

            reset_password( $user, $new_pass );

            $code = 200;
            
            site_sms_send_password( $user_login, $new_pass );
        }
        
        $ajax = intval( isset($_POST['ajax_reset']) ? $_POST['ajax_reset'] : -1 );
        if( $ajax>-1 ) {
            die(json_encode([ 'code' => $code ]));
            exit();
        }

        wp_redirect( home_url() . '?code='. $code );
        exit();
    }
    else if ( isset( $_POST['save-account-password-nonce'] ) && wp_verify_nonce( $_POST['save-account-password-nonce'], 'save_account_password' ) )
    {
        $msg = 200;

        $referer = sanitize_text_field( isset($_POST['_wp_http_referer']) ? $_POST['_wp_http_referer'] : home_url() );

        $pass_cur             = ! empty( $_POST['password_current'] ) ? $_POST['password_current'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$pass1                = ! empty( $_POST['password_1'] ) ? $_POST['password_1'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		$pass2                = ! empty( $_POST['password_2'] ) ? $_POST['password_2'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash

        $current_user = wp_get_current_user();

        if( $pass_cur == '' || $pass1 == '' || $pass2 =='' ) {
            $msg = 'Data null';
            wc_add_notice( __( 'Please fill out all password fields.', 'woocommerce' ), 'error' );
        } else if( $pass1 != $pass2 ) {
            $msg = 'Confirm password no match';
            wc_add_notice( __( 'New passwords do not match.', 'woocommerce' ), 'error' );
        } else if( wp_check_password( $pass_cur, $current_user->user_pass, $current_user->ID ) == false ) {
            $msg = 'Old password no match';
            wc_add_notice( __( 'Your current password is incorrect.', 'woocommerce' ), 'error' );
        } else {
            wp_update_user( array(
                'ID' => $current_user->ID,
                'user_pass' => $pass1
            ) );
            
            $msg = 'Change password success';
            
            wc_add_notice( __( 'Account details changed successfully.', 'woocommerce' ) );
            
            $referer = get_permalink();
        }

        wp_redirect( add_query_arg( 'msg', $msg, $referer ) );
        exit();
    }
}
add_action('wp', 'site_user_submiting', 1 );

function site_user_random_password( $password = '' )
{
    if ( isset( $_POST['site_lost_password'] ) ) {
        $password = '';
        for ( $i = 0; $i < 6; $i++ ) {
            $password .= rand( 0, 9 );
        }
    }
    
    return $password;
}
// add_filter('random_password', 'site_user_random_password');

/*
function site_manage_users_extra_tablenav()
{
?>
    <div class="alignleft actions">
        <select name="customer_type">
            <option value="">Select type</option>
            <option value="1">So 1</option>
            <option value="2">So 2</option>
        </select>
    </div>
<?php
}
// add_action('manage_users_extra_tablenav', 'site_manage_users_extra_tablenav');

function site_users_list_table_query_args( $args = array() )
{
    $args['meta_key'] = '';
    $args['meta_value'] = '';
    
    return $args;
}
// add_filter('users_list_table_query_args', 'site_users_list_table_query_args');
*/