<?php

namespace MenuManagerUltra\lib\MenuItems;

use MenuManagerUltra\lib\Constants;
use MenuManagerUltra\util\MenuManagerUltra_Logger;

class ArrayOperations {

  /*
  * Creates an ordered, flat array where children
  * always follow parents
  */
  public static function order_parents_and_children($items_arr) {

    $top_level_items = self::extract_top_level_items($items_arr);

    $insert_index = 0;

    MenuManagerUltra_Logger::Debug("items" . print_r($items_arr, 1). "\n");

    foreach($top_level_items as $item) {
      
      if ($insert_index > 0) {
        MenuManagerUltra_Logger::Debug("insert index: {$insert_index}\n");
        /* TODO: is this necessary? */
        $item_index = \MenuManagerUltra\util\flat_tree\find_item_index_by_id($items_arr, $item['ID']);
        $item_extracted = array_splice($items_arr, $item_index, 1);
        array_splice($items_arr, $insert_index, 0, $item_extracted);

        MenuManagerUltra_Logger::Debug("TOP LEVEL: Moved {$item['ID']} to index {$insert_index}\n");
      }
      
      $child_item_ids = \MenuManagerUltra\util\flat_tree\find_child_ids($items_arr, $item);

      if (count($child_item_ids) > 0) {
        MenuManagerUltra_Logger::Debug("Found children for {$item['ID']}\n" . print_r($child_item_ids, 1) . "\n");
        list($items_arr, $insert_index) = \MenuManagerUltra\util\flat_tree\move_children_into_place(
          $items_arr,
          $item,
          false,
          ['skip_fetched' => true]
        );
      }
      
      MenuManagerUltra_Logger::Debug("==items from top level iterator==\n");
      MenuManagerUltra_Logger::Debug(print_r(array_column($items_arr, 'ID'), 1));
      $insert_index++;

    }

    MenuManagerUltra_Logger::Debug(join(',', array_column($items_arr, 'ID')));
    MenuManagerUltra_Logger::Debug("items before insert\n");
    MenuManagerUltra_Logger::Debug(print_r($items_arr, 1));

    return $items_arr;

  }

  public static function extract_top_level_items($items_arr) {

    return array_values(array_filter(
      $items_arr,
      function($item) {
        return (isset($item['menu_item_parent']) && intval($item['menu_item_parent']) == 0);
      }
    ));

  }

  public static function extract_frontend_items($items_arr) {

    return array_filter(
      $items_arr,
      function ($item) {
        return !empty($item[Constants::KEY_ITEM_SEEN_BY_FRONTEND]);
      }
    );
  
  }

  public static function item_reassign_children_to_new_parent($items_arr, $item, $parent_item) {

    if ($parent_item) {
      MenuManagerUltra_Logger::Debug("Moving children of {$item['title']} to new parent ID: {$parent_item['ID']}");
    }
    else {
      MenuManagerUltra_Logger::Debug("Moving children of {$item['title']} to root");
    }
  
    $items_arr = \MenuManagerUltra\util\flat_tree\reassign_children_to_new_parent($items_arr, $item, $parent_item);
    
    return self::items_recalculate_depths($items_arr);
  
  }
  
  public static function items_recalculate_depths($items_arr) {
  
    return array_map(
      function ($item) use ($items_arr) {
        $item['depth'] = self::item_determine_depth($items_arr, $item);
        return $item;
      }
      , $items_arr
    );
  
  }
  
  public static function item_determine_depth($items_arr, $item) {
  
    $depth = 0;
  
    if (!empty($item['menu_item_parent'])) {
  
      $parent_index = \MenuManagerUltra\util\flat_tree\find_item_index_by_id($items_arr, $item['menu_item_parent']);

      if ($parent_index > -1) {
        $parent_item = $items_arr[$parent_index];
        $depth = self::item_determine_depth($items_arr, $parent_item) + 1;
      }
  
    }
  
    return $depth;
  }


}
