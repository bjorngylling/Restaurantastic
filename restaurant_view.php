<?php
  include('includes/bootstrap.php');
    
  if(!isset($_GET['id'])) {
    redirect_to("restaurants.php");
  }
  $restaurant = new Restaurant($_GET['id']);
  
  $title = $restaurant->name;
  include("includes/top.php");
?>

<script type="text/javascript"
  src="http://maps.google.com/maps/api/js?sensor=false">
</script>
<script type="text/javascript">
  $(document).ready(function(){
    $("#send_tip_box").hide();
    $("#write_review_box").hide();
    
    var myOptions = {
      zoom: 16,
      center: new google.maps.LatLng("<?php echo $restaurant->latitude . '", "' . $restaurant->longitude; ?>"),
      disableDefaultUI: true,
      navigationControl: true,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    
    var marker = new google.maps.Marker({
      position: new google.maps.LatLng("<?php echo $restaurant->latitude . '", "' . $restaurant->longitude; ?>"),
      map: map, 
      title: "<?php echo $restaurant->name; ?>"
    });
    
    $("#favorite_link").click(function() {
      var url;
      if($("#favorite_link").attr("class") == "add_favorite") {
        url = "http://tdp013.gyllingdata.se/restaurantastic/ajax/favorite.php?add=1&restaurant_id=<?php echo $restaurant->id; ?>";
      }
      else if($("#favorite_link").attr("class") == "remove_favorite") {
        url = "http://tdp013.gyllingdata.se/restaurantastic/ajax/favorite.php?add=0&restaurant_id=<?php echo $restaurant->id; ?>";
      }
      $.ajax({
        url: url,
        success: function(data) {
          if($("#favorite_link").attr("class") == "add_favorite") {
            $("#favorite_link").html("Remove from favorites");
            $("#favorite_link").attr("class", "remove_favorite");
          }
          else if($("#favorite_link").attr("class") == "remove_favorite") {
            $(".remove_favorite").html("Add to favorites");
            $(".remove_favorite").attr("class", "add_favorite");
          }
        }
      });
    });
    $("#toggle_send_tip").click(function() {
      $("#write_review_box").slideUp();
      $("#send_tip_box").slideToggle();
    });
    $("#tip_send").click(function() {
      $.post("ajax/send_tip.php", { restaurant_id: <?php echo $restaurant->id; ?>, to_user: $("#tip_to_user").val(), message_body: $("#tip_message").val() } );
      $("#send_tip_box").slideUp();
      $("#tip_to_user").val("");
      $("#tip_message").val("");
    });
    $("#toggle_write_review").click(function() {
      $("#send_tip_box").slideUp();
      $("#write_review_box").slideToggle();
    });
    $("#review_send").click(function() {
      $.post("ajax/add_review.php", { restaurant_id: <?php echo $restaurant->id; ?>, review_body: $("#review_body").val() } );
      $("#write_review_box").slideUp();
      $("#review_body").val("");
    });
  });

</script>
<div id="restaurant_info" class="left_col">
<h1><?php echo $restaurant->name; ?></h1>
<p>
  <?php echo $restaurant->street_address; ?><br>
  <?php echo $restaurant->postal_address; ?><br>
  Open <?php echo $restaurant->opening_time; ?> to <?php echo $restaurant->closing_time; ?><br>
  Food-types: <?php echo implode(", ", $restaurant->food_types); ?>
</p>
<p>Added by: <?php echo $restaurant->added_by->name; ?></p>
<?php if($restaurant->added_by->id == $user->id) { ?>
  <p><a href="http://tdp013.gyllingdata.se/restaurantastic/restaurant_add_edit.php?id=<?php echo $restaurant->id; ?>">Edit this restaurant</a></p>
<?php } ?>
<?php if(is_signed_in()) { ?>
  <p><a href="#" id="favorite_link" class="<?php if(isset($user->favorites[$restaurant->id])) { echo 'remove_favorite">Remove from favorites'; } else { echo 'add_favorite">Add to favorites'; } ?></a></p>
  <p><a href="#" id="toggle_send_tip">Send this restaurant as a tip to someone</a></p>
  <p id="send_tip_box">
    <label for="tip_to_user">User email:<br><input name="tip_to_user" id="tip_to_user" type="text"></label><br>
    <label for="tip_message">Message:</label><br>
    <textarea name="tip_message" id="tip_message" rows="4" cols="20"></textarea>
    <button name="tip_send" id="tip_send" class="button">Send tip!</button>
  </p>
  <p><a href="#" id="toggle_write_review">Write a review for this restaurant</a></p>
  <p id="write_review_box">
    <label for="review_body">Message:</label><br>
    <textarea name="review_body" id="review_body" rows="4" cols="30"></textarea>
    <button name="review_send" id="review_send" class="button">Review it!</button>
  </p>
<?php } ?>
</div>
<div id="map_canvas" class="right_col"></div>

<div class="clearfloats"></div>
<div>
  <h2>Reviews</h2><br>
  <?php foreach($restaurant->reviews as $review) {
    echo '<p>' . $review['review_body'] . ' <span class="review_by">by ' . $review['added_by']->name . '</span></p>';
  } ?>
</div>
<?php
  include("includes/bottom.php");
?>