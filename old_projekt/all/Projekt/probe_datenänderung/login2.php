<?php
session_start();
include '/var/www/html/private/dbconnection.inc.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$mysqli = new mysqli($servername, $username, $password, $db);
if ($mysqli->connect_error) {
    die("DB Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

// Passwort-Hash aus der DB holen
$stmt = $mysqli->prepare("SELECT password FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($password_hash);
$stmt->fetch();
$stmt->close();
$mysqli->close();

// Passwort prüfen
if ($password_hash && password_verify($password, $password_hash)) {
    $_SESSION['user'] = $email;
    header("Location: profil.php");
    exit();
} else {
    header("Location: login.html?error=wrong_credentials");
    exit();
}

