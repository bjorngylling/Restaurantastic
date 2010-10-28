<?php
  include('includes/bootstrap.php');
    
  $title = "";
  include("includes/top.php");
  
  $restaurant_list = Restaurant::list_all();
  $food_types = Restaurant::list_all_food_types();
?>
<script type="text/javascript">
  $(document).ready(function(){
    $("#search").keyup(function() { 
      $.ajax({
        url: "ajax/search.php?search_string=" + $("#search").val(),
        success: function(data) {
          $(".restaurant_list").html(data);
        }
      });
    });
    
    $("input[id*='food_type-']").click(function() {
      var filter_string = ""
      
      $("input[id*='food_type-']").each(function() {
        if($(this).attr("checked") == true) {
          filter_string = filter_string + "(?=.*" + $(this).attr("name") + ")";
        }
      });
      
      if(filter_string == "") {
        $(".restaurant").each(function() {
          $(this).show();
        });
      }
      else {
        filter(new RegExp(filter_string, "i")); 
      }
    });
    
    
  });
  
  function filter(filter_regexp) {      
    $(".restaurant").each(function() {
      ($(this).children(".food_types").text().search(filter_regexp) < 0) ? $(this).hide() : $(this).show();  
    });
  }  
</script>

<h1>Restaurants</h1>
<div id="restaurant_search">
  <label for="search">Search: <input type="text" name="search" id="search"></label><br><br>
  <label>Food-types:</label><br><br>
  <?php 
  foreach($food_types as $food_type) { ?>
    <label for="food_type-<?php echo $food_type['id'] ?>">
      <input name="<?php echo $food_type['name'] ?>" id="food_type-<?php echo $food_type['id'] ?>" type="checkbox"> <?php echo $food_type['name'] ?>
    </label>
  <?php } ?>
</div>
<div class="restaurant_list">
<?php 
if($restaurant_list) {
  foreach($restaurant_list as $restaurant) { ?>
    <div class="restaurant">
      <p><a href="restaurant_view/<?php echo $restaurant->id ?>" class="restaurant_name"><?php echo $restaurant->name ?></a></p>
      <p class="food_types">Food-types: <?php echo implode(", ", $restaurant->food_types); ?></p>
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