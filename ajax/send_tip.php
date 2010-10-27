<?php
  include('../includes/bootstrap.php');
  
  allow_only_users();
  
  $db = new Database;
  
  $db->query(
    "INSERT INTO messages
     (to_user_id, from_user_id, restaurant_id, message_body)
     VALUES (?, ?, ?, ?)",
    array(User::get_user_id_with_email($_POST['to_user']), $user->id, $_POST['restaurant_id'], $_POST['message_body']));