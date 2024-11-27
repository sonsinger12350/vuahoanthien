<?php
/**
 * https://api.abenla.com/api/Help/
 * 
 */

function site_sms_url( $name = '' )
{
    return 'https://api.abenla.com/api/' . $name;
}

function site_sms_api( $phone = '', $message = '' )
{
	$url = add_query_arg( array(
		'loginName' => 'ABN4P7T',
		'sign' => 'cfd74c2e73cf348e28daebaf357f4a2d',
		'serviceTypeId' => '30',
		'phoneNumber' => $phone,
		'message' => str_replace(' ','+', $message ),
		'brandName' => 'VUA H.THIEN',
		'callBack' => 'false',
		'smsGuid' => 1,
    ) , site_sms_url('SendSms') );
	
	/** @var array|WP_Error $response */
    $response = wp_remote_get( esc_url_raw( $url ), array(
		'timeout'     => 120,
		'httpversion' => '1.1',
	));

    $responseBody = wp_remote_retrieve_body( $response );
    $result = json_decode( $responseBody );
    if ( is_array( $result ) && ! is_wp_error( $result ) ) {
        // Work with the $result data
    } else {
        // Work with the error
    }

	return $result;
}

/** send order id */
function site_sms_send( $phone = '', $number = '' )
{
    $message = 'Ma don hang cua Quy Khach la 1234. Cam on Quy Khach da mua hang tai VuaHoanThien.com. Chi tiet LH 0813008839.';

	if( $number!='' ) {
		$message = str_replace('1234', $number, $message );
	}
	
    return site_sms_api( $phone, $message );
}

/** send new password */
function site_sms_send_password( $phone = '', $password = 'vht2022' )
{
    $message = 'VUA H.THIEN-Mat khau moi cua Quy khach tren website vuahoanthien.com la: ' . $password . '. Chi tiet LH 0813008839';

    return site_sms_api( $phone, $message );
}

function site_sms_testing()
{
	// site_sms_send( $phone = '0123456789' );
	site_sms_send_password( $phone = '0776628322', $code = 'Order0001' );
}
// add_action( 'wp_footer', 'site_sms_testing' );