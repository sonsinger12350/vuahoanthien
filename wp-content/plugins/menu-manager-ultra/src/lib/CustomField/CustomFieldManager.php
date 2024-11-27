<?php

namespace MenuManagerUltra\lib\CustomField;

class CustomFieldManager {

  /* @TODO: types of custom fields probably should be dynamic */
  public static $customFieldSuffixes = ['Post', 'WP_Custom', 'ACF'];

  public static function create($type_suffix) {

    $class_name = "\MenuManagerUltra\lib\CustomField\CustomField_{$type_suffix}";

    if (in_array($type_suffix, self::$customFieldSuffixes) && class_exists($class_name)) {
      return new $class_name;
    }
    else {
      throw new \InvalidArgumentException("Invalid type passed to " . __FUNCTION__ . ':' . __METHOD__);
    }
  }

  public static function listAll() {
    
    $fields = [];

    foreach(self::$customFieldSuffixes as $type_key) {
      $fields = array_merge($fields, self::create($type_key)->listAll());
    }

    return array_values($fields);
  }
}