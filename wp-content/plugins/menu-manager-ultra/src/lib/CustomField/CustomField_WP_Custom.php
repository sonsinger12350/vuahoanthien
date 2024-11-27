<?php

namespace MenuManagerUltra\lib\CustomField;

use MenuManagerUltra\lib\CustomField\CustomFieldInterface;

class CustomField_WP_Custom implements CustomFieldInterface {

  const TYPE_KEY = 'WP_Custom';

  public function listAll() {
    
    global $wpdb;

    $fields = [];

    $result = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT DISTINCT meta_key
        FROM wp_postmeta WHERE meta_key NOT LIKE '\\_%'"),
      ARRAY_A
    );

    if (is_wp_error($result)) {
      throw new \Exception($result->get_error_messages());
    }
    else {
      foreach ($result as $result_item) {
        $info = new CustomFieldInfo(
          $result_item['meta_key'],
          ['label' => $result_item['meta_key'],
          'source' => self::TYPE_KEY]
        );
        
        $fields[$result_item['meta_key']] = $info->asArray();
      }
    }

    return $fields;
  }

  public function info($field_key) {

    if ($field_key) {

      global $wpdb;

      $result = $wpdb->get_results(
        $wpdb->prepare(
          "SELECT DISTINCT meta_key
          FROM {$wpdb->prefix}postmeta WHERE meta_key = %s", $field_key),
        ARRAY_A
      );

      if (is_wp_error($result)) {
        throw new \Exception($result->get_error_messages());
      }
      else {
        $result_item = array_pop($result);
        return new CustomFieldInfo($result_item['meta_key'], ['label' => $result_item['meta_key'], 'source' => self::TYPE_KEY]);
      }

    }
    else {
      throw new \InvalidArgumentException("field_key invalid");
    }
  }

  public function value($field_key, $post_id) {

    if (empty($field_key) || empty($post_id) || !is_numeric($post_id)) {
      throw new \InvalidArgumentException('field_key or post_id');
    }

    global $wpdb;

    $result = $wpdb->get_results(
      $wpdb->prepare(
        "SELECT DISTINCT meta_key, meta_value
        FROM {$wpdb->prefix}postmeta WHERE meta_key = %s and post_id = %d", $field_key, $post_id),
      ARRAY_A
    );

    if (is_wp_error($result)) {
      throw new \Exception($result->get_error_messages());
    }
    else {
      $result_item = array_pop($result);
      return new CustomFieldInfo($result_item['meta_key'], ['label' => $result_item['meta_key'], 'source' => self::TYPE_KEY]);
    }
  }
}