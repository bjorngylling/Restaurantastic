<?php
/* includes/classes/user.php
   User handling

*/

class User {
  public $id;
  public $stylesheet_id;
  public $username;
  
  private $config = false;
  
  function __construct($id = false) {
    $this->config = Config::get_instance();
    if($id)
      $this->load_user($id);
  }
  
  public static function create_new($username, $password, $stylesheet_id = false) {
    $user = new User();
    
    if(!$stylesheet_id)
      $stylesheet_id = $user->config->default_stylesheet_id;
      
    // Make sure the user doesn't exist
    $username_query = Database::get_instance()->query(
      "SELECT id 
       FROM users 
       WHERE username = ?", 
      array($username));
    
    if($username_query) {
      set_notice("Username already exists, please try another usename");
      return false;
    }
    
    // Create the user
    $id = Database::get_instance()->query(
      "INSERT INTO users
       (username, password, stylesheet_id)
       VALUES (?, ?, ?)",
      array($username, $user->hash_password($password), $stylesheet_id));
    
    $user->load_user($id);
    
    return $user;
  }
  
  public static function sign_in($username, $password) {
    $user = new User();
    
    $user_data = Database::get_instance()->query(
      "SELECT id, username, stylesheet_id 
       FROM users 
       WHERE username = ? 
       AND password = ?", 
      array($username,
        $user->hash_password($password)));
    
    if($user_data) {
      $user->load_user($user_data[0]['id'], 
        $user_data[0]['username'], 
        $user_data[0]['stylesheet_id']);
      return $user;
    }
    else {
      return false;
    }    
  }
  
  public function set_stylesheet($stylesheet_id) {
    Database::get_instance()->query(
      "UPDATE users
       SET stylesheet_id = ?
       WHERE id = ?",
      array($stylesheet_id, $this->id));
    $this->stylesheet_id = $stylesheet_id;
  }
  
  private function load_user($id, $username = false, $stylesheet_id = false) {
    if($username AND $stylesheet_id) {
      $this->id = $id;
      $this->username = $username;
      $this->stylesheet_id = $stylesheet_id;
    }
    else {
      $user_data = Database::get_instance()->query(
        "SELECT username, stylesheet_id 
         FROM users 
         WHERE id = ?", 
        array($id));
      $this->id = $id;
      $this->username = $user_data[0]['username'];
      $this->stylesheet_id = $user_data[0]['stylesheet_id'];
    }
  }
  
  private function hash_password($password) {
    return sha1($password.$this->config->db_salt);
  }
  
}