<?php  
  include('includes/bootstrap.php');
  
  if(is_signed_in()) {
    // Already logged in, redirect to book-list
    redirect_to('my_books.php');
    exit;
  }
  elseif(isset($_POST['username'], $_POST['password'])) {
    $user = User::sign_in($_POST['username'], $_POST['password']);
    if($user != false) {
      // Make sure the session variables are cleared
      $_SESSION = array();
      
      $_SESSION['user_id'] = $user->id;
      redirect_to('my_books.php');
    }
    else {
      set_notice("Incorrect username or password!");
    }
  }
  
  $title = "Welcome!";
  include("includes/top.php");
?>
<form action="" method="post">
  <fieldset>
    <legend>Sign in</legend>
    <label for="username">Username: </label>
    <input name="username" type="text" id="username" /><br />
    <label for="password">Password: </label>
    <input name="password" type="password" id="password" /><br />
    <input name="submit" type="submit" id="submit" value="Sign in!" />
  </fieldset>
</form>
<?php
  include("includes/bottom.php");
?>