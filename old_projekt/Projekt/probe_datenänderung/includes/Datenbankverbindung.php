<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '/var/www/html/private/dbconnection.inc.php';

$mysqli = new mysqli($servername, $username, $password, $db);

if ($mysqli->connect_error) {
    die("Datenbank-Fehler: " . $mysqli->connect_error);
}

