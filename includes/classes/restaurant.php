<?php
/* includes/restaurant.php
   A restaurant

*/

class Restaurant {
  public $name;
  public $opening_time;
  public $closing_time;
  public $street_address;
  public $postal_address;
  public $food_types;
  public $latitude;
  public $longitude;
  
  private $id;
  private $added_by;
  private $reviews = array();
  
  private $db = false;
  private $config = false;
  
  function __construct($id = false) {
    $this->config = Config::get();
    $this->db = new Database();
        
    if($id)
      $this->load($id);
  }
  
  public function __get($name) {
    switch($name) {
      case "added_by":
        return $this->added_by;
        break;
      case "id":
        return $this->id;
        break;
      case "reviews":
        return $this->reviews;
        break;
    }
  }
  
  public function __set($name, $value) {
    switch($name) {
      case "added_by":
        if(!isset($this->added_by)) {
          $this->added_by = $value;
        }
        break;
    }
  }
  
  public static function list_all($user_id = false) {
    $db = new Database();
    if($user_id) {
      $list_all = $db->query( 
        "SELECT id
         FROM restaurants
         WHERE added_by_id = ?
         ORDER BY id DESC",
        array($user_id));
    }
    else {
      $list_all = $db->query(
        "SELECT id
         FROM restaurants
         ORDER BY id DESC");
    }
    
    $result = array();
    foreach($list_all as $restaurant) {
      $result[] = new Restaurant($restaurant['id']);
    }
    
    return $result;
  }
  
  public static function search($search_string) {
    $db = new Database();
    $search_string = '%' . $search_string . '%';
    
    $search_list = $db->query(
      "SELECT id
       FROM restaurants
       WHERE lower(name) LIKE lower(?)
       ORDER BY id DESC",
      array($search_string));
    
    if(!$search_list) {
      return false;
    }
      
    $result = array();    
    foreach($search_list as $restaurant) {
      $result[] = new Restaurant($restaurant['id']);
    }
    
    return $result;
  }
  
  public static function list_all_food_types() {
    $db = new Database();
    return $db->query("SELECT * FROM food_types");
  }
  
  public function delete() {
    $this->db->query(
      "DELETE FROM restaurants
       WHERE id = ?", 
      array($this->id));
      
    $this->db->query(
      "DELETE FROM restaurants_food_types
       WHERE restaurant_id = ?", 
      array($this->id));
      
    $this->db->query(
      "DELETE FROM users_restaurants
       WHERE restaurant_id = ?", 
      array($this->id));
      
    $this->db->query(
      "DELETE FROM reviews
       WHERE restaurant_id = ?", 
      array($this->id));
  }
  
  public function save() {
    if(!$this->id) {
      $this->create_new();
    }
    else {      
      $result = $this->db->query(
        "UPDATE restaurants
         SET name=?, opening_time=?, closing_time=?, street_address=?, postal_address=?, latitude=?, longitude=?
         WHERE id = ?",
         array($this->name, $this->opening_time, $this->closing_time, $this->street_address, 
           $this->postal_address, (float)$this->latitude, (float)$this->longitude, $this->id));
    }
    
    $this->save_food_types();
    
    $this->load($this->id);
  }
  
  private function save_food_types() {
    foreach($this->list_all_food_types() as $food_type) {
      if(isset($this->food_types[$food_type['id']])) {
        $this->db->query(
          "INSERT INTO restaurants_food_types
           (restaurant_id, food_type_id)
           VALUES (?, ?)",
          array($this->id, $food_type['id']));
      }
      else {
        $this->db->query(
          "DELETE FROM restaurants_food_types
           WHERE restaurant_id=? AND food_type_id=?",
          array($this->id, $food_type['id']));
      }
    }
  }
  
  private function create_new() {
    // Make sure a restaurant with the same name and postal address doesn't exist already
    $already_exists = $this->db->query(
      "SELECT id 
       FROM restaurants 
       WHERE name = ?
       AND postal_address = ?", 
      array($this->name, $this->postal_address));
    
    if($already_exists) {
      set_notice("A restaurant with that name and postal address already exists!");
      return false;
    }
    
    // Add the restaurant
    $id = $this->db->query(
      "INSERT INTO restaurants
       (name, opening_time, closing_time, street_address, postal_address, latitude, longitude, added_by_id)
       VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
      array($this->name, $this->opening_time, $this->closing_time, $this->street_address, 
         $this->postal_address, (float)$this->latitude, (float)$this->longitude, $this->added_by->id));
    
    $this->id = $id;
  }
  
  private function load($id) {
    $data = $this->db->query(
      "SELECT name, opening_time, closing_time, street_address, postal_address, latitude, longitude, added_by_id
       FROM restaurants
       WHERE id = ?", 
      array($id));
    
    $this->id = $id;
    $this->name = $data[0]['name'];
    $this->opening_time = $data[0]['opening_time'];
    $this->closing_time = $data[0]['closing_time'];
    $this->street_address = $data[0]['street_address'];
    $this->postal_address = $data[0]['postal_address'];
    $this->latitude = $data[0]['latitude'];
    $this->longitude = $data[0]['longitude'];
    $this->added_by = new User($data[0]['added_by_id']);
      
    $food_types_db = $this->db->query(
      "SELECT food_types.*
       FROM food_types, restaurants_food_types AS rft
       WHERE food_types.id = rft.food_type_id
       AND rft.restaurant_id = ?",
      array($this->id));
      
    if($food_types_db) {
      foreach($food_types_db as $food_type) {
        $this->food_types[$food_type['id']] = $food_type['name'];
      }
    }
    
    $reviews_db = $this->db->query(
      "SELECT added_by_id, review_body
       FROM reviews
       WHERE restaurant_id = ?", 
      array($this->id));
    if($reviews_db) {
      foreach($reviews_db as $review) {
        $this->reviews[] = array('added_by' => new User($review['added_by_id']),
          'review_body' => $review['review_body']);
      }
    }
  }
}