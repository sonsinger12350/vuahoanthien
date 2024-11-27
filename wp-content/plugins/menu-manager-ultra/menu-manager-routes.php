<?php

add_action( 'rest_api_init', function () {

  $controller = new \MenuManagerUltra\API\Endpoints\FieldData();
  $controller->register_routes();

  $controller = new \MenuManagerUltra\API\Endpoints\Posts();
  $controller->register_routes();

  $controller = new \MenuManagerUltra\API\Endpoints\Menus();
  $controller->register_routes();

  $controller = new \MenuManagerUltra\API\Endpoints\MenuItems();
  $controller->register_routes();

} );
