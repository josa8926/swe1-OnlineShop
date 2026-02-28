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

$old_password = $_POST['old_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$new_password2 = $_POST['new_password2'] ?? '';

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

// -----------------------------
// 🔐 PASSWORT ÄNDERN (optional)
// -----------------------------
if (!empty($old_password) || !empty($new_password) || !empty($new_password2)) {

    // Prüfen ob alle Felder ausgefüllt sind
    if (!$old_password || !$new_password || !$new_password2) {
        die("Bitte alle Passwortfelder ausfüllen");
    }

    // Neues Passwort prüfen
    if ($new_password !== $new_password2) {
        die("Neue Passwörter stimmen nicht überein");
    }

    // Aktuelles Passwort aus DB holen
    $stmt_pw = $mysqli->prepare("SELECT password FROM USERS WHERE id=?");
    $stmt_pw->bind_param("i", $userid);
    $stmt_pw->execute();
    $stmt_pw->bind_result($db_hash);
    $stmt_pw->fetch();
    $stmt_pw->close();

    // Prüfen ob altes Passwort korrekt ist
    if (!password_verify($old_password, $db_hash)) {
        die("Aktuelles Passwort ist falsch");
    }

    // Neues Passwort 
    $new_hash = password_hash($new_password, PASSWORD_DEFAULT);

    // Passwort speichern
    $stmt_pw_update = $mysqli->prepare("UPDATE USERS SET password=? WHERE id=?");
    $stmt_pw_update->bind_param("si", $new_hash, $userid);
    $stmt_pw_update->execute();
    $stmt_pw_update->close();
}


if ($stmt->execute()) {
    $_SESSION['email'] = $email;
    header("Location: profil.php?success=1");
    exit;
} else {
    echo "Fehler beim Speichern";
}

$stmt->close();
$mysqli->close();

