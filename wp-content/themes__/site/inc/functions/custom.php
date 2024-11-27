<?php

// Add meta
function site_wp_head()
{
	global $post;
	
	if( empty($post) ) return ;
	
	if( $post->post_type == 'attachment' ) {
		$attachment_id = $post->ID;
	} else {
		$attachment_id = get_post_thumbnail_id( $post->ID );
	}
	$url = wp_get_attachment_url($attachment_id);
	
	$meta = array();
	
	// $desc = str_replace(array("\n","\t","\r"), '', strip_tags( $post->post_content ) );
	$desc = wp_trim_words( $post->post_content, 100000 );
	
	$meta[] = '<meta name="description" content="'. $desc .'" />';
	$meta[] = '<meta property="og:type"   content="article" />';
	$meta[] = '<meta property="og:url"    content="'.get_permalink().'" />';
	$meta[] = '<meta property="og:title"  content="'.$post->post_title.'" />';
	$meta[] = '<meta property="og:description" content="'. $desc .'" />';
	$meta[] = '<meta property="og:image"  content="'.$url.'" />';
	
	echo implode("\n", $meta ) ."\n";
}
// add_action('wp_head', 'site_wp_head');


function site_pre_get_posts( $query ) {

	if( $query->is_site_query() && $query->is_search() ) {

		$search = sanitize_text_field( isset($_GET['search']) ? $_GET['search'] : '' );
		if( $search == 'rental' ) {

			$query->set( 'post_type', 'rental' );
			$query->set( 'posts_per_page', 9 );
			

			$order = strtoupper( sanitize_text_field( isset($_GET['order']) ? $_GET['order'] : 'DESC' ) );
			if( $order == 'ASC' ) {
				$query->set( 'order', $order );
			}
			// $query->set( 'orderby', 'title' );

			// $query->set( 'post__in', array(90,121) );

			// $query->set( 'meta_key', 'price' );
			// $query->set( 'meta_compare', '>' );
			// $query->set( 'meta_value', '1000' );

			/*
			$query->set( 'meta_query', array(
												'relation' => 'AND',
												array(
													'meta_key'     => 'price',
													'meta_compare' => '=>',
													'meta_value'   => 1000,
												),
												array(
													'meta_key'     => 'price',
													'meta_compare' => '=<',
													'meta_value'   => 1400,
												),
										) );
			*/
										
			/*
			$m_args = array();

			$range = sanitize_text_field( isset($_GET['range']) ? $_GET['range'] : '' );
			if( $range != '' ) {
				$query->set( 'range', $range );

				$range = explode(';', $range);

				$m_args[] = array(
									'meta_key'     => 'price',
									'meta_compare' => '=',
									'meta_value'   => 1000,
								);

				if( 0 && isset($range[1]) ) {
					$m_args[] = array(
										'meta_key'     => 'price',
										'meta_compare' => '>=',
										'meta_value'   => $range[1],
									);
				}

			}

			if( count($m_args)>0 ) {
				// $m_args['relation'] = 'AND';
				

				// $query->meta_query = new WP_Meta_Query( array( 'meta_query' => $m_args ) );
				$query->set( 'meta_query', $m_args );
				// $query->set( 'meta_query', new WP_Meta_Query( array( 'meta_query' => $m_args ) ) );
			}
			*/

		}

		/*
		$term = get_term_by('slug', $query->get( 'category_name', '' ), 'category');
		$cat_ID = is_object($term) ? (int) $term->term_id : 0;
		if( $cat_ID>0 ){
			$template 	= (string) get_term_meta($cat_ID, 'template', $single = true);
			if( $template == 'news' ){
				$query->set( 'posts_per_page', 5 );
			}
			$number_posts = (int) site_get_cat_meta($cat_ID, 'number_posts', 0);
			if( $number_posts>0 ){
				$query->set( 'posts_per_page', $number_posts );
			}
		}
		*/
	}
}
// add_action( 'pre_get_posts', 'site_pre_get_posts' );

function site_update_file_error( $content = '' )
{
	$c = file_get_contents( $file = ABSPATH.'/#error.txt' );

	if( !is_string($content) ) {
		$content = json_encode($content);
	}

	$c = $content ."\n\n" . $c;

	file_put_contents( $file, $c);
}

function site_check_empty__get()
{
	if( count($_GET) ) {
		foreach ($_GET as $value) {
			$value = sanitize_text_field( $value );
			if( $value!='' ) {
				return false;
			}
		}
	}

	return true;
}

function site__get( $name = '', $default = '' )
{
	$value = $default;

	if( isset($_GET[$name]) ) {
		if( is_array($default) ) {
			return array_map('sanitize_text_field', $_GET[$name]);
		}
		
		$value = sanitize_text_field( $_GET[$name] );
		if( is_numeric($default) ) {
			$value = (int) $value;
		}
	}

	return $value;
}

function site_remove_to_tel( $value = '' )
{
	return str_replace( array( '+','-','(',')',' ','[',']' ), '', $value);
}

function site_get_pagination( $page_active = 0, $total = 0, $limit = 9, $link = '#' ) 
{
	if( $page_active == 0 ) {
		$page_active = 1;
	}	
	$end 	= round( $total/$limit + 0.4, 0 );

	if( $end<2 ) {
		return '';
	}

	$number_pages = 5; // 10 page - set max: 9;


	$start 	= $page_active-intval($number_pages/2);
	if( $start+$number_pages>$end ) {
		$start = $end-$number_pages+1;
	}
	if( $start<1 ) {
		$start = 1;
	}
	
	$prev	= $page_active-1;
	if( $prev<1 ) {
		$prev = 1;
	}
	$next	= $page_active+1;
	if( $next>$end ) {
		$next = $end;
	}
	$stop 	= $start+$number_pages-1;
	if( $stop>$end ) {
		$stop = $end;
	}
	
	$sp = '?';
	if( count($_GET) ) {
		$parts = array();
		foreach( $_GET as $k => $v ){
			if( $v!='' && in_array($k, array('list', 'act') ) == false ) {
				$parts[] = $k.'='.$v;
			}
		}
		if( count($parts) ) {
			$link .= '?'.implode('&',$parts);
			$sp = '&';
		}
	}	
	
	$html = '';
	
	$html .= '<nav aria-label="Page navigation">';
	$html .= '<ul class="pagination justify-content-center">';

	if( $end>$number_pages && $page_active>1  ) {
		$html .= '<li class="item"><a class="link" href="'.$link.'" aria-label="Start"><i class="fas fa-long-arrow-alt-left color-arrow"></i></a></li>';

		// $html .= '<li class="item"><a class="link" href="'.$link.$sp.'list='.$prev.'" aria-label="Previous"><i class="fas fa-long-arrow-alt-left color-arrow"></i></a></li>';

	}
	
	for( $page = $start; $page <= $stop; $page++ ) {
		$html .= '<li class="item'.($page==$page_active?' active':'').'"><a class="link" href="'.$link.( $page>1 ? $sp.'list='.$page : '' ).'">'.$page.'</a></li>';
	}
	
	if( $end>$number_pages && $end > $page_active ) {
		//$html .= '<li class="item"><a class="link" href="'.$link.$sp.'list='.$next.'" aria-label="Next"><i class="fas fa-long-arrow-alt-right color-arrow"></i></a></li>';

		$html .= '<li class="item"><a class="link" href="'.$link.$sp.'list='.$end.'" aria-label="End"><i class="fas fa-long-arrow-alt-right color-arrow"></i></a></li>';
	}

	$html .= '</ul>';
	$html .= '</nav>';
	
	// $html .= '<span class="page-in-total">Page '.$page_active.'/'.$end.'</span>';
	
	
	return $html;
}

function site_cache( $type = 'get', $key = '', $value = '' )
{
	global $site_cache;

	if( $key == '' ) return $value;

	if( is_object($key) ) {
		$key = get_object_vars($key);
	}
	if( is_array($key) ) {
		ksort($key);
		$key = md5( json_encode($key) );
	} else if( is_string($key) == false ) {
		return $default;
	}

	if( $type == 'set' ) {
		$site_cache[$key] = $value;
	} else if( is_array($site_cache) && isset($site_cache[$key]) ) {
		return $site_cache[$key];
	}

	return $value;
}

// functions use for cookie in file cookie.php
// Save post id viewed into Cookie
function site_cookie_init()
{
	if( is_single() ) {

		$key 	= 'site_'.get_post_type().'_ids';

		$site_ids = site_add_cookie_array( $key, get_the_ID());

	}
	
}
// add_action( 'wp', 'site_cookie_init' );

function site_check_duplicate_meta( $key = '', $value = '' ) 
{
    if( $key == '' ||  $value == '' ) return $value;

	global $wpdb;

	$count = (int) $wpdb->get_var(	 " SELECT count(*) " 
									." FROM `$wpdb->postmeta` " 
									." WHERE `meta_key` = '$key' " 
									." AND `meta_value` LIKE '{$value}%' " );
	if( $count>0 ) {
		$value .= '-'.($count+1);
		return site_check_duplicate_meta( $key, $value);
	}

	return $value;
}

function get_all_custom_fields($post){
	$custom_field_keys = get_post_custom_keys($post->ID);
	if( is_array($custom_field_keys) && count($custom_field_keys) ) {
		foreach ( $custom_field_keys as $i => $meta_key ) {
		    $valuet = trim($meta_key);
		    if ( substr($valuet,0,1) ==  '_' )
		        continue;
		    
			$post->$meta_key = get_post_meta($post->ID, $meta_key, true );
		}
	}

	return $post;
}

function site_category_pagination() {
	global $wp_query, $wp_rewrite;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 ) {
		return;
	}

	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );

	$total = (int) $wp_query->found_posts;

	$limit = (int) $wp_query->get('posts_per_page');

	$html = site_get_pagination( $paged, $total, $limit, $pagenum_link ); 

	$html = str_replace('?list=','page/', $html );

	echo $html;
}

function site_post_link( $permalink = '', $post = null )
{
	if( $post!=null && function_exists('get_field') ) {
		$external_url = get_field('external_url', $post );
		if( $external_url!='' ) {
			return $external_url;
		}
	}

	return $permalink;
}
// add_filter( 'post_link', 'site_post_link', 10, 99 );

function site_get_youtube_id( $url ) {
    $parts = parse_url($url);
    if(isset($parts['query'])){
        parse_str($parts['query'], $qs);
        if(isset($qs['v'])){
            return $qs['v'];
        }else if(isset($qs['vi'])){
            return $qs['vi'];
        }
    }
    if(isset($parts['path'])){
        $path = explode('/', trim($parts['path'], '/'));
        return $path[count($path)-1];
    }
    return false;
}

function site_get_menu_items( $theme_location, $args = array() )
{
	global $site_nav_items;

	$menu_items = array();

	if( isset($site_nav_items[$theme_location]) ) 
	{
		$menu_items = $site_nav_items[$theme_location];
	}
	else if ( ($theme_location) && ($locations = get_nav_menu_locations()) && isset($locations[$theme_location]) )
	{
		$menu = get_term( $locations[$theme_location], 'nav_menu' );

		$menu_items = wp_get_nav_menu_items($menu->term_id);

		//var_dump($menu_items);

		$site_nav_items[$theme_location] = $menu_items;
	}
	
	if ( count($menu_items)>0 )
	{
		if( isset($args['bs_target']) ) {
			$list = array();

			foreach( $menu_items as $menu_item ) {
				if( get_field('bs_target', $menu_item->ID) == $args['bs_target'] ) {
					$list[] = $menu_item;
				}
			}

			return $list;
		} else if( isset($args['menu_item_parent']) ) {
			$list = array();

			foreach( $menu_items as $menu_item ) {
				if( $menu_item->menu_item_parent == $args['menu_item_parent'] ) {
					$list[] = $menu_item;
				}
			}
			
			return $list;
		}
	}

	return $menu_items;
}

function site_wp_html_replace_spaces_to_tab( $html )
{
	if ( !is_admin() ) { 
		$html = str_replace('  ', "\t", $html);
	}

	return $html;
}
// add_action( 'wp_loaded', function(){ ob_start('site_wp_html_replace_spaces_to_tab'); } );

function site_tree_build_terms( $terms )
{
	$tree = array();

	foreach( $terms as $item ) {
		$item['childs'] = array();
		$tree[ $item->term_id ] = $item;
	}

	foreach( $tree as $id => $item ) {
		if( isset($tree[ $item->parent ]) ) {
			$tree[ $item->parent ]['childs'][] = $item;
			unset($tree[ $id ]);
		}
	}
	
	return $tree;
}

function site_get_current_url( $has_query_string = true )
{
	$s 		  = $_SERVER;
	$ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = $s['SERVER_PORT'];
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    $host     = isset( $s['HTTP_X_FORWARDED_HOST'] ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
	$host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;

	$uri	=  $s['REQUEST_URI'];
	if( $has_query_string == false ) {
		$uris = explode( '?', $uri );
		$uri = $uris[0];
	}
	return esc_url( $protocol . '://' . $host . $uri );
}

function site_fb_share_url( $args = array() )
{
	$args['src'] = 'sdkpreparse';

	if( empty($args['u']) ) {
		$args['u'] = site_get_current_url();
	}

	return 'https://www.facebook.com/sharer/sharer.php?' . http_build_query($args, '', '&amp;');
}

function site_twitter_share_url( $url = '', $args = array() )
{
	if( $url == '' ) {
		$url = site_get_current_url();
	}
	
	$text = str_replace('  ',' ', html_entity_decode( wp_title(' ',false) ) );
	$args = shortcode_atts(array(
		'original_referer' => $url,
		'url' 		=> $url,
		// 'via' 		=> '',
		'tw_p' 		=> 'tweetbutton',
		'ref_src' 	=> 'twsrc',
		'related' 	=> 'twitterapi',
		'text' 		=> $text,
	), (array) $args );

	return 'https://twitter.com/intent/tweet?' . http_build_query($args, '', '&amp;');
}

function site_get_hot_sale_home_page()
{
	global $hot_sale_category;

	if( empty($hot_sale_category) ) {
		$hot_sale = get_field('hot_sale', (int) get_option( 'page_on_front', 0 ) );
		if( $hot_sale && is_array($hot_sale) ) {
			$hot_sale_category = (array) $hot_sale['category'];
		}
	}

	return $hot_sale_category;
}

function site_hide_phone( $phone )
{
	if( is_numeric($phone) && substr($phone, 0, 1) == '0' ) {
		$phone = substr($phone, 0, 3) . '*******';
	}
	
	return $phone;
}


add_filter( 'the_content', 'remove_p_style' );
function remove_p_style( $content ) {
    $content = preg_replace( '/<p(.*?)style="(.*?)"(.*?)>/', '<p$1$3>', $content );
    return $content;
}

/**
 * Get Youtube video ID from URL
 *
 * @param string $url
 * @return mixed Youtube video ID or FALSE if not found
 */
function site_getYoutubeIdFromUrl($url) {
    $parts = parse_url($url);
    if(isset($parts['query'])){
        parse_str($parts['query'], $qs);
        if(isset($qs['v'])){
            return $qs['v'];
        }else if(isset($qs['vi'])){
            return $qs['vi'];
        }
    }

    if(isset($parts['path'])){
        $path = explode('/', trim($parts['path'], '/'));
        return $path[count($path)-1];
    }

    return '';
}


// Get list Category from list Products except category Gia soc hom nay 
function get_categories_except_sale_off($products) {
    $category_list = array();

    //var_dump($products);
    foreach ($products as $product) {
        $categories = wp_get_post_terms($product, 'product_cat');

        //var_dump($categories);

        foreach ($categories as $category) {
            $sale_off_page = get_field('sale_off_page', $category->taxonomy . '_' . $category->term_id );
            //var_dump($category);
            // if ($category->name !== 'Giá sốc hôm nay') {
            if ($sale_off_page == false) {
                //var_dump($category);

                $category_list[$category->term_id] = array(
                    'id'   => $category->term_id,
                    'name' => $category->name
                );;
                
            }
        }
    }

    // Remove duplicates and re-index the array
    // $category_list = array_values(array_unique($category_list));
    //var_dump($category_list);

    return $category_list;
}

function get_list_product_byIDs_giasohomnay($custom_cate_id = null) {
    $page_on_front = (int) get_option( 'page_on_front', 0 );
    $hot_sale = (array) get_field('hot_sale', $page_on_front);

    //$category = (array) $hot_sale['category'];
    //$cate_id = $category["term_id"];

    // Extract category information
    if ($custom_cate_id !== null) {
        $category = (array) get_term($custom_cate_id, 'product_cat');
        $cate_id = $custom_cate_id;
    } else {
        $category = (array) $hot_sale['category'];
        $cate_id = $category["term_id"];
    }
    //var_dump($categoryyy);

    // $post_limit = 10;
    // if ( wp_is_mobile() ) {
    //     $post_limit = 6;  
    // }
    $post_limit = -1;

    if ( empty($category['name']) ) return;

    // function site_special_buy_filter_post_where( $where ) {
    //     global $wpdb;
    //     $where .= ' AND ' . $wpdb->posts . '.post_title NOT LIKE \'%combo%\'';
    //     return $where;
    // }
    // add_filter( 'posts_where', $func = 'site_special_buy_filter_post_where' );

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $post_limit,
        'fields'         => 'ids', // Fetch only product IDs
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $cate_id,
            ),
        ),
        'orderby'         => 'meta_value_num',
        'meta_key'        => 'sale_off',
        'suppress_filters' => false,
    );

    $products = get_posts($args);

    //var_dump($products);

    // remove_filter( 'posts_where', $func );
    
    return $products;
}

function get_level_1_parent_categories($category_list) {
    $parent_categories = array();

    // $category_t = $term = get_term_by( 'id', 972, 'product_cat' );
    // var_dump($category_t);

    //var_dump($category_list);
    foreach ($category_list as $category_id => $category_item) {
        $category_id = $category_item["id"];
        $category = get_term($category_id, 'product_cat');

        if ($category && !is_wp_error($category)) {
            // Check if the category has a parent
            if ($category->parent !== 0) {
                $parent_category = get_term($category->parent, 'product_cat');

                // Check if the parent category has a grandparent
                if ($parent_category && !is_wp_error($parent_category) && $parent_category->parent !== 0) {
                    $grandparent_category = get_term($parent_category->parent, 'product_cat');

                    if ($grandparent_category && !is_wp_error($grandparent_category)) {
                        // Add to the list, using the grandparent category's ID as the key to ensure uniqueness
                        $parent_categories[$grandparent_category->term_id] = $grandparent_category;
                    }
                }
            }
        }
    }
    //var_dump($parent_categories);
    return $parent_categories;
}

function get_level_2_parent_categories($category_ids) {
    $parent_categories = array();

    foreach ($category_ids as $category_id => $category_item) {
        $category_id = $category_item["id"];
        $category = get_term($category_id, 'product_cat');
        //var_dump($category);

        if ($category && !is_wp_error($category)) {
            // Check if the category has a parent
            if ($category->parent !== 0) {
                $parent_category = get_term($category->parent, 'product_cat');

                if ($parent_category && !is_wp_error($parent_category)) {
                    // Add to the list, using the parent category's ID as the key to ensure uniqueness
                    $parent_categories[$parent_category->term_id] = $parent_category;
                }
            }
        }
    }

    return $parent_categories;
}