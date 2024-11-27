<div class="left-cat">
    <h2>DANH MỤC SẢN PHẨM</h2>
    <?php
        wp_nav_menu( array(
            'theme_location'    => 'left',
            'menu_class'        => 'list-unstyled clearfix',
            'container_class'   => 'left_cat_menu'
        ) );
        #list-item
    ?>
    <div class="left-cat_footer">
        <img src="<?php site_the_assets('img/icon-ship.png');?>" alt="">
        <span>
            GIAO HÀNG NHANH<br/>
            TRONG NGÀY
        </span>
    </div>
</div>