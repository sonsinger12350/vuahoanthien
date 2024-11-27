<?php

namespace MenuManagerUltra\lib\Post;

class PostDataFormatter {

  public static function applyLinks($post, $apply_to_obj = null) {

    if (!$post) {
      throw new \InvalidParameterException("Invalid Post");
    }
  
    $post_obj = (is_numeric($post)) ? get_post($post) : $post;
    
    if (!$apply_to_obj) {
      $apply_to_obj = $post_obj;
    }

    $apply_to_obj->permalink = get_permalink($post_obj->ID);
    $apply_to_obj->edit_link = html_entity_decode(get_edit_post_link($post_obj->ID));
    $apply_to_obj->display_link = wp_make_link_relative($apply_to_obj->permalink);
    $apply_to_obj->goto_link = $apply_to_obj->permalink;
  
    return $apply_to_obj;
  }
  

}