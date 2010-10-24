<?php
  include('includes/bootstrap.php');
  
  if(is_signed_in()) {
    // Already signed in, can't sign up while logged in
    set_notice("You are already signed in. Sign out and try again.");
    redirect_to(Config::get()->signed_in_page());
    exit;
  }
  elseif(isset($_POST['username']) && isset($_POST['email']) {
    if(User::create_new($_POST['username'])) {
      // log in the new user here
      
      //redirect_to("sign_in.php");
    }
    else{
      // Make sure there's no old data in post when we print the form
      $_POST = array();
    }
  }
  
  $title = "Sign up - Facebook account";
  include("includes/top.php");
  
  $username = isset($_SESSION['name']) ? $_SESSION['name'] : $_POST['username'];
?>

<form action="" method="post">
  <fieldset>
    <legend>Enter information for your Facebook login</legend>
    <label for="username">Username: </label>
    <input name="username" type="text" id="username" value="<?php echo $username; ?>" /><br />
    <label for="email">Email: </label>
    <input name="email" type="text" id="email" value="<?php echo $email; ?>" /><br />
    <input name="submit" type="submit" id="submit" class="button" value="Sign up!" />
  </fieldset>
</form>

<?php
  include("includes/bottom.php");
?>