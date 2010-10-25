<?php
  include('includes/bootstrap.php');

  allow_only_users();
  
  $title = "Welcome!";
  include("includes/top.php");
?>

<h2>Signed in successfully!</h2>

<?php
  include("includes/bottom.php");
?>