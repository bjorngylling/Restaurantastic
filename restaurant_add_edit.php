<?php
  include('includes/bootstrap.php');

  allow_only_users();
  
  if($_POST['submit']) {
    $restaurant = new Restaurant($_POST['id']);
      
    $restaurant->name = $_POST['name'];
    $restaurant->opening_time = $_POST['opening_time'];
    $restaurant->closing_time = $_POST['closing_time'];
    $restaurant->street_address = $_POST['street_address'];
    $restaurant->postal_address = $_POST['postal_address'];
    $restaurant->food_types = $_POST['food_types'];
    $restaurant->latitude = $_POST['latitude'];
    $restaurant->longitude = $_POST['longitude'];
    $restaurant->added_by = $user;
    
    $restaurant->save();
    
    redirect_to("restaurant_view.php?id=" . $restaurant->id);
  }

  $restaurant = new Restaurant($_GET['id']);

  $title = "Add a restaurant";
  include("includes/top.php");
  
  $food_types = Restaurant::list_all_food_types();
?>
<script type="text/javascript"
  src="http://maps.google.com/maps/api/js?sensor=false">
</script>
<script type="text/javascript">
  var map;
  var marker;
      
  $(document).ready(function(){
    // Google
    var myOptions = {
      zoom: 15,
      center: new google.maps.LatLng("<?php echo $restaurant->latitude . '", "' . $restaurant->longitude; ?>"),
      disableDefaultUI: true,
      navigationControl: true,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    
    marker = new google.maps.Marker({
      position: new google.maps.LatLng("<?php echo $restaurant->latitude . '", "' . $restaurant->longitude; ?>"),
      draggable: true,
      map: map, 
      title: ""
    });
    
    google.maps.event.addListener (marker, 'drag', function (event) {
      var position = marker.getPosition();
      $("input[name=latitude]").val(position.lat());
      $("input[name=longitude]").val(position.lng());
    });
    
    $("input[name=street_address]").change(function() {
      set_map_to($("#street_address").val() + " " + $("input[name=postal_address]").val());
    });
    $("input[name=postal_address]").change(function() {
      set_map_to($("#street_address").val() + " " + $("input[name=postal_address]").val());
    });
  });

  function set_map_to(address) {
    // Google
    geocoder = new google.maps.Geocoder();
    
    geocoder.geocode( { 'address': address}, function(result, status) {
      var position = result[0].geometry.location;
      map.setCenter(position);
      marker.setPosition(position);
      marker.setMap(map);
      $("input[name=latitude]").val(position.lat());
      $("input[name=longitude]").val(position.lng());
    });
  }

</script>

<div class="left_col" id="add_restaurant"> 
<form action="" method="post">
  <fieldset>
    <legend>Add a restaurant</legend>
    <label for="name">Name: </label>
    <input name="name" type="text" id="name" value="<?php echo $restaurant->name; ?>"><br>
    <label for="street_address">Street address: </label>
    <input name="street_address" type="text" id="street_address" value="<?php echo $restaurant->street_address; ?>"><br>
    <label for="postal_address">Postal address: </label>
    <input name="postal_address" type="text" id="postal_address" value="<?php echo $restaurant->postal_address; ?>"><br>
    <label for="opening_time">Opens at: </label>
    <input name="opening_time" type="text" id="opening_time" value="<?php echo $restaurant->opening_time; ?>"><br>
    <label for="closing_time">Closes at: </label>
    <input name="closing_time" type="text" id="closing_time" value="<?php echo $restaurant->closing_time; ?>"><br>
    <label>Food-types:</label><br><br>
    <?php 
    foreach($food_types as $food_type) { ?>
      <label for="<?php echo strtolower($food_type['name']) ?>">
        <input name="food_types[<?php echo $food_type['id'] ?>]" id="<?php echo strtolower($food_type['name']) ?>" type="checkbox" <?php if(isset($restaurant->food_types[$food_type['id']])) echo 'checked="checked"'; ?> value="<?php echo $food_type['name'] ?>"> <?php echo $food_type['name'] ?>
      </label>
    <?php } ?>
    <input name="latitude" type="hidden" value="<?php echo $restaurant->latitude; ?>">
    <input name="longitude" type="hidden" value="<?php echo $restaurant->longitude; ?>">
    <input name="id" type="hidden" value="<?php echo $restaurant->id; ?>">
    <input name="submit" type="submit" class="button" value="<?php if($restaurant->id) { echo "Update it!"; } else { echo "Add it!"; } ?>">
  </fieldset>
</form>
</div>
<div id="map_canvas" class="right_col"></div>

<div class="clearfloats"></div>

<?php
  include("includes/bottom.php");
?>