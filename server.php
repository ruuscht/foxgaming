<?php
session_start();

// initiera variabler
$username = "";
$email    = "";
$errors = array(); 

// anslut till databasen
$db = mysqli_connect('localhost', 'root', '', 'gaming');

// REGISTERA ANVÄNDARE
if (isset($_POST['reg_user'])) {
  // ta emot alla inmatningsvärden från formuläret
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // formulärvalidering, se till att formuläret är korrekt fyllt 
  // genom att lägga till (array_push ()) motsvarande fel till $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "Password confirmation doesn't match");
  }

// Kontrollera först i databasen för att se om
// det inte finns användare med samma användarnamn eller e-post
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // om användaren exsisterar
    if ($user['username'] === $username) {
      array_push($errors, "This username already exist");
    }

    if ($user['email'] === $email) {
      array_push($errors, "This email already exist");
    }
  }

  // Registrera slutligen användare om det inte finns några fel i formuläret
  if (count($errors) == 0) {
  	$password = md5($password_1);//Krypterar lösnordet

  	$query = "INSERT INTO users (username, email, password) 
  			  VALUES('$username', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: index.php');
  }
}

if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: index.php');
  	}else {
  		array_push($errors, "Are you sure you typed right?");
  	}
  }
}

?>