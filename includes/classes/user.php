<?php
/* includes/classes/user.php
   User handling

*/

class User {
  public $id;
  public $username;
  
  private $db = false;
  private $config = false;
  
  function __construct($id = false) {
    $this->config = Config::get();
    $this->db = new Database();
    
    if($id)
      $this->load_user($id);
  }
  
  public function create_new($username, $password) {    
    // Make sure the user doesn't exist
    $username_query = $this->db->query(
      "SELECT id 
       FROM users 
       WHERE username = ?", 
      array($username));
    
    if($username_query) {
      set_notice("Username already exists, please try another usename");
      return false;
    }
    
    // Create the user
    $id = $this->db->query(
      "INSERT INTO users
       (username, password)
       VALUES (?, ?)",
      array($username, hash_password($password)));
    
    $this->load_user($id);
    
    return true;
  }
  
  public function oauth_create_new($oauth_id, $username) {
    
  }
  
  public function sign_in($username, $password) {
    $user_data = $this->db->query(
      "SELECT id, username
       FROM users 
       WHERE username = ? 
       AND password = ?", 
      array($username, hash_password($password)));
    
    if($user_data) {
      $this->load_user($user_data[0]['id'], 
        $user_data[0]['username']);
      return true;
    }
    else {
      return false;
    }    
  }
  
  public function oauth_sign_in($oauth_id) {
    $user_data = $this->db->query(
      "SELECT id, username
       FROM users 
       WHERE oauth_id = ?", 
      array($oauth_id));
      
    if($user_data) {
      $this->load_user($user_data[0]['id'], 
        $user_data[0]['username']);
      return true;
    }
    else {
      return false;
    }
  }
  
  private function load_user($id, $username = false, $stylesheet_id = false) {
    if($username AND $stylesheet_id) {
      $this->id = $id;
      $this->username = $username;
    }
    else {
      $user_data = $this->db->query(
        "SELECT username 
         FROM users 
         WHERE id = ?", 
        array($id));
      $this->id = $id;
      $this->username = $user_data[0]['username'];
    }
  }
  
  private function hash_password($password) {
    return sha1($password.$this->config->db_salt);
  }
  
}