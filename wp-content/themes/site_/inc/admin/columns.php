<?php


if( is_admin() ) {

	function site_admin_post_column( $column, $post_id ) {
	    if ( $column == 'slug' ) {
	    	$p = get_post( $post_id );

            echo $p->post_name; 
	    } else if ( $column == 'modified' ) {
            echo get_the_date( 'Y-m-d H:i:s', $post_id );
	    }
	}
	// add_action( 'manage_pages_custom_column' , 'site_admin_post_column', 10, 2 );
	// add_action( 'manage_rental_posts_custom_column' , 'site_admin_post_column', 10, 3 );
	// add_action( 'manage_sell_posts_custom_column' , 'site_admin_post_column', 10, 3 );
	// add_action( 'manage_posts_custom_column' , 'site_admin_post_column', 10, 3 );

	function site_admin_add_post_column($columns) {

		/*
		if( is_array($columns) && count($columns) ) {
			$new = array();
			foreach ( $columns as $key => $value ) {
				$new[$key] = $value;
				if( $key == 'title' ) {
					$new['slug'] = __('post_modified');
				}
			}
			$columns = $new;
		}
		*/

		$columns = array_merge( $columns, array('modified' => __('Modified')) );

	    return $columns;
	}
	// add_filter( 'manage_pages_columns' , 'site_admin_add_post_column');
	// add_filter( 'manage_rental_posts_columns' , 'site_admin_add_post_column');
	// add_filter( 'manage_sell_posts_columns' , 'site_admin_add_post_column');
	// add_filter( 'manage_posts_columns' , 'site_admin_add_post_column');

	function site_admin_set_custom_edit_post_columns($columns) {
	    unset( $columns['author'] );

	    return $columns;
	}
	// add_filter('posts_clauses', 'site_admin_set_custom_edit_post_columns' );

    //Adds Custom Column To Users List Table
    function site_admin_add_user_id_column($columns) {
        $new = [];

        foreach( $columns as $key => $title ) {
            $new[$key] = $title;
            if( $key == 'email' ) {
                $new['customer_type'] = 'Type';
            }
        }
        
        return $new;
    }
    add_filter('manage_users_columns', 'site_admin_add_user_id_column');

    //Adds Content To The Custom Added Column
    function site_admin_show_user_id_column_content($value, $column_name, $user_id) 
    {
        // $user = get_userdata( $user_id );

        if ( $column_name == 'customer_type' )
        {
            $value = get_user_meta( $user_id, $column_name, true );

            $types = site_wc_user_customer_types();
            
            return isset($types[ $value ]) ? $types[ $value ] : '';
        }
        
        return $value;
    }
    add_filter('manage_users_custom_column',  'site_admin_show_user_id_column_content', 10, 3);
}