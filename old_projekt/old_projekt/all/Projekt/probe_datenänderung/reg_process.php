<?php
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL); 

session_start();
include '/var/www/html/private/dbconnection.inc.php';

$conn = new mysqli($servername, $username, $password, $db);
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen:" . $conn->connect_error);
}

$name = $_POST["name"] ?? "";
$email = $_POST["email"] ?? "";
$passwort1 = $_POST["passwort1"] ?? "";
$passwort2 = $_POST["passwort2"] ?? "";

if (!$name || !$email || !$passwort1 || !$passwort2) {
  header("Location: register.php?error=empty");
  exit;
 //die("Bitte füllen Sie alle Felder aus");
}

if ($passwort1 !== $passwort2) {
  header("Location: register.php?error=password");
  exit;
  //die("Passwörter stimmen nicht überein");
}

$passwort = password_hash($passwort1, PASSWORD_DEFAULT);

$check = $conn->prepare("SELECT id FROM USERS WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
  header("Location: register.php?error=email_exists");
  exit;
   //die("E-mail existiert bereits");
}

$stmt = $conn->prepare("INSERT INTO USERS (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $passwort);

if ($stmt->execute()) {
  setcookie("success" , "Registrierung erfolgreich!", time() + 10 , "/");
  header("Location: login.php");
  exit ; 
} else {
    echo "Ein Fehler ist aufgetreten";
}

