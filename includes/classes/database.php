<?php
/* includes/classes/database.php
   Database wrapper for mysqli 

*/

class Database {
  static private $instance = false;
  private $config = false;
  private $con = false;
  
  public function __construct() {
    $this->config = Config::get();
    $this->connect();
  }
  
  public function disconnect() {
    if($this->con) {
      $this->con->close();
      $this->con = false;
    }
  }
  
  public function query($query, $parameters = array()) {
    // Prepare the query
    $statement = $this->con->prepare($query);
    
    // If it's a static query $parameters will be empty, we don't need to bind
    if(sizeof($parameters) > 0) {
      $this->bind_parameters(&$statement, $parameters);
    }
    
    // Run the query
    $statement->execute();
    
    $result = $this->query_results_to_array($statement);
    
    if($this->config->debug) {
      $this->print_query($query, $parameters, $result);
    }
    
    $statement->close();
    
    return($result);
  }
  
  /*
  PRIVATE
  */
  
  private function connect() {
    if($this->con) {
      return true; // We're already connected
    }
    
    // Connect to the database
    $this->con = new mysqli($this->config->db_host, 
                            $this->config->db_user, 
                            $this->config->db_pass,
                            $this->config->db_name);
                            
    if($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
        . $mysqli->connect_error);
    }
                             
    return true;
  }
  
  private function bind_parameters(&$statement, $parameters) {
    // Construct the mysqli datatype string
    $data_types = "";
    
    foreach($parameters as $param)
      $data_types .= $this->var_to_mysqli_type($param);
    
    // Build the parameter array for the bind_param call
    $params = array($data_types);
    // The variables need to be sent in as references
    foreach($parameters as $key => $value)
      $params[] = &$parameters[$key];
    
    // Call the bind_param funktion on $statement with $params as parameters
    call_user_func_array(array(&$statement, 'bind_param'), $params);
  }
  
  private function var_to_mysqli_type($var) {
    if(is_int($var))
      return 'i';
    elseif(is_float($var))
      return 'd';
    else
      return 's';
  }
  
  private function query_results_to_array($statement) {
    $result = $statement->result_metadata();
    
    // Exit here if it's a query that returns no results
    // If it's a insert query we return the id
    if(!$result && $statement->insert_id) {
      $id = $statement->insert_id;
      $statement->free_result();
      return $id;
    }
    if(!$result)
      return true;
    
    // Create an array of references to elements in another array,
    // one for each field in the table so we can bind the results to
    // the elements and then access them easily later on.
    foreach($result->fetch_fields() as $field)
      $fields[] = &$values[$field->name];
    
    call_user_func_array(array(&$statement, "bind_result"), $fields);
    
    // Fetch the results row by row and transfer them to our
    // result array which we will return
    while($statement->fetch()) {
      foreach($values as $key => $val)
        $temp[$key] = $val;
      
      $results[] = $temp;
    }
    
    $statement->free_result();
    
    return $results;
  }
  
  private function print_query($query, $parameters, $result) {
    for ($i = 0; $i < count($parameters); $i++) {
      $query = preg_replace('/\?/', "`".$parameters[$i]."`", $query, 1);
    }

    echo "Result for query \"$query\" <br />";
    print_r($result);
    echo "<br /><br />";
  }
  
}
