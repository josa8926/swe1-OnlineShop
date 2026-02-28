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
$action = $_POST['action'] ?? '';

if ($action === "update_profile") {

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';

    if (!$name || !$email) {
        die("Bitte alle Felder ausfüllen");
    }

    // Prüfen ob E-Mail schon existiert
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
    $stmt->execute();
    $stmt->close();

    $_SESSION['email'] = $email;

    header("Location: profil.php?success=profile");
    exit;
}

if ($action === "update_password") {

    $old = $_POST['old_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $new2 = $_POST['new_password2'] ?? '';

    if (!$old || !$new || !$new2) {
        die("Bitte alle Passwortfelder ausfüllen");
    }

    if ($new !== $new2) {
        die("Neue Passwörter stimmen nicht überein");
    }

    // aktuelles Passwort holen
    $stmt = $mysqli->prepare("SELECT password FROM USERS WHERE id=?");
    $stmt->bind_param("i", $userid);
    $stmt->execute();
    $stmt->bind_result($db_hash);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($old, $db_hash)) {
        die("Aktuelles Passwort ist falsch");
    }

    // neues Passwort speichern
    $new_hash = password_hash($new, PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare("UPDATE USERS SET password=? WHERE id=?");
    $stmt->bind_param("si", $new_hash, $userid);
    $stmt->execute();
    $stmt->close();

    header("Location: profil.php?success=password");
    exit;
}

die("Ungültige Aktion");

