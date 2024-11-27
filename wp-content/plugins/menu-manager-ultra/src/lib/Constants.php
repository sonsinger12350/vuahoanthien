<?php

namespace MenuManagerUltra\lib;

class Constants {

  const KEY_ITEM_SEEN_BY_FRONTEND = 'seen_by_frontend';
  const POST_TYPES_FREE = ['post', 'page'];
  const CHILDREN_MAX_RECURSION = 1000; //Just to prevent runaway recursion when moving children of children into place

  const ENDPOINT_PERMISSION_DEFAULT = 'edit_theme_options';
  const ROUTE_BASE = 'mm_ultra/v1';
  
  const FIELD_KEY_ID = ID;

}

