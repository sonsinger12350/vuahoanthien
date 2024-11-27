<?php


$list = get_posts(array(
    'post_type' => 'testimonial',
    'numberposts' => 5,
));

if( count($list) ):
?>
<section class="section section-customers">
    <div class="container">
        <div class="section-content">
            <div class="list-items">
                <?php foreach( $list as $i => $p ):?>
                <div class="item">
                    <div class="item-desc">
                        <?php echo $p->post_content;?>
                    </div>
                    <div class="item-info">
                        <span class="item-image">
                            <img src="<?php echo site_get_template_directory_assets('images/ico-avatar.png');?>" alt="">
                        </span>
                        <span class="item-name"><?php echo $p->post_title;?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php 
endif;