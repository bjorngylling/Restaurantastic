<?php
  include('includes/bootstrap.php');
  
  allow_only_users();
  
  $title = "Listing your messages";
  include("includes/top.php");
  
  $message_list = $user->list_all_messages();
?>

<h2>Your messages</h2>
<div class="restaurant_list">
<?php 
if($message_list) {
  foreach($message_list as $message) { 
    $from_user = new User($message['from_user_id']);
    $restaurant = new Restaurant($message['restaurant_id']);
    ?>
    <div>
      <p>
        <?php echo $from_user->name ?> has tipped you about <a href="restaurant_view.php?id=<?php echo $restaurant->id; ?>"><?php echo $restaurant->name ?></a><br>
        Message:<br>
        <?php echo $message['message_body']; ?>
      </p>
    </div>
  <?php } 
}
else { ?>
  <p>You have no messages! :(</p>
<?php } ?>

</div>

<?php
  include("includes/bottom.php");
?>