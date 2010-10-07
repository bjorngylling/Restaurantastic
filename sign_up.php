<?php
  include('includes/bootstrap.php');
  
  if(is_signed_in()) {
    set_notice("You can't sign up while signed in. Sign out and try again.");
    // Already logged in, can't sign up while logged in
    redirect_to('my_books.php');
    exit;
  }
  elseif(isset($_POST['username'], $_POST['password'], $_POST['confirm'])) {
    if($_POST['password'] != $_POST['confirm']) {
      set_notice("Password confirmation failed, retype your password and try again.");
    }
    else {
      if(User::create_new($_POST['username'], $_POST['password'])) {
        redirect_to("sign_in.php");
      }
      else{
        // Make sure there's no old data in post when we print the form
        $_POST = array();
      }
    }
  }
  
  $title = "Sign up";
  include("includes/top.php");
?>

<form action="" method="post">
  <fieldset>
    <legend>Sign up</legend>
    <label for="username">Username: </label>
    <input name="username" type="text" id="username" value="<?php echo $_POST['username']; ?>" /><br />
    <label for="password">Password: </label>
    <input name="password" type="password" id="password" /><br />
    <label for="confirm">Confirm: </label>
    <input name="confirm" type="password" id="confirm" /><br />
    <input name="submit" type="submit" id="submit" value="Sign up!" />
  </fieldset>
</form>

<?php
  include("includes/bottom.php");
?>