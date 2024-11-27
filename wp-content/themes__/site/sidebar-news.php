<div class="sidebar">
    <?php 
        global $post;
        $current_id = $post->ID;
        $args = array(
            'post_type'      => 'post',
            'post__not_in' => array( $current_id ),
            'posts_per_page' => 8,
        );
        $latest_posts = new WP_Query($args);
    ?>
    <div class="sidebar-head">
        <h3>Bài viết mới nhất</h3>
    </div>
    <div class="sidebar-body">
        <div class="news-sidebar">
            <?php while ( $latest_posts->have_posts()){ ?>
                <?php 
                    global $post;
                    $latest_posts->the_post(); 

                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                    $image = isset($image[0]) && !empty($image[0]) ? $image[0] : get_template_directory_uri().'/images/rectangle.png';
                ?>
                <div class="news-sidebar--item">
                    <div class="row">
                        <div class="col thumbnail">
                            <a href="<?php the_permalink(); ?>"><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>"></a>
                        </div>
                        <div class="col desc"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
                    </div>
                </div>
                <?php } 
            wp_reset_query();
            ?>
        </div>
    </div>
</div>