<?php
/* includes/classes/user.php
   User handling

*/

class User {
  public $id;
  public $name;
  
  private $db = false;
  private $config = false;
  
  function __construct($id = false) {
    $this->config = Config::get();
    $this->db = new Database();
    
    if($id)
      $this->load_user($id);
  }
  
  public function create_new($email, $password, $name) {    
    // Make sure the user doesn't exist
    $email_query = $this->db->query(
      "SELECT id 
       FROM users 
       WHERE email = ?", 
      array($email));
    
    if($email_query) {
      set_notice("There is already a account with that email in the system!");
      return false;
    }
    
    // Create the user
    $id = $this->db->query(
      "INSERT INTO users
       (email, name, password)
       VALUES (?, ?, ?)",
      array($email, $name, $this->hash_password($password)));
    
    $this->load_user($id);
    
    return true;
  }
  
  public function oauth_create_new($email, $name, $oauth_id) {
    // Make sure the user doesn't exist
    $email_query = $this->db->query(
      "SELECT id 
       FROM users 
       WHERE email = ?", 
      array($email));
      
    if($email_query) {
      set_notice("There is already a account with that email in the system!");
      return false;
    }
    
    // Create the user
    $id = $this->db->query(
      "INSERT INTO users
       (email, name, oauth_id)
       VALUES (?, ?, ?)",
      array($email, $name, $oauth_id));
    
    $this->load_user($id);
    
    return true;
  }
  
  public function sign_in($email, $password) {
    $user_data = $this->db->query(
      "SELECT id
       FROM users 
       WHERE email = ? 
       AND password = ?", 
      array($email, $this->hash_password($password)));
    
    if($user_data) {
      $this->load_user($user_data[0]['id']);
      return true;
    }
    else {
      return false;
    }    
  }
  
  public function oauth_sign_in($oauth_id) {
    $user_data = $this->db->query(
      "SELECT id
       FROM users 
       WHERE oauth_id = ?", 
      array($oauth_id));
      
    if($user_data) {
      $this->load_user($user_data[0]['id']);
      return true;
    }
    else {
      return false;
    }
  }
  
  private function load_user($id) {
    $user_data = $this->db->query(
      "SELECT name, email 
       FROM users 
       WHERE id = ?", 
      array($id));
    $this->id = $id;
    $this->name = $user_data[0]['name'];
    $this->email = $user_data[0]['email'];
  }
  
  private function hash_password($password) {
    return sha1($password.$this->config->db_salt);
  }
  
}