<?php


// 
if( function_exists('the_field') == false ){
	// echo '<p>Please install ACF plugin!</p>';
}

function site_the_field($key = '', $term_alias = '', $default = null )
{
	if( function_exists('the_field') ){
		return the_field($key, $term_alias, $default );
	}

	echo site_get_field( $key, $term_alias, $default );
}

function site_get_field($key = '', $term_alias = '', $default = '' )
{
	if( function_exists('get_field') ){
		return get_field($key, $term_alias, $default );
	}

	if( $key  == '' || $term_alias  == '' ) return $default;
	
	$value = $default;		
	
	$term_alias = explode('_', $term_alias ); 
	
	if( count($term_alias) == 2 ){			
		$term_id = (int) end($term_alias);
	} else {
		$term_id = (int) $term_alias[0];
	}
			
	$single = is_array($default) == false;
	
	if( $term_id> 0 ){
		$value = get_term_meta($term_id, $key, $single);
	}
	
	if( is_numeric($default) ){
		$value = intval($value);
	}
	
	return $value;
}

add_action('acf/save_post', 'site_acf_save_post_fields');
function site_acf_save_post_fields( $post_id )
{
	$post_type = get_post_type( $post_id );
	if( $post_type == 'product' ) {

		$fields = get_field_objects( $post_id );

		foreach( $fields as $name => $field ) {
			if( $field['name'] == 'is_combo' ) {
				continue;
			}
			
			if( $field['type'] == 'checkbox' ) {
				site_acf_save_post_field( $post_id, $name );
			}
		}

		site_acf_save_post_combo_field( $post_id );
	}
	/*
	else if( $post_type == 'nav_menu_item' && isset($_POST['menu-item-acf'][$post_id]) ) {
		$data = $_POST['menu-item-acf'][$post_id];

		file_put_contents( ABSPATH . '/a_fields.json', json_encode( $_POST['menu-item-acf'] ) );

		$fields = get_field_objects( $post_id );

		foreach( $fields as $name => $field )
		{
			$key = $field['key'];

			$value = sanitize_text_field( isset($data[$key]) ? $data[$key] : '' );
			if( $value!='' )
			{
				update_post_meta( $post_id, $field['name'], $value );
				update_post_meta( $post_id, $field['_name'], $key );
			}
		}
	}
	*/

	// if( $post_type == 'nav_menu_item' ) {
	// 	file_put_contents( ABSPATH . '/a_posts_2.json', json_encode( $_POST ) );
	// }
}

function site_acf_save_post_field( $post_id, $key ) 
{
    // $field = get_field_object($key);

    // if( empty($field['wrapper']) || empty($field['wrapper']['class']) || $field['wrapper']['class']!='query-list' ) return;
    
    $meta_key = $key . '_list';
    
    // Check the new value of a specific field.
    $checkbox = get_field($key, $post_id);
    if( is_array($checkbox) && count($checkbox)>0 ) {
        $list = get_post_meta( $post_id, $meta_key );
        
        foreach( $list as $i => $value ) {
            $index = array_search( $value, $checkbox );
            if( $index > -1 ) {
                unset($checkbox[$index]);
            } else {
                delete_post_meta( $post_id, $meta_key, $value );
            }
        }

        foreach( $checkbox as $value ) {
            add_post_meta( $post_id, $meta_key, $value['value'] );
        }
    } else {
        delete_post_meta( $post_id, $meta_key );
    }
}

function site_acf_get_fields( $search = 'all', $group = 0 )
{
	$posts = get_posts(array(
		'post_type' => 'acf-field' . ( $group ? '-group' : '' ),
		's' => $search
	));

	
	if( count($posts) ) {
		$postss = get_posts(array(
			'post_type' => 'acf-field',
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
		    'order' => 'ASC',
		    'post_parent' => $posts[0]->ID
		));

		//var_dump($postss);

		$list = [];

		foreach( $postss as $p ){
			$params = maybe_unserialize($p->post_content);
			
			// var_dump( $p->post_title );
			// var_dump( $p );
			// $p->choices = $params['choices'];

			$params['title'] = $p->post_title;
			$params['name'] = $p->post_name;
			$params['excerpt'] = $p->post_excerpt;
			$list[ $p->ID ] = $params;
		}

		return $list;
	}

	return array();
}

function site_acf_save_post_combo_field( $post_id )
{
	$combo_id = (int) get_field('combo_id', $post_id );
	if( $combo_id>0 ){
		$combo_products = get_field('combo_products', $combo_id);

		$do_delete = true;
		foreach( $combo_products as $p ) {
			if( $post_id == $p->ID ) {
				$do_delete = false;
				break;
			}
		}

		if( $c == 0 ) {
			delete_field('combo_id', $post_id);
		}
 	} else {
		$combo_products = get_field('combo_products', $post_id );
		if( is_array($combo_products) && count($combo_products)>0 ) {
			foreach( $combo_products as $p ) {
				update_field('combo_id', $post_id, $p->ID );
			}
			update_field('is_combo', 1, $post_id );
		}
	}
}

function my_acf_render_field( $field ) 
{
    echo '<p>Some extra HTML.</p>';
}
// Apply to all fields.
// add_action('acf/render_field', 'my_acf_render_field');


add_action('acf/include_field_types', function(){
    require get_theme_file_path( '/inc/functions/class-acf-field-repeater.php' );
}, 10);