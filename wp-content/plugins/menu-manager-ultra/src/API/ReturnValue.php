<?php

/**
 * @copyright Copyright (c) 2022 JK Plugins <contactjkplugins@gmail.com>
 * @author JK Plugins <contactjkplugins@gmail.com>
 *
 * ReturnValue is the base class for returning data from the API.
 * This class should be used whenever returning data from
 * the backend to the frontend.
 *
 * Usage:
 *
 * $return = new ReturnValue();
 *
 * $return->status = ReturnValue::STATUS_SUCCESS;
 * $return->addMessage('Successful API call!');
 *
 * $response = $return->formatResponse();
 */

namespace MenuManagerUltra\API;

class ReturnValue {

  const STATUS_ERROR = 'error';
  const STATUS_SUCCESS = 'success';

  protected $status = null;
  protected $message = null;
  protected $returnFieldsDefault = ['status', 'message'];

  /**
   * If returnFields are included here present, their values will be included
   * in the response.
   *
   * @var array $returnFields Specifies additional object members to include in the return data
   *
   * Example usage:
   *
   * class subReturnValue extends ReturnValue {
   *   protected $returnFields = ['items'];
   * }
   *
   * $return = new subReturnValue();
   * $return->items = ['one', 'two', 'three'];
   *
   * $response = $return->formatResponse();
   *
   * In the above example, the response array will include a key called 'items'
   * with the value of $return->items
   */
  protected $returnFields = [];

  public function __get($key) {
    if (!property_exists($this, $key)) {
        throw new \InvalidArgumentException("{$key} is not a valid property");
    }

    return $this->$key;
  }

  public function __set($key, $val) {
    if (!property_exists($this, $key)) {
        throw new \InvalidArgumentException("{$key} is not a valid property");
    }

    if ($key == 'message') {
      $this->addMessage($val);
    }
    else {
      $this->$key = $val;
    }
  }

  public function addMessage($message) {

    if (!is_array($this->message)) {
      $orig_message = $this->message;
      $this->message = [];

      if ($orig_message) {
        $this->message[] = $orig_message;
      }
    }

    $this->message[] = $message;
  }

  public function formatResponse() {

    $ret = [];

    foreach (array_merge($this->returnFieldsDefault, $this->returnFields) as $field) {
      $ret[$field] = $this->{$field};
    }

    return $ret;

  }

}
