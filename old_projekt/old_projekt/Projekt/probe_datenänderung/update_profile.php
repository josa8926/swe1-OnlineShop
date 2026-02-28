<?php
session_start();
include '/var/www/html/private/dbconnection.inc.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.html?error=not_logged_in");
    exit();
}

$current_email = $_SESSION['user'];
$new_email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

if (!$new_email) {
    header("Location: profil.php?error=E-Mail darf nicht leer sein");
    exit();
}

$update_password = false;
if ($password || $password_confirm) {
    if ($password !== $password_confirm) {
        header("Location: profil.php?error=Passwörter stimmen nicht überein");
        exit();
    }
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $update_password = true;
}

$conn = new mysqli($servername, $username, $password, $db);
if ($conn->connect_error) die("DB Verbindung fehlgeschlagen: " . $conn->connect_error);

// Prüfen, ob E-Mail schon existiert
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND email <> ?");
$stmt->bind_param("ss", $new_email, $current_email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    $conn->close();
    header("Location: profil.php?error=E-Mail existiert bereits");
    exit();
}
$stmt->close();

// Update
if ($update_password) {
    $stmt = $conn->prepare("UPDATE users SET email = ?, password = ? WHERE email = ?");
    $stmt->bind_param("sss", $new_email, $hashed_password, $current_email);
} else {
    $stmt = $conn->prepare("UPDATE users SET email = ? WHERE email = ?");
    $stmt->bind_param("ss", $new_email, $current_email);
}

if ($stmt->execute()) {
    $_SESSION['user'] = $new_email;
    header("Location: profil.php?success=1");
} else {
    header("Location: profil.php?error=Fehler beim Aktualisieren");
}

$stmt->close();
$conn->close();

