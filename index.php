<?php
  require('includes/bootstrap.php');

  $title = "";
  include("includes/top.php");
?>

<div class="book_list">
<?php 
$book_list = Book::list_all();
if($book_list) {
  foreach($book_list as $book) { ?>
    <div class="book">
      <p><span class="title"><?php echo $book['title'] ?></span> by <span class="author"><?php echo $book['author'] ?></span>.</p>
      <p class="isbn">ISBN: <?php echo $book['isbn'] ?></p>
    </div>
  <?php } 
}
else { ?>
<p>The library is empty!</p>
<?php } ?>

</div>

<?php
  include("includes/bottom.php");
?>