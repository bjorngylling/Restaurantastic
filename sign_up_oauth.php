<?php
  include('includes/bootstrap.php');
  
  if(is_signed_in()) {
    // Already signed in, can't sign up while logged in
    set_notice("You are already signed in. Sign out and try again.");
    redirect_to(Config::get()->signed_in_page);
    exit;
  }
  elseif(isset($_POST['name']) && isset($_POST['email'])) {
    $user = new User;
    
    if($user->oauth_create_new($_POST['email'], $_POST['name'], $_SESSION['oauth_id'])) {
      // sign in the user
      $_SESSION = array();
      $_SESSION['user_id'] = $user->id;
      redirect_to(Config::get()->signed_in_page);
    }
    else{
      // Make sure there's no old data in post when we print the form
      $_POST = array();
    }
  }
  
  $title = "Sign up - Facebook account";
  include("includes/top.php");
  
  $name = isset($_SESSION['name']) ? $_SESSION['name'] : $_POST['name'];
  unset($_SESSION['name']);
?>

<form action="" method="post">
  <fieldset>
    <legend>Enter information for your Facebook login</legend>
    <label for="email">Email: </label>
    <input name="email" type="text" id="email" value="<?php echo $email; ?>" /><br />
    <label for="name">Name: </label>
    <input name="name" type="text" id="name" value="<?php echo $name; ?>" /><br />
    <input name="submit" type="submit" id="submit" class="button" value="Sign up!" />
  </fieldset>
</form>

<?php
  include("includes/bottom.php");
?>