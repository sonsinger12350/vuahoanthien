<?php

namespace MenuManagerUltra\API\Endpoints;

use  MenuManagerUltra\API\ReturnValue ;
use  MenuManagerUltra\lib\Post\PostDataFormatter ;
use  MenuManagerUltra\lib\Constants ;
class Posts extends \WP_REST_Controller
{
    public function register_routes()
    {
        register_rest_route( Constants::ROUTE_BASE, '/posts/search', array(
            'methods'             => 'POST',
            'callback'            => [ $this, 'searchPosts' ],
            'permission_callback' => function ( $request ) {
            return current_user_can( Constants::ENDPOINT_PERMISSION_DEFAULT );
        },
        ) );
        register_rest_route( Constants::ROUTE_BASE, '/posts/types', array(
            'methods'             => 'GET',
            'callback'            => [ $this, 'getPostTypesAsObjects' ],
            'permission_callback' => function ( $request ) {
            return current_user_can( Constants::ENDPOINT_PERMISSION_DEFAULT );
        },
        ) );
        register_rest_route( Constants::ROUTE_BASE, '/post/(?P<post_id>\\d+)/get', array(
            'methods'             => 'GET',
            'callback'            => [ $this, 'getPost' ],
            'args'                => array(
            'post_id' => array(
            'validate_callback' => function ( $param, $request, $key ) {
            return is_numeric( $param );
        },
        ),
        ),
            'permission_callback' => function ( $request ) {
            return current_user_can( Constants::ENDPOINT_PERMISSION_DEFAULT );
        },
        ) );
    }
    
    /*
     * Used as a filter for WP_Query to search title & content because certain plugins/hooks
     * can upset the use of the "s" parameter
     */
    public function applySearchParamToQuery( $where, $wp_query )
    {
        global  $wpdb ;
        if ( $search_keys = $wp_query->get( 'mmu_search_query' ) ) {
            $where .= " AND\n        (\n            {$wpdb->posts}.post_title LIKE '%" . esc_sql( $search_keys ) . "%'\n        OR\n            {$wpdb->posts}.post_content LIKE '%" . esc_sql( $search_keys ) . "%'\n        )";
        }
        return $where;
    }
    
    public function searchPosts( $params )
    {
        add_filter(
            'posts_where',
            [ $this, 'applySearchParamToQuery' ],
            10,
            2
        );
        $post_types = $this->getPostTypes( 'names' );
        $num_per_page = 20;
        $page_number = 0;
        if ( isset( $params['offset'] ) && $params['offset'] > 0 ) {
            $page_number = ceil( $params['offset'] / $num_per_page ) + 1;
        }
        $args = [
            'post_type'        => array_keys( $post_types ),
            'mmu_search_query' => $params['search_key'],
        ];
        /* Query the results for the given search keywords */
        $results_query = new \WP_Query( array_merge( $args, [
            'posts_per_page' => $num_per_page,
            'paged'          => $page_number,
        ] ) );
        $results = $results_query->get_posts();
        /* Loop through the results and add in the permalink */
        $results = array_map( function ( $result ) {
            return PostDataFormatter::applyLinks( $result->ID, $result );
        }, $results );
        /* Issue a query to count all matching results, regardless of pagination */
        $count_query = new \WP_Query( array_merge( $args, [
            'posts_per_page' => -1,
            'fields'         => 'ids',
        ] ) );
        $num_results = intval( $count_query->found_posts );
        return [
            'total_results' => $num_results,
            'results'       => $results,
        ];
    }
    
    public function getPostTypesAsObjects()
    {
        return $this->getPostTypes( 'objects' );
    }
    
    public function getPostTypes( $return_type = 'objects' )
    {
        $args = [
            'public'            => true,
            'show_in_nav_menus' => 1,
        ];
        $types = get_post_types( $args, $return_type );
        
        if ( $return_type == 'objects' ) {
            $ret = [];
            foreach ( $types as $name => $obj ) {
                $obj->labels = get_post_type_labels( $obj );
                $ret[] = $obj;
            }
        } else {
            $ret = $types;
        }
        
        return $this->postTypesApplyPremiumFilter( $ret );
    }
    
    public function postTypesApplyPremiumFilter( $types )
    {
        return array_filter( $types, function ( $val ) {
            
            if ( is_object( $val ) ) {
                
                if ( !empty($val->name) ) {
                    return in_array( $val->name, Constants::POST_TYPES_FREE );
                } else {
                    return false;
                }
            
            } else {
                return in_array( $val, Constants::POST_TYPES_FREE );
            }
        
        } );
    }
    
    public function getPost( $params )
    {
        $post = null;
        
        if ( $post = get_post( $params['post_id'] ) ) {
            $post = PostDataFormatter::applyLinks( $post );
            $post->labels = get_post_type_labels( $post );
        }
        
        return [
            'post' => $post,
        ];
    }

}