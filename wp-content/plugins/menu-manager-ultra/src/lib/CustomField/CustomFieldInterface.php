<?php

namespace MenuManagerUltra\lib\CustomField;

interface CustomFieldInterface {

  public function listAll();
  public function info($field_key);
  public function value($field_key, $post_id);

}