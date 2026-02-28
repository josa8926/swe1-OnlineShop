<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}

include '/var/www/html/private/dbconnection.inc.php';

$mysqli = new mysqli($servername, $username, $password, $db);
if ($mysqli->connect_error) {
    die("DB Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

$userid = $_SESSION['userid'];
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';

if (!$name || !$email) {
    die("Bitte alle Felder ausfüllen");
}

// Prüfen ob E-Mail schon existiert (außer beim eigenen Account)
$check = $mysqli->prepare("SELECT id FROM USERS WHERE email=? AND id!=?");
$check->bind_param("si", $email, $userid);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    die("E-Mail wird bereits verwendet");
}

$check->close();

// Update durchführen
$stmt = $mysqli->prepare("UPDATE USERS SET name=?, email=? WHERE id=?");
$stmt->bind_param("ssi", $name, $email, $userid);

if ($stmt->execute()) {
    $_SESSION['email'] = $email; // Session aktualisieren
    header("Location: profil.php?success=1");
    exit;
} else {
    echo "Fehler beim Speichern";
}

$stmt->close();
$mysqli->close();

