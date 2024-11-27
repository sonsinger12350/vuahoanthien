<?php

namespace MenuManagerUltra\util;

class MenuManagerUltra_Logger {

  public static $debug_enabled  = true;
  public static $debug_filepath = '/tmp/mmu-debug.log';

  public static function debug($message) {

    if (self::$debug_enabled) {
      return file_put_contents(self::$debug_filepath, '[' . date('Y-m-d h:i:s A') . '] ' . $message . "\n", FILE_APPEND);
    }
    
  }

  public static function warn($message) {
    return error_log($message);
  }

}

