<?php

namespace MenuManagerUltra\API\Endpoints;

use MenuManagerUltra\API\ReturnValue;
use MenuManagerUltra\API\MenusReturnValue;
use MenuManagerUltra\API\MenuReturnValue;
use MenuManagerUltra\lib\Constants;

class Menus extends \WP_REST_Controller {

  public function register_routes() {
    register_rest_route(Constants::ROUTE_BASE, '/menu/add/',
      array(
        'methods' => 'POST',
        'callback' => [$this, 'addMenu'],
        'permission_callback' => function($request) {
          return current_user_can(Constants::ENDPOINT_PERMISSION_DEFAULT);
        }
      )
    );

    register_rest_route(Constants::ROUTE_BASE, '/menus/list',
      array(
        'methods' => 'GET',
        'callback' => [$this, 'listAll'],
        'permission_callback' => function($request) {
          return current_user_can(Constants::ENDPOINT_PERMISSION_DEFAULT);
        }
      )
    );
    
  }

  public function addMenu($data) {

    $return = new MenuReturnValue();
    $menu_id = null;
    $status = ReturnValue::STATUS_ERROR;
  
    if (empty($data['menu_name'])) {
      $return->addMessage('No menu name given');
    }
    else {
      if (wp_get_nav_menu_object($data['menu_name'])) {
        $return->addMessage('A menu with that name already exists');
      }
      else {
        $create_result = wp_create_nav_menu($data['menu_name']);
  
        if (is_wp_error($create_result)) {
          $return->addMessage($create_result->get_error_message());
        }
        else {
          $menu_id = $create_result;
          $status = ReturnValue::STATUS_SUCCESS;
        }
      }
    }
    
    $return->status = $status;
    $return->menu_id = $menu_id;

    return $return->formatResponse();
  }

  public function listAll() {

    $return = new MenusReturnValue();
    $menus = [];
    $status = ReturnValue::STATUS_ERROR;

    $query_result = get_terms(
      [
        'taxonomy' => 'nav_menu',
        'orderby' => 'name',
        'order' => 'ASC',
        'hide_empty' => false,
      ]
    );

    if (is_wp_error($query_result)) {
      $return->addMessage($query_result->get_error_message());
    }
    else {
      $menus = array_map(
        function ($term_obj) {
          $new_term_obj = wp_get_nav_menu_object($term_obj);
          $new_term_obj->id = $new_term_obj->term_id;

          return $new_term_obj;
        }
        , $query_result
      );
      
      $status = ReturnValue::STATUS_SUCCESS;
    }

    $return->status = $status;
    $return->menus = $menus;

    return $return->formatResponse();

  }
}