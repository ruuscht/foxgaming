<?php
@session_start();

// initiera variabler
$username = "";
$email    = "";
$errors = array(); 

// anslut till databasen
$host = "localhost";
$user = "root";
$pass = "";
$db = "gaming";


// MAKE CONNECTION

try {
$dsn = "mysql:host=$host;dbname=$db;";
$dbh = new PDO($dsn, $user, $pass);

} catch(PDOException $e) {
    echo "Error! ". $e->getMessage() ."<br />";
    die;
}

// REGISTERA ANVÄNDARE
if (isset($_POST['reg_user'])) {
  // ta emot alla inmatningsvärden från formuläret
$username = $_POST['username'];
  $email = $_POST['email'];
  $password_1 = $_POST['password_1'];
  $password_2 = $_POST['password_2'];

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
  $user_check_query = "SELECT * FROM users WHERE username=:username OR email=:email LIMIT 1";
  $sth = $dbh->prepare($user_check_query);
  $sth->bindParam(':username', $username);
  $sth->bindParam(':email', $email);
  $return = $sth->execute();
 /*  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result); */
  
  $data = $sth->fetch(); 
  
  if ($data) { // om användaren exsisterar
    if ($data['username'] === $username) {
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
          VALUES(:username, :email, :password)";
          
  	  $sth = $dbh->prepare($query);
      $sth->bindParam(':username', $username);
      $sth->bindParam(':email', $email);
      $sth->bindParam(':password', $password);
      $return = $sth->execute();
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header('location: index.php');
  }
}

//LoginHandler
if (isset($_POST['login_user'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
    $query = "SELECT * FROM users WHERE username=:username AND password=:password";
              
    $sth = $dbh->prepare($query);
   
    $sth->bindParam(':username', $username);
    $sth->bindParam(':password', $password);
    $return = $sth->execute();
  	
  	if ($sth->rowCount() == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "Welcome! You are now logged in";
  	  header('location: index.php');
  	}else {
  		array_push($errors, "Are you sure you typed right?");
  	}
  }
}

?>