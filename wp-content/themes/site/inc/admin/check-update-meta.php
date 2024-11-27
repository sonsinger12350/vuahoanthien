<?php
/*
 * The Custom
 */
defined('ABSPATH') or die();

function admin_product_auto_check_update_meta()
{
    $pagenow    = isset($GLOBALS['pagenow']) ? sanitize_text_field($GLOBALS['pagenow']) : '';
    $post_type  = isset($_REQUEST['post_type']) ? sanitize_text_field($_REQUEST['post_type']) : '';
    $action     = isset($_REQUEST['action']) ? sanitize_text_field($_REQUEST['action']) : '';

    if (
        function_exists('site_wc_save_meta_sale_off') == false
        || $pagenow != 'edit.php' 
        || $post_type != 'product' 
        || post_type_exists($post_type) == false 
        || $action != 'update_safe_off'
    ) return;

    echo '<div style="max-width: 900px; margin: 0 auto;">';
    echo '<h2>Post type: '. $post_type .'</h2>';
    
    $paged     = isset($_REQUEST['paged']) ? intval($_REQUEST['paged']) : -1;
    $limit     = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 50;

    if( $paged == -1 ) {

        echo '<p><a href="'. admin_url('edit.php?post_type=' . $_REQUEST['post_type'] . '&action=update_safe_off&limit=' . $limit . '&paged=' . ($paged + 1)) .'">Start</a></p>';
        
        die();
    }

    // The Query.
    $the_query = new WP_Query([
        'posts_per_page'    => $limit,
        'post_type'         => $post_type,
        'post_status'       => 'any',
        'offset'            => $paged * $limit,
        'orderby'           => 'ID',
        'order'             => 'DESC'
    ]);

    echo '<p>Page : ' . ($paged+1) . '/'. $the_query->max_num_pages .'</p>';
?>
    <?php if ($the_query->have_posts()) : ?>
        <ul>
            <?php
            foreach ($the_query->posts as $p) {
                $html = '<b>' . $p->ID . ')' . $p->post_title . '</b>{status:' . $p->post_status . ',';
                
                $html .= ',sale_off:{before:' . get_post_meta($p->ID, 'sale_off', true);
                
                site_wc_save_meta_sale_off($p->ID, $p);

                $html .= ',after:' . get_post_meta($p->ID, 'sale_off', true).'}';

                echo '<li>' . $html . '</li>';
            }
            ?>
        </ul>
        <?php if ($paged < $the_query->max_num_pages-1) : ?>        
        <script>
            setTimeout(function() {
                location.href = '<?php echo admin_url('edit.php?post_type=' . $_REQUEST['post_type'] . '&action=update_safe_off&limit=' . $limit . '&paged=' . ($paged + 1)); ?>';
            }, 100);
        </script>
        <?php endif; ?>
    <?php else : ?>
        <h3 align=center>The end!</h3>
    <?php endif; ?>
<?php
    echo '</div>';
    die();
    exit();
}
add_action('admin_init', 'admin_product_auto_check_update_meta');