<?php
  include('../includes/bootstrap.php');
  
  allow_only_users();
  
  $db = new Database;
  
  $db->query(
    "INSERT INTO reviews
     (restaurant_id, added_by_id, review_body)
     VALUES (?, ?, ?)",
    array($_POST['restaurant_id'], $user->id, $_POST['review_body']));