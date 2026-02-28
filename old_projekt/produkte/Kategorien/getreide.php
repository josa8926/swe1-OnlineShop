<?php
header('Content-Type: application/json');

include '/var/www/html/private/dbconnection.inc.php';

    $pdo = new PDO(
        "mysql:host=$servername;dbname=$db;charset=utf8",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
// 2. Alle Produkte holen
$sql = "SELECT * FROM produkt where Kategorien = 'Getreide'";
$produkte = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($produkte);
//include 'asia.js';
?>
