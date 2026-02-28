<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: index.php");
    exit;
}

require_once 'db.php';

$message = '';
$error = '';

// PRODUKT HINZUFÜGEN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {

    $name = trim($_POST['name']);
    $preis = (float) $_POST['preis'];
    $preis_pro_kg = (float) $_POST['preis_pro_kg'];
    $bild_url = trim($_POST['bild_url']);
    $menge = (int) $_POST['menge'];
    $herkunft = $_POST['herkunft'];
    $kategorien = trim($_POST['kategorien']);

    if ($name === '' || $herkunft === '') {
        $error = "Name und Herkunft sind Pflichtfelder.";
    } elseif ($menge < 0) {
        $error = "Menge darf nicht negativ sein.";
    } else {

        $stmt = $mysqli->prepare("
            INSERT INTO produkt 
            (name, preis, preis_pro_kg, bild_url, menge, Herkunft, Kategorien)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                preis = VALUES(preis),
                preis_pro_kg = VALUES(preis_pro_kg),
                bild_url = Values(bild_url),
                menge = menge + VALUES(menge),
                herkunft = VALUES(Herkunft),
                kategorien = VALUES(kategorien)
        ");

        $stmt->bind_param("sddsiss",
            $name,
            $preis,
            $preis_pro_kg,
            $bild_url,
            $menge,
            $herkunft,
            $kategorien
        );

        if ($stmt->execute()) {
            $message = " Produkt gespeichert oder aktualisiert.";
        } else {
            $error = " Fehler: " . $stmt->error;
        }

        $stmt->close();
    }
}

// PRODUKTE LADEN
$products = [];
$result = $mysqli->query("SELECT * FROM produkt ORDER BY Herkunft, produkt_id DESC");
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

require_once 'verwaltung.php';

