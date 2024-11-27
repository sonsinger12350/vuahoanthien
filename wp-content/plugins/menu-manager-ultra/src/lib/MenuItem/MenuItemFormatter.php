<?php

namespace MenuManagerUltra\lib\MenuItem;

use MenuManagerUltra\lib\Post\PostDataFormatter;

class MenuItemFormatter {

  public const MENU_ITEM_OBJECT_TYPE_CUSTOM = 'custom';

  public static function applyLinks($menu_item) {

    if (!$menu_item) {
      throw new \InvalidParameterException("Invalid menu item");
    }
  
    if ($menu_item->object != self::MENU_ITEM_OBJECT_TYPE_CUSTOM) {
      if (!empty($menu_item->object_id)) {
        $menu_item = PostDataFormatter::applyLinks($menu_item->object_id, $menu_item);
      }
    }
    else {
      $menu_item->display_link = $menu_item->url;
      $menu_item->goto_link = $menu_item->url;
    }
  
    return $menu_item;
  }

  public static function applyDefaultFields($menu_item) {

    if (!$menu_item) {
      throw new \InvalidParameterException("Invalid menu item");
    }
  
    if ($menu_item->object != self::MENU_ITEM_OBJECT_TYPE_CUSTOM) {
      if (!empty($menu_item->object_id)) {
        $menu_item->object_title = get_post_field('post_title', $menu_item->object_id);
      }
    }
    
    return $menu_item;
  }

  public static function applyCustomFields($menu_item) {

    $enabled_custom_fields = get_option('mmu_custom_fields_applied', []);
    $menu_item->object_fields = [];
  
    foreach ($enabled_custom_fields as $info) {
      try {
        if ($field_obj = \MenuManagerUltra\lib\CustomField\CustomFieldManager::create($info['field_source'])) {
          if (!empty($menu_item->object_id)) {
  
            $field_info = $field_obj->info($info['field_key']);
            $field_arr  = $field_info->asArray();
            $field_arr['field_value'] = $field_obj->value($info['field_key'], $menu_item->object_id);
  
            $menu_item->object_fields[] = $field_arr;
            
          }
        }
      }
      catch(\Exception $e) {
        // @TODO: Log this error. It shouldn't bomb if the field source is no longer valid for some reason.
      }
    }
  
    return $menu_item;
  
  }

  public static function applyChildData($menu_item, $menu_id = null) {

    if ($menu_id !== null) {
      self::confirmValidMenuID($menu_id);
    }

    self::confirmValidMenuItem($menu_item);

    $item_id = (is_object($menu_item)) ? $menu_item->ID : $menu_item['ID'];
    
    $tax_query = [];

    if ($menu_id) {
      $tax_query = array(
        array(
          'taxonomy' => 'nav_menu',
          'field'    => 'term_taxonomy_id',
          'terms'    => $menu_id
        )
      );
    }

    $child_query = new \WP_Query([
      'post_type' => 'nav_menu_item',
      'posts_per_page' => -1,
      'fields' => 'ids',
      'meta_query' => [
        [
        'key' => '_menu_item_menu_item_parent',
        'value' => $item_id
        ]
      ],
      'tax_query' => $tax_query,
    ]);
  
    $children_count = ($child_query->found_posts) ? $child_query->found_posts : 0;

    if (is_object($menu_item)) {
      $menu_item->has_fetchable_children = $children_count;
    }
    else {
      $menu_item['has_fetchable_children'] = $children_count;
    }
    
  
    return $menu_item;
  }

  public static function confirmValidMenuID($menu_id) {
    
    if (!$menu_id || !is_numeric($menu_id)) {
      throw new \InvalidArgumentException("Invalid Menu ID");
    }
  }
  
  public static function confirmValidMenuItem($menu_item) {
    if (!$menu_item) {
      throw new \InvalidArgumentException("No Menu Item Found");
    }
    
    $menu_item = (array) $menu_item;
  
    if (empty($menu_item['ID'])) {
      throw new \InvalidArgumentException("No ID found for Menu Item");
    }
  
    return true;
  }

}