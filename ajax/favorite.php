<?php
  include('../includes/bootstrap.php');
  
  allow_only_users();
  
  if($_GET['add']) {
    $user->add_favorite_restaurant($_GET['restaurant_id']);
    echo "added";
  }
  else {
    $user->remove_favorite_restaurant($_GET['restaurant_id']);
    echo "removed";
  }