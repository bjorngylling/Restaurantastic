<?php 
  require_once('includes/bootstrap.php');

  session_destroy();
  
  redirect_to('index.php');