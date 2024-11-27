<?php

$page_on_front = (int) get_option( 'page_on_front', 0 );
$highlight = get_field('highlight', $page_on_front);
if( $highlight == '' ) {
    $highlight = 'Mua hàng trực tuyến giá tốt - miễn phí giao hàng tại thành phố Hồ Chí Minh';
}

?>
<div class="highlight">
<?php echo $highlight;?>
</div>