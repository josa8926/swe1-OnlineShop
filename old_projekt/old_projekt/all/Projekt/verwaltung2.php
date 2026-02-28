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
    $name = trim($_POST['name'] ?? '');
    $preis = !empty($_POST['preis']) ? (float) $_POST['preis'] : null;
    $preis_pro_kg = !empty($_POST['preis_pro_kg']) ? (float) $_POST['preis_pro_kg'] : null;
    $bild_url = trim($_POST['bild_url'] ?? '');
    $menge = isset($_POST['menge']) ? (int) $_POST['menge'] : 0;
    $herkunft = $_POST['herkunft'] ?? '';
    $kategorien = trim($_POST['kategorien'] ?? '');

    if ($name === '') {
        $error = "Produktname ist ein Pflichtfeld.";
    } elseif ($herkunft === '') {
        $error = "Herkunft ist ein Pflichtfeld.";
    } elseif ($menge < 0) {
        $error = "Menge darf nicht negativ sein.";
    } else {
        $stmt = $mysqli->prepare("
            INSERT INTO produkt
            (name, preis, preis_pro_kg, bild_url, menge, Herkunft, Kategorien)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        if (!$stmt) {
            $error = "Datenbankfehler (Prepare): " . $mysqli->error;
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
            
            if ($stmt->execute()) {
                $message = "✓ Produkt '$name' erfolgreich hinzugefügt!";
                // Redirect to prevent form resubmission
                header("Location: verwaltung.php?success=1");
                exit;
            } else {
                $error = "✗ Fehler beim Speichern: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

// Erfolgsbenachrichtigung nach Redirect
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "✓ Produkt erfolgreich hinzugefügt!";
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
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Produktverwaltung</title>
<link rel="stylesheet" href="admin-style.css">
</head>
<body>
<div class="notification-panel">
    <?php if (!empty($message)): ?>
        <div class="notification success">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="notification error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
</div>
<div class="container">
<h1>Produktverwaltung</h1>

<h2>Neues Produkt</h2>
<form method="POST" class="form-vertical">
<input type="text" name="name" placeholder="Produktname" required>
<input type="number" step="0.01" name="preis" placeholder="Preis">
<input type="number" step="0.01" name="preis_pro_kg" placeholder="Preis pro Kg">
<input type="url" name="bild_url" placeholder="Bild URL">
<input type="number" name="menge" placeholder="Menge" min="0" required>
<select name="herkunft" required>
<option value="">Herkunft wählen</option>
<option value="afro">Afro</option>
<option value="asia">Asia</option>
</select>
<input type="text" name="kategorien" placeholder="Kategorie (z.B. Getreide, Öl, Gewürze)">
<button type="submit" name="add_product">Speichern</button>
</form>

<h2>Produkte</h2>
<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Preis</th>
<th>Preis/Kg</th>
<th>Menge</th>
<th>Herkunft</th>
<th>Kategorie</th>
</tr>
<?php if (!empty($products)): ?>
    <?php foreach ($products as $p): ?>
    <tr>
    <td><?php echo htmlspecialchars($p['produkt_id']); ?></td>
    <td><?php echo htmlspecialchars($p['name']); ?></td>
    <td><?php echo htmlspecialchars($p['preis']); ?> €</td>
    <td><?php echo htmlspecialchars($p['preis_pro_kg']); ?> €</td>
    <td><?php echo htmlspecialchars($p['menge']); ?></td>
    <td><?php echo htmlspecialchars($p['Herkunft']); ?></td>
    <td><?php echo htmlspecialchars($p['Kategorien']); ?></td>
    </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7">Keine Produkte vorhanden.</td>
    </tr>
<?php endif; ?>
</table>
</div>
</body>
</html>
