<?php

class MenuTreeNavigator {

  /**
   * Modification of "Build a tree from a flat array in PHP"
   *
   * Authors: @DSkinner, @ImmortalFirefly and @SteveEdson
   *
   * @link https://stackoverflow.com/a/28429487/2078474
   */
  public static function buildTree(array &$elements, $parentId = 0) {
    $branch = array();
    foreach ( $elements as &$element )
    {
        if ( $element->menu_item_parent == $parentId )
        {
            $children = self::buildTree( $elements, $element->ID );
            if ( $children ) {
              $element->menu_children = $children;
            }

            $branch[$element->object_id] = $element;
            unset( $element );
        }
    }

    print_r($branch); 

    /* Sort by menu order */
    $order = array_column($branch, 'menu_order');
    array_multisort($order, SORT_ASC, $branch);

    // usort($branch, 
    //   function ($item1, $item2) {
    //     if ($item1['menu_order'] == $item2['menu_order']) return 0;
    //     return $item1['menu_order'] < $item2['menu_order'] ? -1 : 1;
    //   }
    // );

    return $branch;
  }

  /**
   * Creates an array of elements keyed by element ID and object ID
   * @return Array [items keyed by menu id, items keyed by object ID]
   */
  function itemsKeyedByID( array &$elements ) {
      $object_id_branch = array();
      $menu_id_branch = array();
      foreach ( $elements as &$element )
      {
          $object_id_branch[$element->object_id] = $element;
          $menu_id_branch[$element->ID] = $element;
          unset( $element );

      }

      return [$menu_id_branch, $object_id_branch];
  }

  public static function treeFromMenuItems($menu_items) {

    return self::buildTree($menu_items);

  }

  public static function treeByMenuID($menu_id) {

    $tree = [];

    if ($menu_items = wp_get_nav_menu_items($menu_id)) {
      $tree = self::treeFromMenuItems($menu_items);  
    }
    
    return $tree;
      
  }

  public static function nestedTreeByMenuID($menu_id) {

    $tree = [];

    if ($menu_items = self::treeByMenuID($menu_id)) {
      list( $menu_items_by_menu_id, $tree ) = self::itemsKeyedByID($menu_items);
    }

    return $tree;
  }

  /**
   * Find the topmost parent for a given post id, in a set of items
   * @return element menu item element or false if none found
   */
  public static function topLevelParentByItems( $items, $post_id ) {

    $rekeyed_items = self::itemsKeyedByID($items);

    $items_by_menu_id = $rekeyed_items[0];
    $items_by_object_id = $rekeyed_items[1];

    if ( !empty($items_by_object_id[$post_id]) ) {
      $element = $items_by_object_id[$post_id];
      
      while( $element->menu_item_parent ) {
        $element = $items_by_menu_id[$element->menu_item_parent];

      }

      return $element;
    }

    return false;
  }

  public static function treeItemGetOutput($menu_object, $options = []) {

    $recursion_depth = 0; 

    if ( isset($options['recursion_depth']) ) {
      $recursion_depth = $options['recursion_depth'];
    }

    if ( !empty($options['max_depth']) ) {
      
      if ( ($recursion_depth) >= $options['max_depth']) {
        return;
      }
    }

    $output = '';

    $output .= "<li class=\"menu-item\"><a href=\"{$menu_object->url}\">{$menu_object->title}</a>";

    if ( $menu_object->menu_children ) {

      $inner_options = $options;
      $inner_options['recursion_depth'] = $recursion_depth + 1;

      foreach( $menu_object->menu_children as $child ) {

        if ( $sub_output =  self::treeItemGetOutput($child, $inner_options) ) {
          $output .= '<ul class="sub-menu">';
          $output .= $sub_output;
          $output .= '</ul>';
        }
      }

    }

    $output .= "</li>";

    return $output;

  }

  public static function findPostIdInMenuTree($menu_tree, $post_id) {
    if ( $menu_tree ) {
      foreach ($menu_tree as $menu_object) {
        if ( $menu_object->object_id == $post_id ) {
          return $menu_object;
        }
        else {
          if ( !empty($menu_object->menu_children) ) {
            if ( $position = self::findPostIdInMenuTree($menu_object->menu_children, $post_id) ) {
              return $position;
            }
          }
        }
      }
    }

    return false;
  }

  function getActiveMenuKey($location) {

    $theme_locations = get_nav_menu_locations();
    $menu_obj = getActiveMenuObject($location);

    if ( $menu_obj && !empty($menu_obj->slug) ) {
      return $menu_obj->slug;
    }

    return null;

  }

  function getActiveMenuObject($location) {

    $theme_locations = get_nav_menu_locations();
    
    /* @TODO apply filter here */
    
    if (!empty($theme_locations[$location])) {
      $menu_obj = wp_get_nav_menu_object($theme_locations[$location]);
    
      return $menu_obj;
    }

    return null;

  }

  public static function getActiveMenuTree($location) {

    $menu_tree = [];
    $menu_items = self::getActiveMenuItems($location);

    if ( $menu_items ) {
      $menu_tree = self::buildTree($menu_items);
    }

    return $menu_tree;

  }



  public static function getActiveMenuItems($location) {

    $menu_items = [];
    $menu_key = getActiveMenuKey($location);
    $menu_items = wp_get_nav_menu_items($menu_key);

    return $menu_items;

  }



  public static function sidebarMenuGetChildrenOrSiblings($post, $location) {

    return sidebarMenuGetChildren($post, $location, ['siblings_if_no_children' => true]);

  }

  public static function sidebarMenuGetChildren($post, $location, $options = [] ) {

    $output = '';
    $sidebar_title = '';
    $found_children = false;
    $found_items = false;
    
    $siblings_if_no_children = ( isset($options['siblings_if_no_children']) ) ? $options['siblings_if_no_children'] : false;

    $sidebar_title = null;

    $menu_items = self::getActiveMenuItems($location);
    
    if ( $menu_items ) {
    
      $menu_tree = self::treeFromMenuItems($menu_items);  
      list( $menu_items_by_menu_id, $menu_items_by_post_id ) = self::itemsKeyedByID($menu_items);

      $my_top_level = self::topLevelParentByItems($menu_items, $post->ID);

      $top_level_object_id = null ;

      if ( !empty($my_top_level->object_id) ) {
        $top_level_object_id = $my_top_level->object_id;
      }

      if ( $top_level_object_id && !empty($menu_tree[$top_level_object_id]) ) {

        $sub_tree = self::findPostIdInMenuTree($menu_tree[$top_level_object_id]->menu_children, $post->ID);

        if ( !$sub_tree->menu_children && $siblings_if_no_children ) {

          //
          // Get siblings instead
          //
          $parent_item_id = $sub_tree->menu_item_parent;
          $parent_item = $menu_items_by_menu_id[$parent_item_id];
          $sidebar_title = $parent_item->title;
          $sub_tree = $parent_item;
          $found_items = true;
        }
        else {
          $sidebar_title = $sub_tree->title;
        }
      }

      if ( !empty($sub_tree) ) {   

        if ( $sub_tree->menu_children ) {
          $found_items = true;
          $found_children = true;

          foreach( $sub_tree->menu_children as $child ) {
            $output .= self::treeItemGetOutput($child, $options);
          }
        }
      }
    }

    return [
      'menu_title' => $sidebar_title,
      'menu_output' => $output,
      'found_children' => $found_children,
      'found_items' => $found_items
    ];
  }
}