<?php


function site_custom_rewrite_rules() { 

    add_rewrite_rule(
        'san-pham/([^/]+)/?$',
        'index.php?pagename=san-pham&pc_name=$matches[1]',
        'top' );
    
    flush_rewrite_rules();
}
add_filter('init', 'site_custom_rewrite_rules');