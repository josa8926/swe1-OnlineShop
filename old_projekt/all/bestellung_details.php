<?php
session_start();

// Login prüfen
if (!isset($_SESSION['userid'])) {
    header("Location: login2.php");
    exit;
}

// Bestellungs-ID prüfen
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: bestellungen.php");
    exit;
}

// Datenbank verbinden
include '/var/www/html/private/dbconnection.inc.php';
$mysqli = new mysqli($servername, $username, $password, $db);

if ($mysqli->connect_error) {
    die("Datenbankfehler: " . $mysqli->connect_error);
}

$userid = $_SESSION['userid'];
$bestellung_id = intval($_GET['id']);

// Bestellung laden - Prüfen ob sie dem User gehört (über Email)
$stmt = $mysqli->prepare("
    SELECT b.* 
    FROM Bestellung b
    WHERE b.id = ? 
    AND b.email = (SELECT email FROM USERS WHERE id = ?)
");
$stmt->bind_param("ii", $bestellung_id, $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Bestellung nicht gefunden oder gehört nicht zum User
    header("Location: bestellungen.php");
    exit;
}

$bestellung = $result->fetch_assoc();
$stmt->close();

// Produkte der Bestellung laden
$stmt = $mysqli->prepare("SELECT * FROM Produkte WHERE order_id = ?");
$stmt->bind_param("i", $bestellung_id);
$stmt->execute();
$result = $stmt->get_result();
$produkte = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Gesamtpreis berechnen
$gesamtpreis = 0;
foreach ($produkte as $produkt) {
    $gesamtpreis += $produkt['price'] * $produkt['qty'];
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestelldetails #<?php echo $bestellung_id; ?> – Jenny Afro & Asia Shop</title>
</head>
<body>

<h1>📋 Bestelldetails #<?php echo htmlspecialchars($bestellung['id']); ?></h1>

<p>
    <a href="bestellungen.php"> meine Bestellungen</a> | 
    <a href="afroseite2.php">Startseite</a> | 
    <a href="logout.php">Ausloggen</a>
</p>

<hr>

<h2>Lieferinformationen</h2>

<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <td><strong>Bestellnummer:</strong></td>
        <td>#<?php echo htmlspecialchars($bestellung['id']); ?></td>
    </tr>
    <tr>
        <td><strong>Name:</strong></td>
        <td><?php echo htmlspecialchars($bestellung['vorname']) . ' ' . htmlspecialchars($bestellung['nachname']); ?></td>
    </tr>
    <tr>
        <td><strong>Lieferadresse:</strong></td>
        <td>
            <?php echo htmlspecialchars($bestellung['adresse']); ?><br>
            <?php if (!empty($bestellung['zusatz'])) echo htmlspecialchars($bestellung['zusatz']) . '<br>'; ?>
            <?php echo htmlspecialchars($bestellung['plz']) . ' ' . htmlspecialchars($bestellung['ort']); ?>
        </td>
    </tr>
    <tr>
        <td><strong>E-Mail:</strong></td>
        <td><?php echo htmlspecialchars($bestellung['email']); ?></td>
    </tr>
    <tr>
        <td><strong>Telefon:</strong></td>
        <td><?php echo htmlspecialchars($bestellung['handy']); ?></td>
    </tr>
    <tr>
        <td><strong>Status:</strong></td>
        <td><strong><?php echo htmlspecialchars($bestellung['status']); ?></strong></td>
    </tr>
</table>

<hr>

<h2>Bestellte Artikel</h2>

<?php if (empty($produkte)): ?>
    <p>Keine Artikel gefunden.</p>
<?php else: ?>
    
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr style="background-color: #f0f0f0;">
                <th>Artikel</th>
                <th>Einzelpreis</th>
                <th>Menge</th>
                <th>Gesamtpreis</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produkte as $produkt): ?>
                <tr>
                    <td><?php echo htmlspecialchars($produkt['title']); ?></td>
                    <td><?php echo number_format($produkt['price'], 2, ',', '.'); ?> €</td>
                    <td><?php echo htmlspecialchars($produkt['qty']); ?></td>
                    <td><strong><?php echo number_format($produkt['price'] * $produkt['qty'], 2, ',', '.'); ?> €</strong></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr style="background-color: #f0f0f0;">
                <td colspan="3" align="right"><strong>Gesamtsumme:</strong></td>
                <td><strong style="font-size: 18px;"><?php echo number_format($gesamtpreis, 2, ',', '.'); ?> €</strong></td>
            </tr>
        </tfoot>
    </table>
    
<?php endif; ?>

<hr>

<p><small>&copy; 2026 Jenny Afro & Asia Shop. Alle Rechte vorbehalten.</small></p>

</body>
</html>
<?php 
$mysqli->close(); 
?>
