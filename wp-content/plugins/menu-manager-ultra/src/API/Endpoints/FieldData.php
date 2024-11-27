<?php

namespace MenuManagerUltra\API\Endpoints;

use MenuManagerUltra\API\ReturnValue;
use MenuManagerUltra\API\FieldDataReturnValue;
use MenuManagerUltra\lib\Constants;

class FieldData extends \WP_REST_Controller {

  public function register_routes() {

    register_rest_route(Constants::ROUTE_BASE , '/fields/custom/list',
      array(
        'methods' => \WP_REST_Server::READABLE,
        'callback' => [$this, 'customFieldsList'],
        'permission_callback' => function($request) {
          return current_user_can(Constants::ENDPOINT_PERMISSION_DEFAULT);
        }
      )
    );

    register_rest_route( Constants::ROUTE_BASE , '/fields/custom/settings',
      array(
        'methods' => \WP_REST_Server::READABLE,
        'callback' => [$this, 'customFieldsAppliedGet'],
        'permission_callback' => function($request) {
          return current_user_can(Constants::ENDPOINT_PERMISSION_DEFAULT);
        }
      )
    );

    register_rest_route( Constants::ROUTE_BASE , '/fields/custom/settings/save',
      array(
        'methods' => \WP_REST_Server::EDITABLE,
        'callback' => [$this, 'customFieldsAppliedSave'],
        'permission_callback' => function($request) {
          return current_user_can(Constants::ENDPOINT_PERMISSION_DEFAULT);
        }
      )
    );

  }

  public function customFieldsAppliedGet() {

    $fields = get_option('mmu_custom_fields_applied', []);
  
    $return = new FieldDataReturnValue();
    $return->status = ReturnValue::STATUS_SUCCESS;
    $return->fields = $fields;
    return $return->formatResponse();
  
  }
  
  public function customFieldsAppliedSave($data) {
  
    $return = new ReturnValue();
    
    if (isset($data['fields'])) {
  
      $field_settings = [];
  
      foreach($data['fields'] as $field_info) {
        if (!empty($field_info['field_key']) && !empty($field_info['enabled']) && $field_info['enabled'] == 1) {
  
          $save_data = ['enabled' => 1];
  
          /* @TODO instantiating a new field obj shouldn't really be necessary for each iteration */
          if ($field_obj = \MenuManagerUltra\lib\CustomField\CustomFieldManager::create($field_info['field_source'])) {
            $info = $field_obj->info($field_info['field_key']);
  
            $save_data = array_merge($info->asArray(), $save_data);
            $field_settings[] = $save_data;
          }
  
          
        }
      }
  
      update_option('mmu_custom_fields_applied', $field_settings);
      $return->status = ReturnValue::STATUS_SUCCESS;
    }
    else {
      $return->status = ReturnValue::STATUS_ERROR;
      $return->addMessage("No fields data passed to backend");
    }
  
    return $return->formatResponse();
    
  }
  
  
  public function customFieldsList() {
  
    $return = new FieldDataReturnValue();
    $return->status = ReturnValue::STATUS_ERROR;
  
    try {
      
      $fields =  \MenuManagerUltra\lib\CustomField\CustomFieldManager::listAll();
  
      usort(
        $fields,
        function ($a, $b) {
          return strcmp($a['field_label'], $b['field_label']);
        }
      );
  
      $return->fields = $fields;
      $return->status = ReturnValue::STATUS_SUCCESS;
      return $return->formatResponse();
  
    }
    catch(\Exception $e) {
      /* TODO: should type check exceptions to prevent sensitive info from hitting frontend */
      $return->status = ReturnValue::STATUS_ERROR;
      $return->message = $e->getMessage();
      return $return->formatResponse();
    }
  
  }

}



