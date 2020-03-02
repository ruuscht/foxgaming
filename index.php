<?php 
  @session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="index.css?<?php echo time(); ?>">
</head>
<body>

<div class="nav">
<ul>
<!-- <div class="logo">
    <img src="logo.png" alt="">
</div> -->
  <li><a class="active" href="#">Home</a></li>
  <li><a href="about.php">About us</a></li>
  <li><a href="#">Contact</a></li>
</ul>
</div>

<div class="content">
  	<!-- notification message -->
  	<?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
      	<h3>
          <?php 
          	echo $_SESSION['success']; 
          	unset($_SESSION['success']);
          ?>
      	</h3>
      </div>
  	<?php endif ?>


<div class="header">
	<h2>FoxGaming</h2>
</div>


    <!-- logged in user information -->
    <?php  if (isset($_SESSION['username'])) : ?>
    	<p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
    	<p> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>
    <?php endif ?>
</div>

<?php
include("server.php");
include("post.php");
$Posts = new GBPost($dbh);




foreach( $Posts->fetchAll() as $post ) {

	echo "<b>Name:</b> " . $post['Title'] . "<br />";
	echo "<b>Message:</b><br /> " . $post['Description'] . "<br />";
	echo "<b>Posted:</b> " . $post['Published'] . "<br />";
	echo "<a href=\"editpost.php?action=delete&id=" . $post['id'] . "\">Delete!</a>";
	echo " | ";
	echo "<a href=\"editpost.php?action=edit&id=" . $post['id'] . "\">Edit!</a>";
	echo "<hr />";
}
?>
		
</body>
</html>