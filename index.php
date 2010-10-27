<?php
  include('includes/bootstrap.php');
    
  $title = "";
  include("includes/top.php");
  
  $restaurant_list = Restaurant::list_all();
?>
<h1>Restaurants</h1>
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
<p>There are no restaurants in the system yet.</p>
<?php } ?>

</div>

<?php
  include("includes/bottom.php");
?>