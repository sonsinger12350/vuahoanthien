<?php

if( !class_exists('Newsletter') ) {
    return '';
}

$newsletter = Newsletter::instance();

?>
<form method="post" action="<?php echo esc_attr($newsletter->get_subscribe_url()) ;?>">
<input type="hidden" name="nr" value="widget"/>
<div class="input-group mb-3">
<input type="email" name="ne" class="form-control" placeholder="Nhập email để nhận thêm nhiều ưu đãi" required aria-required>
<button class="btn btn-primary" type="submit">Gửi</button>
</div>
</form>