<?php

namespace MenuManagerUltra\util\flat_tree;

use \MenuManagerUltra\lib\Constants;
use \MenuManagerUltra\util\MenuManagerUltra_Logger;

/**
 * Locates children of $parent_item in the array, and moves them into place directly below the
 * parent.
 *
 * If $options['skip_fetched'] is truthy, this function will not operate on any items that were fetched on the frontend.
 * That way, it won't override ordering that was done manually by the user.
 *
 * @param array $items_arr  The array of items
 * @param array $parent_item The parent item as a key/value array
 * @param int $insert_index the next index to insert items into
 * @param array $options Key/value array supporting the 'skip_fetched' option
 */
function move_children_into_place($items_arr, $parent_item, $insert_index = false, $options = []) {
  
  static $recurs_check = 0;

  $child_item_ids = find_child_ids($items_arr, $parent_item);

  if ($insert_index === false) {
    $insert_index = find_item_index_by_id($items_arr, $parent_item['ID']);
  }

  if (count($child_item_ids) > 0) {

    foreach ($child_item_ids as $child_item_id) {
      $child_index = find_item_index_by_id($items_arr, $child_item_id);

      if ($child_index !== false) {
        
        $child_item = $items_arr[$child_index];

        if ($child_index > $insert_index) {
          $insert_index++;
        }

        if (empty($options['skip_fetched']) || empty($parent_item['wasFetched'])) {
      
          if ($child_index != $insert_index) {
            $child_item_extracted = array_splice($items_arr, $child_index, 1);
            $child_item = $child_item_extracted[0];

            //echo "Found {$child_item_id} as index {$child_index}. Moving {$child_index} to " . ($insert_index) . "\n";
            array_splice($items_arr, $insert_index, 0, $child_item_extracted);
          }
          else {
            //echo "{$child_item_id} already at {$child_index}. No Move\n";
          }
        }
        else {
          //echo "{$child_item_id} part of fetched parent. No Move\n";
        }

        //$insert_index++;

        
        //print_r(array_column($items_arr, 'ID'));

        $sub_child_item_ids = find_child_ids($items_arr, $child_item);

        if (count($sub_child_item_ids) > 0) {

          $recurs_check++;
          if ($recurs_check > Constants::CHILDREN_MAX_RECURSION) {
            MenuManagerUltra_Logger::warn("too much recursion in " . __FUNCTION__); break;
          }
          list($items_arr, $insert_index) = move_children_into_place($items_arr, $child_item, $insert_index);
          $recurs_check--;
        }

      }
      
    }
  }

  return [ $items_arr, $insert_index ];

}

/**
 * Find a particular item by its ID column
 *
 * @param array $items_arr
 * @param string $item_id
 */
function find_item_index_by_id($items_arr, $item_id) {

  /**
   * Don't like using foreach here, but array_column + array_search causes issues
   * if an ID key doesn't exist on every item. An ID key should exist on every item,
   * but this is not the place to determine how to handle missing IDs
   * since this function should be able to find the right item even in the presence of
   * some other problematic item in the array.
   */

  $index = 0;
  $id_key = constant('MMU_FIELD_KEY_ID');

  foreach($items_arr as $item) {
    if (isset($item[$id_key]) && $item[$id_key] == $item_id) {
      return $index;
    }

    $index++;
  }

  return -1;
}

function find_item_by_id($items_arr, $item_id) {

  $index = find_item_index_by_id($items_arr, $item_id);

  if ($index > -1) {
    return $items_arr[$index];
  }
}

function find_child_ids($items_arr, $item) {

  return array_column(
    find_children($items_arr, $item),
    'ID'
  );

}

function find_children($items_arr, $item) {

  return array_filter(
    $items_arr,
    function($search_item) use ($item) {
      return ($search_item['menu_item_parent'] == $item[constant('MMU_FIELD_KEY_ID')]);
    }
  );

}

function remove_item($items_arr, $item) {

  $index = find_item_index_by_id($items_arr, $item['ID']);

  if ($index > -1) {
    array_splice($items_arr, $index, 1);
  }

  return $items_arr;

}

function update_item($items_arr, $item) {

  $index = find_item_index_by_id($items_arr, $item['ID']);

  if ($index > -1) {
    $stored_item = $items_arr[$index];
    $items_arr[$index] = array_merge($stored_item, $item);
  }

  return $items_arr;
}

/**
 * Changes direct descendants of a given item to be descendants of a different
 * item.
 */
function reassign_children_to_new_parent($items_arr, $item, $new_parent_item = false) {
  
  $new_parent_id = null;

  if ($new_parent_item === false) {
    throw new \Exception('Missing value for new_parent_item');
  }

  if ($new_parent_item === null) {
    /* Assign to root */
    $new_parent_id = 0;
  }
  else {
    $new_parent_id = $new_parent_item['ID'];
  }

  $child_item_ids = find_child_ids($items_arr, $item);

  if ($new_parent_id !== null && count($child_item_ids) > 0) {
  
    foreach ($child_item_ids as $child_item_id) {
      $child_item_index = find_item_index_by_id($items_arr, $child_item_id);
      $child_item = $items_arr[$child_item_index];

      if ($child_item) {
        $child_item['menu_item_parent'] = $new_parent_id;
        $child_item['parentItem'] = $new_parent_item;
        $items_arr[$child_item_index] = $child_item;
      }
    }
  }

  return $items_arr;

}