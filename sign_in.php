<?php  
  include('includes/bootstrap.php');
  
  if(is_signed_in()) {
    // Already logged in, redirect to book-list
    redirect_to(Config::get()->signed_in_page());
    exit;
  }
  elseif(isset($_POST['username'], $_POST['password'])) {
    $user = new User();
    if($user->sign_in($_POST['username'], $_POST['password'])) {
      // Make sure the session variables are cleared
      $_SESSION = array();
      
      $_SESSION['user_id'] = $user->id;
      redirect_to(Config::get()->signed_in_page());
    }
    else {
      set_notice("Incorrect username or password!");
    }
  }
  
  $title = "Welcome!";
  include("includes/top.php");
  
  $fb_sign_in_url = "https://graph.facebook.com/oauth/authorize?client_id=" . Config::get()->fb_app_id . "&amp;redirect_uri=http://tdp013.gyllingdata.se/restaurantastic/sign_in_oauth.php";
?>
<div id="fb-root"></div>

<form action="" method="post">
  <fieldset>
    <legend>Sign in</legend>
    <label for="username">Username: </label>
    <input name="username" type="text" id="username" /><br />
    <label for="password">Password: </label>
    <input name="password" type="password" id="password" /><br />
    <input name="submit" type="submit" class="button" value="Sign in!" /><br /><br />
    <a href="<?php echo $fb_sign_in_url; ?>"><img src="media/fb_login_button.jpg" alt="Sign in using Facebook"></a>
  </fieldset>
</form>
<?php
  include("includes/bottom.php");
?>