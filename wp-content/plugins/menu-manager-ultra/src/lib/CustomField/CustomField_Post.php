<?php

namespace MenuManagerUltra\lib\CustomField;

use MenuManagerUltra\lib\CustomField\CustomFieldInterface;
use MenuManagerUltra\lib\CustomField\CustomFieldInfo;

class CustomField_Post implements CustomFieldInterface {

  const TYPE_KEY = 'Post';

  public function listAll() {
    
    $fields['post_title'] = $this->info('post_title')->asArray();

    return $fields;

  }

  public function info($field_key) {

    return new CustomFieldInfo('post_title', ['label' => 'Post Title', 'source' => self::TYPE_KEY]);

  }

  public function value($field_key, $post_id) {

    return get_post_field($field_key, $post_id);
    
  }
}

