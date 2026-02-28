<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '/var/www/html/private/dbconnection.inc.php';

$mysqli = new mysqli($servername, $username, $password, $db);
if ($mysqli->connect_error) {
  die("DB Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if(!$email || !$password){
  header("Location: login2.php?error=empty");
  exit;
}

$stmt = $mysqli->prepare("SELECT id, password FROM USERS WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($userid , $hash);
$stmt->fetch();
$stmt->close();
$mysqli->close();

if ($hash && password_verify($password , $hash)) {
  session_regenerate_id(true);
  $_SESSION['userid'] = $userid;
  $_SESSION['email'] = $email;
  $_SESSION['is_admin'] = ($email === "admin2@gmail.com");

  if($_SESSION['is_admin']){
    header("Location: verwalt.php");
  }else{
    header("Location: afroseite3.php");
  }
  exit;
}else{
  header("Location: login2.php?error=false");
  exit ;
}
