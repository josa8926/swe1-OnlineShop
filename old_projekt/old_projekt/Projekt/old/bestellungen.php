<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['userid'])) {
    header("Location: login2.php");
    exit;
}
//if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  //  header("Location: bestellungen.php");
    //exit;
}
include '/var/www/html/private/dbconnection.inc.php';
$mysqli = new mysqli($servername, $username, $password, $db);
if ($mysqli->connect_error) {
    die("Datenbankfehler: " . $mysqli->connect_error);
}
$userid = $_SESSION['userid'];
$bestellung_id = intval($_GET['id']);

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
    header("Location: bestellungen.php");
    exit;
}
$bestellung = $result->fetch_assoc();
$stmt->close();

$stmt = $mysqli->prepare("SELECT * FROM Produkte WHERE order_id = ?");
$stmt->bind_param("i", $bestellung_id);
$stmt->execute();
$result = $stmt->get_result();
$produkte = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$gesamtpreis = 0;
foreach ($produkte as $produkt) {
    $gesamtpreis += $produkt['price'] * $produkt['qty'];
}
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestelldetails #<?php echo $bestellung_id; ?> – Jenny Afro & Asia Shop</title>
    <link rel="stylesheet" href="admin-style.css">
    <style>
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .detail-table td {
            padding: 10px 14px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        .detail-table td:first-child {
            color: #777;
            width: 180px;
            font-weight: 600;
        }
        .artikel-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .artikel-table th {
            background: #f5f5f5;
            padding: 10px 14px;
            text-align: left;
            font-size: 13px;
            color: #555;
            border-bottom: 2px solid #ddd;
        }
        .artikel-table td {
            padding: 10px 14px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        .artikel-table tfoot td {
            background: #f9f9f9;
            font-weight: bold;
            font-size: 15px;
            padding: 12px 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            background: #e3f2fd;
            color: #1565C0;
        }
        .section-title {
            font-size: 17px;
            font-weight: bold;
            color: #333;
            margin: 24px 0 12px 0;
            padding-bottom: 6px;
            border-bottom: 2px solid #eee;
        }
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .page-header h1 {
            margin: 0;
            font-size: 22px;
        }
        .nav-links a {
            font-size: 14px;
            color: #1976D2;
            text-decoration: none;
            margin-left: 16px;
        }
        .nav-links a:hover { text-decoration: underline; }
        .tab-bar {
            display: flex;
            gap: 4px;
            margin-bottom: 24px;
            border-bottom: 2px solid #ddd;
        }
        .tab-bar a {
            padding: 10px 22px;
            text-decoration: none;
            color: #555;
            border-radius: 6px 6px 0 0;
            font-size: 14px;
            border: 1px solid transparent;
            border-bottom: none;
            transition: background 0.2s;
        }
        .tab-bar a.active, .tab-bar a:hover {
            background: #fff;
            color: #1976D2;
            border-color: #ddd;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">

    <div class="page-header">
        <h1>📋 Bestelldetails #<?php echo htmlspecialchars($bestellung['id']); ?></h1>
        <div class="nav-links">
            <a href="bestellungen.php">← Meine Bestellungen</a>
            <a href="afroseite2.php">🏠 Startseite</a>
        </div>
    </div>

    <!-- Tab-Leiste -->
    <div class="tab-bar">
        <a href="bestellungen.php" class="active">📦 Bestellungen</a>
        <a href="nachrichten.php">✉️ Nachrichten</a>
    </div>

    <!-- Lieferinformationen -->
    <div class="section-title">📍 Lieferinformationen</div>
    <table class="detail-table">
        <tr>
            <td>Bestellnummer</td>
            <td><strong>#<?php echo htmlspecialchars($bestellung['id']); ?></strong></td>
        </tr>
        <tr>
            <td>Name</td>
            <td><?php echo htmlspecialchars($bestellung['vorname']) . ' ' . htmlspecialchars($bestellung['nachname']); ?></td>
        </tr>
        <tr>
            <td>Lieferadresse</td>
            <td>
                <?php echo htmlspecialchars($bestellung['adresse']); ?><br>
                <?php if (!empty($bestellung['zusatz'])) echo htmlspecialchars($bestellung['zusatz']) . '<br>'; ?>
                <?php echo htmlspecialchars($bestellung['plz']) . ' ' . htmlspecialchars($bestellung['ort']); ?>
            </td>
        </tr>
        <tr>
            <td>E-Mail</td>
            <td><?php echo htmlspecialchars($bestellung['email']); ?></td>
        </tr>
        <tr>
            <td>Telefon</td>
            <td><?php echo htmlspecialchars($bestellung['handy']); ?></td>
        </tr>
        <tr>
            <td>Status</td>
            <td><span class="status-badge"><?php echo htmlspecialchars($bestellung['status']); ?></span></td>
        </tr>
    </table>

    <!-- Bestellte Artikel -->
    <div class="section-title">🛒 Bestellte Artikel</div>
    <?php if (empty($produkte)): ?>
        <p style="color:#888;">Keine Artikel gefunden.</p>
    <?php else: ?>
        <table class="artikel-table">
            <thead>
                <tr>
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
                <tr>
                    <td colspan="3" style="text-align:right;">Gesamtsumme:</td>
                    <td><?php echo number_format($gesamtpreis, 2, ',', '.'); ?> €</td>
                </tr>
            </tfoot>
        </table>
    <?php endif; ?>

</div>

<footer style="text-align:center; padding: 24px 0; color:#aaa; font-size:13px;">
    &copy; 2026 Jenny Afro &amp; Asia Shop. Alle Rechte vorbehalten.
</footer>
</body>
</html>
