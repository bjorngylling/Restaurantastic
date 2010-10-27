<?php
  include('includes/bootstrap.php');
  
  allow_only_users();
  
  
  $title = "Listing your restaurants";
  include("includes/top.php");
  
  $restaurant_list = Restaurant::list_all($user->id);
?>
<div class="left_col">

<h2>Your restaurants</h2>
<div class="restaurant_list">
<?php 
if($restaurant_list) {
  foreach($restaurant_list as $restaurant) { ?>
    <div class="restaurant">
      <p><a href="restaurant_view.php?id=<?php echo $restaurant['id'] ?>"><?php echo $restaurant['name'] ?></a></p>
    </div>
  <?php } 
}
else { ?>
<p>You have not added any restaurants!</p>
<?php } ?>

<p><br><br><a href="restaurant_add_edit.php">Add a new restaurant</a></p>
</div>
</div>
<div class="right_col">
  <h2>Your favorites</h2>
  
  <div class="restaurant_list">
  <?php foreach($user->favorites as $id => $name) { ?>
    <p><a href="restaurant_view.php?id=<?php echo $id ?>"><?php echo $name ?></a></p>
  <?php } ?>
  </div>
</div>

<?php
  include("includes/bottom.php");
?>