<?php
session_start();
include '/var/www/html/private/dbconnection.inc.php';

$mysqli = new mysqli($servername, $username, $password, $db);
if ($mysqli->connect_error) {
    die("DB Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $mysqli->prepare("SELECT COUNT(*) FROM USERS WHERE email=? AND password_hash=?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();
$mysqli->close();

if ($count === 1) {
    $_SESSION['user'] = $email;
    include '/data/workuser/enqueue_auftrag.php';
    enqueue($email);
    echo "Login erfolgreich";
header("Location: profil.php");
exit();
} else {
    echo "Falsche E-Mail oder Passwort";
}
