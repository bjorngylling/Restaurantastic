<?php
/* includes/config.php
   Application settings
   Stored in a singleton class
*/

class Config {  
  static private $instance = false;
  
  public $db_host = '';
  public $db_user = '';
  public $db_pass = '';
  public $db_name = '';
  public $db_salt = '';
  
  public $fb_app_id = '';
  public $fb_secret = '';
  
  static public function get() {
    if (!self::$instance) {
      self::$instance = new Config;
    }
 
    return self::$instance;
  }

}