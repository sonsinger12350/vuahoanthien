<?php

namespace MenuManagerUltra\lib\CustomField;

use MenuManagerUltra\lib\CustomField\CustomFieldInterface;
use MenuManagerUltra\lib\CustomField\CustomFieldInfo;

class CustomField_ACF implements CustomFieldInterface {

  const TYPE_KEY = 'ACF';

  public function listAll() {
    
    $args = [
      'post_type' => 'acf-field',
      'posts_per_page' => -1,
      'orderby' => 'title',
      'order' => 'ASC',
    ];

    $posts = get_posts($args);

    if (is_wp_error($posts)) {
      throw new Exception($posts->get_error_messages());
    }
    else {
      foreach($posts as $post_info) {
        $field_key = $post_info->post_excerpt;
        $info = new CustomFieldInfo($field_key, ['label' => $post_info->post_title, 'source' => self::TYPE_KEY]);
       
        $fields[$field_key] = $info->asArray();
      }
    }

    return $fields;

  }

  /* TODO: should be more DRY */
  public function info($field_key) {

    global $wpdb;

    $result = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT ID
        FROM {$wpdb->prefix}posts WHERE post_type=%s AND post_excerpt = %s", ['acf-field', $field_key]
      ),
      ARRAY_A
    );

   if (is_wp_error($result)) {
      throw new \Exception($result->get_error_messages());
    }
    else {
      if ($result && $result_item = array_pop($result)) {
        $post = get_post($result_item['ID']);
        return new CustomFieldInfo($field_key, ['label' => $post->post_title, 'source' => self::TYPE_KEY]);
      }
      else {
        return new CustomFieldInfo();
      }
    }
  }

  public function value($field_key, $post_id) {

    if (function_exists('get_field')) {
      return get_field($field_key, $post_id);
    }
    
    return null;
    
  }
}

