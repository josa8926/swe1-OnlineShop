<?php
session_start();

// On vérifie si l'utilisateur a le badge "admin"
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    // Si non, on l'expulse vers le login
    header("Location: login.php");
    exit;
}

echo "Bienvenue dans l'administration, " . $_SESSION['email'];
?>
