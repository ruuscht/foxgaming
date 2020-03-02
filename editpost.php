<?php 

include("server.php");
// $_SERVER can be used without being declared


if(isset($_GET['action']) && $_GET['action'] ==  "delete"){
    print_r($_GET);
    $query = "DELETE from posts where id =" . $_GET['id'];
    $return = $dbh->exec($query);

    header("location:index.php");

} else {




$description = (!empty($_POST['Description']) ? $_POST['Description'] : "");
$title = (!empty($_POST['Title']) ? $_POST['Title'] : "");


// to prevent a hacker attack!
$description = (!empty($_POST['Description']) ? $_POST['Description'] : "");
$title = (!empty($_POST['Title']) ? $_POST['Title'] : "");

$description = htmlspecialchars($description);
$title = htmlspecialchars($title);

$errors = false;
$errorMessages = "";


/* print_r($_POST) ."<br/>"; */

/* $textArea = $_POST["textarea"];
$inputText = $_POST["inputtext"]; */

if (empty($description)){
    $errorMessages .= "message area is empty" . "<br/>";
    $errors = true;
} 
if(empty($title)){
    $errorMessages .= "name area is empty";
    $errors = true;
} 

/* else {
    echo $textArea ;
    echo "<br/>";
    echo $inputText;
}
 */

 if ($errors == true){
     echo $errorMessages;
     echo '<a href = "index.php"> Go back </a>';
     die;
 }

$description = filter_var($textArea, FILTER_SANITIZE_STRING);
$title = filter_var($inputText, FILTER_SANITIZE_STRING);



 $query = "INSERT INTO posts (Title, Description) values ('$title', '$description');";
 $return = $dbh ->exec($query);

if(!$return){
print_r($dbh->errorInfo());
} else {
 header("location:index.php");
}

}
?>