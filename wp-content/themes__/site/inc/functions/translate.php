<?php

function site_translate_points_and_rewards_for_woocommerce( $translation = '', $text = '', $domain = '' )
{
    if( $domain != 'points-and-rewards-for-woocommerce' ) {
        return $translation;
    }

    if( $translation === $text && substr(get_locale(), 0, 2 ) == 'vi' ) {
        // file_put_contents( ABSPATH. '/a_t.json', json_encode([ $translation, $text, $domain ]) );

        return site_translate_to_vietnamese( $text );
    }
    
    return $translation;
}
add_filter( 'gettext', 'site_translate_points_and_rewards_for_woocommerce', 10, 3 );

function site_translate_to_vietnamese( $text = '', $domain = '' )
{
    $list = array(
        "Cart Discount" => 'Điểm',
        " Point Log Table" => 'Điểm tích lũy',
        "Per Currency Spent Points" => 'Điểm đã dùng', // Mỗi tiền tệ đã tiêu điểm
        "Date" => 'Ngày',
        "Point Status" => 'Tình trạng',
        "Coupon Creation" => 'Điểm tích lũy',
        "Deducted Points earned on Order Total on Order Cancellation" => 'Số điểm khấu trừ kiếm được trên tổng số đơn đặt hàng khi hủy đơn hàng',
        "Points Log Table With Points Earned Each time on Order Total" => 'Bảng nhật ký điểm với số điểm kiếm được mỗi lần trên tổng số đơn đặt hàng',
        "Total Points" => 'Tổng điểm tích lũy: ',
        "No Points Generated Yet." => 'Bạn chưa có điểm tích lũy.',
        "My Points" => 'Điểm của bạn',
        "View Point Log" => 'Xem nhật ký điểm',
    );
    
    if( isset($list[$text]) ) {
        return $list[$text];
    }

    return $text;
}