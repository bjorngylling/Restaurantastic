<?php
  include('includes/bootstrap.php');
  
  if(is_signed_in()) {
    set_notice("You are already signed in. Sign out and try again.");
    // Already signed in, can't sign up while logged in
    redirect_to(Config::get()->signed_in_page);
    exit;
  }
  elseif(isset($_GET['code'])) {
    $url = "https://graph.facebook.com/oauth/access_token?client_id=" . Config::get()->fb_app_id 
      . "&redirect_uri=http://tdp013.gyllingdata.se/restaurantastic/sign_in_oauth.php&client_secret=" . Config::get()->fb_secret
      . "&code=" . $_GET['code'];
    $oauth_token = file_get_contents($url);
    
    $url = "https://graph.facebook.com/me?{$oauth_token}";
    $fb_user = json_decode(file_get_contents($url));
    
    $user = new User;
    if($user->oauth_sign_in($fb_user->id)) {
      // Make sure the session variables are cleared
      $_SESSION = array();
      
      $_SESSION['user_id'] = $user->id;
      redirect_to(Config::get()->signed_in_page);
    }
    else {
      $_SESSION['oauth_id'] = $fb_user->id;
      $_SESSION['name'] = $fb_user->name;
      set_notice("This is your first time at Restaurantastic. Please select a name to be associated with your Facebook login!");
      redirect_to('sign_up_oauth.php');
    }
    
  }
  