<?php

namespace MenuManagerUltra\API\Endpoints;

use MenuManagerUltra\API\ReturnValue;
use MenuManagerUltra\API\MenuItemsReturnValue;
use MenuManagerUltra\util\MenuManagerUltra_Logger;
use MenuManagerUltra\lib\MenuItem\MenuItemFormatter;
use MenuManagerUltra\lib\Constants;
use MenuManagerUltra\lib\MenuItems\ArrayOperations;

class MenuItems extends \WP_REST_Controller {

  public function register_routes() {
    register_rest_route( Constants::ROUTE_BASE, '/menu/(?P<menu_id>\d+)/items', array(
      'methods' => 'GET',
      'callback' => [$this, 'getMenuItems'],
      'args' => array(
        'menu_id' => array(
          'validate_callback' => function($param, $request, $key) {
            return is_numeric( $param );
          }
        ),
      ),
      'permission_callback' => function($request) {
        return current_user_can('edit_theme_options');
      },
    ) );

    register_rest_route( Constants::ROUTE_BASE , '/menu/(?P<menu_id>\d+)/items/refresh', array(
      'methods' => 'POST',
      'callback' => [$this, 'refreshMenuItems'],
      'args' => array(
        'menu_id' => array(
          'validate_callback' => function($param, $request, $key) {
            return is_numeric( $param );
          }
        ),
      ),
      'permission_callback' => function($request) {
        return current_user_can('edit_theme_options');
      },
    ) );

    register_rest_route( Constants::ROUTE_BASE , '/menu/(?P<menu_id>\d+)/item/(?P<item_id>\d+)/children', array(
      'methods' => 'GET',
      'callback' => [$this, 'getMenuItems'],
      'args' => array(
        'menu_id' => array(
          'validate_callback' => function($param, $request, $key) {
            return is_numeric( $param );
          }
        ),
        'item_id' => array(
          'validate_callback' => function($param, $request, $key) {
            return is_numeric( $param );
          }
        ),
      ),
      'permission_callback' => function($request) {
        return current_user_can('edit_theme_options');
      }
    ) );
    
    register_rest_route( Constants::ROUTE_BASE , '/menu/update', array(
      'methods' => 'POST',
      'callback' => [$this, 'saveMenuItems'],
      'permission_callback' => function($request) {
        return current_user_can('edit_theme_options');
      }
    ));
  }

  public function getMenuItems( $data ) {

    $parent_id = (isset($data['item_id'])) ? $data['item_id']: '0';

    $query = new \WP_Query([
      'post_type' => 'nav_menu_item',
      'posts_per_page' => -1,
      'meta_query' => [
        [
        'key' => '_menu_item_menu_item_parent',
        'value' => $parent_id
        ]
      ],
      'orderby' => 'menu_order',
      'order' => 'ASC',
      'tax_query'   => array(
        array(
          'taxonomy' => 'nav_menu',
          'field'    => 'term_taxonomy_id',
          'terms'    => $data['menu_id']
        ),
      ),
    ]);

    $posts = $query->get_posts();
    return $this->applyMenuItemDataToPosts($data['menu_id'], $posts);

  }

  public function applyMenuItemDataToPosts($menu_id, $posts) {

    return array_map(
      function ($post) use ($menu_id) {
        $menu_item = $this->enrichMenuItem($post);
        return MenuItemFormatter::applyChildData($menu_item, $menu_id);
      },
      $posts
    );

  }

  public function fetchMenuItem($item_id) {

    return get_post($item_id);

  }

  /**
   * Direct endpoint
   */
  public function saveMenuItems( $request ) {

    $ret_status = ReturnValue::STATUS_ERROR;
    $ret_messages = [];
    $items_arr = [];

    if (empty($request['items']) || empty($request['menu_id'])) {
      $ret_status = ReturnValue::STATUS_ERROR;
      $ret_messages[] = 'Did not find items and menu id in request';
    }
    else {

      $items_arr = $request['items'];

      //Probably dont need this: $items_arr = mm_enrich_frontend_items_from_db($items_arr);
      $items_arr = $this->markItemsAsSeenByFrontend($items_arr);
      $items_arr = $this->loadItemsNotSeenByFrontend($request['menu_id'], $items_arr);

      $this->saveMenuItemsRevision($items_arr);

      $items_arr = $this->processDeletions($items_arr);

      $items_arr = ArrayOperations::order_parents_and_children($items_arr);

      global $wpdb;

      /* @TODO The rest of this function should be extracted into smaller functions */

      // begin transaction
      $wpdb->query('START TRANSACTION');
      
      $index = 0;

      $ret_status = ReturnValue::STATUS_SUCCESS;

      foreach ($items_arr as $item) {

        $position = $index + 1;

        if (!empty($item['isNewAddition'])) {
          $insertable_item = $this->prepareMenuItemForInsert($item);
        }
        else {
          $insertable_item = $this->prepareMenuItemForUpdate($item);
        }

        $insertable_item['menu-item-position'] = $position;
      
        $temp_id = null;

        if (!empty($item['isNewAddition'])) {
          $insert_id = 0;
          $temp_id = $item['ID'];
        }
        else {
          $insert_id = $item['ID'];
        }

        $insert_result = wp_update_nav_menu_item($request['menu_id'], $insert_id, $insertable_item);

        if (is_wp_error($insert_result)) {
          $ret_status = ReturnValue::STATUS_ERROR;
          $ret_messages = $insert_result->get_error_messages();
          $wpdb->query('ROLLBACK');
          break;
        }
        else {

          $item_index = \MenuManagerUltra\util\flat_tree\find_item_index_by_id($items_arr, $item['ID']);

          MenuManagerUltra_Logger::Debug("Item index for new or updated item with id {$item['ID']}: {$item_index}\n");

          $updated_item = $items_arr[$item_index];
          
          if ($temp_id) {
            /* Update the array with the new DB ID, rather than the temporary one */
            $updated_item = (array) $this->loadAndEnrichMenuItem($insert_result);
            $updated_item['isNewAddition'] = false;
            $updated_item[Constants::KEY_ITEM_SEEN_BY_FRONTEND] = true;

            MenuManagerUltra_Logger::Debug("Found temp ID {$temp_id}, new item is " . print_r($updated_item, 1) . "\n");
          }

          $updated_item['menu_order'] = $position;

          $items_arr[$item_index] = $updated_item;

        }

        $index++;
      }

      if ($ret_status == ReturnValue::STATUS_SUCCESS) {
        $wpdb->query('COMMIT');
      }

    }

    $items_arr = ArrayOperations::items_recalculate_depths($items_arr);
    $frontend_items = ArrayOperations::extract_frontend_items($items_arr);

    $retval =  [
      'status' => $ret_status,
      'messages' => $ret_messages,
      'items' => array_values($frontend_items)
    ];

    return $retval;

  }

  public function saveMenuItemsRevision($items_arr) {

    return wp_insert_post(
      [
        'post_type' => 'mmu_revision',
        'post_title' => "Menu Revision from " . date('Y-m-d h:i:s A'),
        'post_content' => serialize($items_arr)
      ]
    );

    /* @TODO Log in case of problem */

  }

  /**
   * Direct Endpoint
   *
   * Refresh items on the frontend without changing their values,
   * except the fields specified by override fields.
   * Used when settings have changed and we want to reflect the changes on the
   * frontend without reverting any changes the user has made
   */
  public function refreshMenuItems($data) {

    $return = new MenuItemsReturnValue();

    if ( empty($data['override_fields']) ) {
      /* Nothing to do here */
      $return->status = ReturnValue::STATUS_SUCCESS;
      $return->items = $data['items'];

    }
    else {
      $items = $data['items'];
      $override_fields = $data['override_fields'];

      if (!is_array($override_fields)) {
        $override_fields = [$override_fields];
      }

      $updated_items = array_map(
        function($item) use ($override_fields) {
          $db_item = (array) $this->loadAndEnrichMenuItem($item['ID']);

          foreach ($override_fields as $field) {

            if (!empty($db_item[$field])) {
              $item[$field] = $db_item[$field];
            }
          }

          return $item;
        },
        $items
      );

      $return->status = ReturnValue::STATUS_SUCCESS;
      $return->items = $updated_items;

    }

    return $return->formatResponse();
  }

  /*
  * Take the items array from the frontend and merge each item with
  * any additional data from the database, in case there's anything from the
  * database that's necessary to create a complete menu item
  * @TODO: this can be done in a single query instead of many
  * @param $items_arr
  * @return Array
  */
  public function enrichFrontendItemsFromDb($items_arr) {

    return array_map(
      function($item) {
        $item[Constants::KEY_ITEM_SEEN_BY_FRONTEND] = true;

        if (empty($item['isNewAddition'])) {
          $saved_menu_item = (array) $this->loadAndEnrichMenuItem($item['ID']);
          return array_merge($saved_menu_item, $item);
        }
        else {
          return $item;
        }
      },
      $items_arr
    );

  }

  public function markItemsAsSeenByFrontend($items_arr) {

    return array_map(
      function($item) {
        $item[Constants::KEY_ITEM_SEEN_BY_FRONTEND] = true;
        return $item;
      },
      $items_arr
    );

  }

  public function loadAndEnrichMenuItem($id) {

    $menu_item = [];
    
    if (!is_numeric($id)) {
      throw new \InvalidArgumentException('id');
    }

    $menu_item = $this->fetchMenuItem($id);
    $menu_item = $this->enrichMenuItem($menu_item);
    
    return $menu_item;

  }

  public function enrichMenuItem($menu_item) {

    if (!$this->menuItemIsSetUp($menu_item)) {
      $menu_item = wp_setup_nav_menu_item($menu_item);
    }

    $menu_item = MenuItemFormatter::applyCustomFields($menu_item);

    MenuManagerUltra_Logger::Debug("Applying links to " . print_r($menu_item, 1). "\n");
    $menu_item = MenuItemFormatter::applyLinks($menu_item);
    $menu_item = MenuItemFormatter::applyDefaultFields($menu_item);
    
    MenuManagerUltra_Logger::Debug("Applied links to " . print_r($menu_item, 1). "\n");

    return $menu_item;

  }

  public function menuItemIsSetUp($menu_item) {

    return (isset($menu_item->db_id)) ? true : false;
  }

  /*
  * Load any items from the database that are not already present
  * in the passed array
  * @param $items_arr
  */
  public function loadItemsNotSeenByFrontend($menu_id, $items_arr) {

    $query = new \WP_Query([
      'post_type' => 'nav_menu_item',
      'post__not_in' => array_column($items_arr, 'ID'),
      'posts_per_page' => -1,
      'orderby' => 'menu_order',
      'order' => 'ASC',
      'tax_query'   => array(
        array(
          'taxonomy' => 'nav_menu',
          'field'    => 'term_taxonomy_id',
          'terms'    => $menu_id
        ),
      ),
    ]);

    $other_menu_items = $query->get_posts();

    $other_menu_items = array_map(
      function($item) {
        $item = (array) wp_setup_nav_menu_item($item);
        $item[Constants::KEY_ITEM_SEEN_BY_FRONTEND] = false;
        return $item;
      },
      $other_menu_items
    );

    return array_merge($items_arr, $other_menu_items);
  }

  public function processDeletions($items_arr) {

    $items_arr = $this->itemsAssignDeleteActions($items_arr);
    $delete_toggle_column_entries = array_column($items_arr, 'markedForDelete', 'ID');

    /* Find items that are marked for deletion */
    while (($id_to_delete = array_search(true, $delete_toggle_column_entries)) !== false) {

      $item_index = \MenuManagerUltra\util\flat_tree\find_item_index_by_id($items_arr, $id_to_delete);

      if ($item_index > -1) {
        $items_arr = $this->itemPerformDeleteAction($items_arr, $items_arr[$item_index]);

        /* Rescan the array in case indexes have changed due to the above operation */
        $delete_toggle_column_entries = array_column($items_arr, 'markedForDelete', 'ID');
      }

    }

    $this->afterProcessDeletions($items_arr);

    return $items_arr;

  }

  public function afterProcessDeletions($items_arr) {

    return array_map(
      function($item) {
        return MenuItemFormatter::applyChildData($item);
      },
      $items_arr
    );

  }

  public function itemsAssignDeleteActions($items_arr) {

    foreach ($items_arr as $item) {
      $items_arr = $this->itemAssignDeleteAction($items_arr, $item);
    }

    return $items_arr;

  }

  public function itemAssignDeleteAction($items_arr, $item) {

    if (!empty($item['markedForDelete'])) {
      if (!empty($item['deleteTree'])) {
        $child_item_ids = \MenuManagerUltra\util\flat_tree\find_child_ids($items_arr, $item);

        if (count($child_item_ids) > 0) {
          foreach ($child_item_ids as $child_item_id) {
            $child_item = \MenuManagerUltra\util\flat_tree\find_item_by_id($items_arr, $child_item_id);

            if ($child_item) {
              /*
                An item in the deleted tree can be manually undeleted by the user on the frontend.
                That's why we check to determine if it was ever visible on the frontend (since the user
                may not have expanded the parent tree). If it was seen by the frontend, double check markedForDelete
                which will be false if the user manually undeleted this particular item within a deleted tree.
              */
              if (!isset($child_item['deleteTree'])) {
                $child_item['deleteTree'] = true;
              }

              if (empty($child_item[Constants::KEY_ITEM_SEEN_BY_FRONTEND])) {
                $child_item['markedForDelete'] = true;
                $child_item['deleteTree'] = true;
              }

              $child_item_index = \MenuManagerUltra\util\flat_tree\find_item_index_by_id($items_arr, $child_item_id);
              $items_arr[$child_item_index] = $child_item;

              $items_arr = $this->itemAssignDeleteAction($items_arr, $child_item);
                        
            }
          }
        }
      }
    }

    return $items_arr;

  }

  public function itemPerformDeleteAction($items_arr, $item) {

    if (isset($item['deleteTree']) && !$item['deleteTree']) {
      /* Move children to parent */

      MenuManagerUltra_Logger::Debug("Should move children to parent");

      $parent_item_id = !empty($item['menu_item_parent']) ? $item['menu_item_parent'] : null;
      $parent_item    = null;
      
      if ($parent_item_id) {
        $parent_item = \MenuManagerUltra\util\flat_tree\find_item_by_id($items_arr, $parent_item_id);
      }

      $items_arr = ArrayOperations::item_reassign_children_to_new_parent($items_arr, $item, $parent_item);
      
    }

    $items_arr = \MenuManagerUltra\util\flat_tree\remove_item($items_arr, $item);
    $this->itemDeleteFromDb($item);

    return $items_arr;

  }

  public function itemDeleteFromDb($item) {

    MenuItemFormatter::confirmValidMenuItem($item);

    $result = wp_delete_post($item['ID']);

    MenuManagerUltra_Logger::Debug("Deleted Item " . $item['ID']);

    if (is_wp_error($result)) {
      throw new \RuntimeException($result->get_error_messages());
    }

    return $result;
  }

  public function prepareMenuItemForInsert($menu_item_data = []) {

    $ret = $this->prepareMenuItemForUpdate($menu_item_data);
    $ret['menu-item-status'] = 'publish';

    return $ret;
  }

  /**
  * wp_update_nav_menu_item() expects different array keys from
  * what is provided by the REST API. So this function simply converts the keys.
  * @param array $menu_item_data The data object in its "RESTful" format
  * @return array
  */
  public function prepareMenuItemForUpdate($menu_item_data = []) {

    $classes = is_array($menu_item_data['classes']) ? join(' ', $menu_item_data['classes']) : $menu_item_data['classes'];

    $ret = array(
      'menu-item-db-id'         => $menu_item_data['ID'],
      'menu-item-object-id'     => $menu_item_data['object_id'],
      'menu-item-object'        => $menu_item_data['object'],
      'menu-item-parent-id'     => $menu_item_data['menu_item_parent'],
      'menu-item-position'      => $menu_item_data['menu_order'],
      'menu-item-type'          => $menu_item_data['type'],
      'menu-item-title'         => $menu_item_data['title'],
      'menu-item-url'           => $menu_item_data['url'],
      'menu-item-description'   => $menu_item_data['description'],
      'menu-item-attr-title'    => $menu_item_data['attr_title'],
      'menu-item-target'        => $menu_item_data['target'],
      'menu-item-classes'       => $classes,
      'menu-item-xfn'           => $menu_item_data['xfn'],
      'menu-item-status'        => $menu_item_data['post_status'],
      'menu-item-post-date'     => $menu_item_data['post_date'],
      'menu-item-post-date-gmt' => $menu_item_data['post_date_gmt'],
    );

    if ($ret['menu-item-description'] == null) {
      $ret['menu-item-description'] = " "; //Yes, this is how WordPress does this. ¯\_(ツ)_/¯
    }

    if ($ret['menu-item-attr-title'] === null) {
      $ret['menu-item-attr-title'] = "";
    }

    return $ret;

  }
}