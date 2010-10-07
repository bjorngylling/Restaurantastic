<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <meta http-equiv="Content-type" content="text/html; charset=utf8" />
    
    <title>Restaurantastic<?php if($title) echo " - $title"; ?></title>
    
    <link rel="stylesheet" href="media/css/reset.css" type="text/css" media="screen" charset="utf8" />
    <link rel="stylesheet" href="media/css/master.css" type="text/css" media="screen" charset="utf8" />
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
    	        <li><a href="index.php">index</a> &bull; </li>
              <li><a href="sign_out.php">Sign out</a></li>
            </ul>
          <?php }
          else { ?>
            <ul>
    	        <li><a href="index.php">index</a> &bull; </li>
    	        <li><a href="sign_up.php">Sign up</a> &bull; </li>
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