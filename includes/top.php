<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    
    <title>Restaurantastic<?php if($title) echo " - $title"; ?></title>
    
    <link rel="stylesheet" href="media/css/reset.css" type="text/css" media="screen" charset="utf8">
    <link rel="stylesheet" href="media/css/master.css" type="text/css" media="screen" charset="utf8">
    
    <script type="text/javascript"
      src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js">
    </script>

  </head>
  
  <body>
    <div id="wrapper">
    	<div id="header">
        	<h1>Restaurantastic</h1>
        	<h2>Innovativ Programmering</h2>
        </div>
        <div id="menu">
          <?php if(is_signed_in()) { ?>
            <ul>
    	        <li><a href="index.php">All restaurants</a> &bull; </li>
    	        <li><a href="restaurants_manage.php">Manage your restaurants</a> &bull; </li>
    	        <li><a href="view_messages.php">View your messages</a> &bull; </li>
              <li><a href="sign_out.php">Sign out</a></li>
            </ul>
          <?php }
          else { ?>
            <ul>
    	        <li><a href="index.php">All restaurants</a> &bull; </li>
              <li><a href="sign_in.php">Sign in</a></li>
            </ul>
          <?php } ?>
        </div>
        <div id="content">
          <?php if(isset($_SESSION['notice'])) { ?>
            <div id="notice">
              <?php echo $_SESSION['notice']; ?>
            </div>
            <?php unset($_SESSION['notice']);
          } ?>