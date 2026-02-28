<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

// ACTION AJAX : mettre une commande à "verarbeitet"
if (isset($_GET['action']) && $_GET['action'] === 'verarbeiten' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $mysqli->prepare("UPDATE Bestellung SET status = 'verarbeitet' WHERE id = ? AND status = 'bezahlt'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $success = $stmt->affected_rows > 0;
    $stmt->close();
    $mysqli->close();
    header('Content-Type: application/json');
    echo json_encode(['success' => $success]);
    exit;
}

// ONGLET ACTIF 
$tab = $_GET['tab'] ?? 'produkte';

$message = '';
$error = '';

// ─── AJOUTER UN PRODUIT ───────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name        = trim($_POST['name']);
    $preis       = !empty($_POST['preis'])       ? (float) $_POST['preis']       : 0.0;
    $preis_pro_kg= !empty($_POST['preis_pro_kg'])? (float) $_POST['preis_pro_kg']: 0.0;
    $bild_url    = trim($_POST['bild_url']);
    $menge       = (int) $_POST['menge'];
    $herkunft    = $_POST['herkunft'];
    $kategorien  = trim($_POST['kategorien']);

    if ($name === '' || $herkunft === '') {
        $error = "Name und Herkunft sind Pflichtfelder.";
    } elseif ($menge < 0) {
        $error = "Menge darf nicht negativ sein.";
    } else {
        $stmt = $mysqli->prepare("
            INSERT INTO produkt (name, preis, preis_pro_kg, bild_url, menge, Herkunft, Kategorien)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                preis        = VALUES(preis),
                preis_pro_kg = VALUES(preis_pro_kg),
                bild_url     = VALUES(bild_url),
                menge        = menge + VALUES(menge),
                Herkunft     = VALUES(Herkunft),
                Kategorien   = VALUES(Kategorien)
        ");
        if (!$stmt) {
            $error = "Prepare failed: " . $mysqli->error;
        } else {
            $stmt->bind_param("sddsiss", $name, $preis, $preis_pro_kg, $bild_url, $menge, $herkunft, $kategorien);
            if ($stmt->execute()) {
                $message = $stmt->affected_rows == 1
                    ? "✓ Produkt erfolgreich hinzugefügt! ID: " . $stmt->insert_id
                    : "✓ Produkt aktualisiert!";
            } else {
                $error = "✗ Fehler beim Einfügen: " . $stmt->error;
            }
            $stmt->close();
        }
    }
    // Reste sur l'onglet produits après soumission
    $tab = 'produkte';
}

// ─── CHARGER LES PRODUITS ────────────────────────────────────────────────────
$products = [];
$result = $mysqli->query("SELECT * FROM produkt ORDER BY Herkunft, produkt_id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    $error = "Fehler beim Laden der Produkte: " . $mysqli->error;
}

// ─── CHARGER LES BESTELLUNGEN (status = bezahlt) ─────────────────────────────
$bestellungen = [];
if ($tab === 'nachrichten') {
    $sql = "SELECT id, vorname, nachname, adresse, ort, plz, email, handy, status,
                   (SELECT SUM(price * qty) FROM Produkte WHERE order_id = Bestellung.id) as gesamtpreis
            FROM Bestellung
            WHERE status = 'bezahlt'
            ORDER BY id DESC";
    $result = $mysqli->query($sql);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $bestellungen[] = $row;
        }
    }
}

$mysqli->close();

require_once 'verwaltung.php';
?>
