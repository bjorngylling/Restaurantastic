<?php  
  include('includes/bootstrap.php');
  
  if(is_signed_in()) {
    // Already logged in, redirect to book-list
    redirect_to('my_books.php');
    exit;
  }
  elseif(isset($_POST['username'], $_POST['password'])) {
    if($user = User::sign_in($_POST['username'], $_POST['password'])) {
      // Make sure the session variables are cleared
      $_SESSION = array();
      
      $_SESSION['user_id'] = $user->id;
      redirect_to('my_books.php');
    }
    else {
      set_notice("Incorrect username or password!");
    }
  }
  elseif($fb_auth = get_facebook_cookie()) {
    if($user = User::oauth_sign_in($fb_auth['uid'])) {
      // Make sure the session variables are cleared
      $_SESSION = array();
      
      $_SESSION['user_id'] = $user->id;
    }
    elseif($user = User::oauth_create_new($fb_auth['uid'])) {
      // Make sure the session variables are cleared
      $_SESSION = array();
      
      $_SESSION['user_id'] = $user->id;
      
      set_notice("This is your first time at Restaurantastic. An account has been created and associated with your Facebook login!");
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
    <input name="submit" type="submit" class="button" value="Sign in!" /><br /><br />
    <a href="" onClick="FB.login();"><img src="http://developers.facebook.com/images/devsite/login-button.png"></a>
  </fieldset>
</form>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
  FB.init({appId: '<?php echo Config::get()->fb_app_id ?>', status: true,
           cookie: true});
  FB.Event.subscribe('auth.login', function(response) {
    window.location.reload();
  });
</script>
<?php
  include("includes/bottom.php");
?>