<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
  header("Location: login2.php");
  exit;
}
include '/var/www/html/private/dbconnection.inc.php';

$mysqli = new mysqli($servername, $username, $password, $db);
if ($mysqli->connect_error) {
  die("DB Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

$message = '';
$error = '';

// PRODUKT HINZUFÜGEN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
  // Debug: Zeige alle POST-Daten
  error_log("POST Data: " . print_r($_POST, true));

  $name = trim($_POST['name']);
  $preis = !empty($_POST['preis']) ? (float) $_POST['preis'] : 0.0;
  $preis_pro_kg = !empty($_POST['preis_pro_kg']) ? (float) $_POST['preis_pro_kg'] : 0.0;
  $bild_url = trim($_POST['bild_url']);
  $menge = (int) $_POST['menge'];
  $herkunft = $_POST['herkunft'];
  $kategorien = trim($_POST['kategorien']);

  // Debug-Ausgabe
  error_log("Name: $name, Preis: $preis, Preis/kg: $preis_pro_kg, Menge: $menge, Herkunft: $herkunft, Kategorien: $kategorien");

  if ($name === '' || $herkunft === '') {
    $error = "Name und Herkunft sind Pflichtfelder.";
  } elseif ($menge < 0) {
    $error = "Menge darf nicht negativ sein.";
  } else {
    // Einfaches INSERT ohne ON DUPLICATE KEY
    $stmt = $mysqli->prepare("
            INSERT INTO produkt
            (name, preis, preis_pro_kg, bild_url, menge, Herkunft, Kategorien)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                 preis = VALUES(preis),              
                 preis_pro_kg = VALUES(preis_pro_kg), 
                 bild_url = VALUES(bild_url),        
                 menge = menge + VALUES(menge),       
                 Herkunft = VALUES(Herkunft),         
                 Kategorien = VALUES(Kategorien)      
            ");

        if (!$stmt) {
            $error = "Prepare failed: " . $mysqli->error;
            error_log("Prepare Error: " . $mysqli->error);
        } else {
            $stmt->bind_param("sddsiss",
                $name,
                $preis,
                $preis_pro_kg,
                $bild_url,
                $menge,
                $herkunft,
                $kategorien
            );

            if ($stmt->execute()){
              if ($stmt->affected_rows == 1) {
                $message = "✓ Produkt erfolgreich hinzugefügt! ID: " . $stmt->insert_id;
                error_log("Success: Product added with ID " . $stmt->insert_id);
            } elseif ($stmt->affected_rows == 2){
                $message = "✓ Produkt aktualisiert! ID: " . $stmt->insert_id;
                error_log("Success: Product update with ID " . $stmt->insert_id);
            }
            }else {
                $error = "✗ Fehler beim Einfügen: " . $stmt->error;
                error_log("Execute Error: " . $stmt->error);
            }
            $stmt->close();
        }
    }
}

// PRODUKTE LADEN
$products = [];
$result = $mysqli->query("SELECT * FROM produkt ORDER BY Herkunft, produkt_id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    $error = "Fehler beim Laden der Produkte: " . $mysqli->error;
}

require_once 'verwaltung.php';
