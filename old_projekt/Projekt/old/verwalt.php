<?php
session_start();

// Vérification admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: index.php");
    exit;
}

// Inclure ta connexion existante
require_once 'db.php';

$message = '';
$error = '';

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $mysqli->real_escape_string(trim($_POST['name']));
    $category = $mysqli->real_escape_string($_POST['category']);
    $image_url = $mysqli->real_escape_string(trim($_POST['image_url']));
    $quantity = intval($_POST['quantity']);
    
    if (empty($name)) {
        $error = "Der Produktname ist erforderlich.";
    } elseif (!in_array($category, ['afro', 'asia'])) {
        $error = "Ungültige Kategorie.";
    } elseif ($quantity < 0) {
        $error = "Die Menge kann nicht negativ sein.";
    } else {
        $sql = "INSERT INTO products (name, category, image_url, quantity) 
                VALUES ('$name', '$category', '$image_url', $quantity)
                ON DUPLICATE KEY UPDATE 
                    quantity = quantity + VALUES(quantity),
                    image_url = VALUES(image_url)";
        
        if ($mysqli->query($sql)) {
            if ($mysqli->affected_rows == 1) {
                $message = "✅ Neues Produkt erfolgreich hinzugefügt!";
            } else {
                $result = $mysqli->query("SELECT quantity FROM products WHERE name = '$name' AND category = '$category'");
                if ($result && $row = $result->fetch_assoc()) {
                    $message = "✅ Menge aktualisiert! Neue Menge: " . $row['quantity'];
                }
            }
        } else {
            $error = "❌ Fehler: " . $mysqli->error;
        }
    }
}

// Löschung
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($mysqli->query("DELETE FROM products WHERE id = $id")) {
        $message = "✅ Produkt gelöscht!";
    }
}

// Produkte abrufen
$products = [];
$result = $mysqli->query("SELECT * FROM products ORDER BY category, id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$total_products = count($products);
$total_quantity = array_sum(array_column($products, 'quantity'));

// Stats nach Kategorie
$afro_count = 0;
$asia_count = 0;
foreach ($products as $p) {
    if ($p['category'] == 'afro') $afro_count++;
    else $asia_count++;
}

// HTML Template einbinden
require_once 'verwalt.php';
?>
