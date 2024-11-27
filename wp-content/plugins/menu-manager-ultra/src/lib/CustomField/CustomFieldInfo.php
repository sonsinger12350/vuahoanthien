<?php

namespace MenuManagerUltra\lib\CustomField;

class CustomFieldInfo {

  protected $_key = null;
  protected $_label = null;
  protected $_source = null;

  public function __construct($key = null, $data = []) {
    if ($key) {
      $this->_key = $key;
    }

    if ($data) {
      foreach ($data as $data_key => $data_val) {
        $this->{$data_key} = $data_val;
      }
    }
  }

  public function __get($key) {
    
    $real_key = "_{$key}";
    
    if (!property_exists($this, $real_key)) {
      throw new \InvalidArgumentException("{$key} is not a valid property");
    }

    return $this->$real_key;
  }

  public function __set($key, $val) {

    $real_key = "_{$key}";

    if (!property_exists($this, $real_key)) {
        throw new \InvalidArgumentException("{$key} is not a valid property");
    }

    $this->$real_key = $val;
  }

  /* @TODO this should be more dynamic in determining 
     which fields are part of the returned data */
  public function asArray() {
    return [
      'field_key' => $this->_key,
      'field_label' => $this->_label,
      'field_source' => $this->_source
    ];
  }
  
}