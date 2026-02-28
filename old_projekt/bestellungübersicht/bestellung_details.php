<?php
session_start();

// Prüfen ob Benutzer eingeloggt ist
if (!isset($_SESSION['userid'])) {
    header("Location: login2.php");
    exit;
}

// Prüfen ob Bestellungs-ID übergeben wurde
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: bestellungen.php");
    exit;
}

include '/var/www/html/private/dbconnection.inc.php';

$mysqli = new mysqli($servername, $username, $password, $db);
if ($mysqli->connect_error) {
    die("DB Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

$userid = $_SESSION['userid'];
$bestellung_id = intval($_GET['id']);

// Bestelldaten holen und prüfen ob Bestellung zum User gehört
$stmt = $mysqli->prepare("
    SELECT 
        id, 
        datum, 
        gesamtpreis, 
        status, 
        lieferadresse,
        zahlungsmethode
    FROM BESTELLUNGEN
    WHERE id = ? AND user_id = ?
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

// Bestellpositionen holen
$stmt = $mysqli->prepare("
    SELECT 
        bp.produkt_id,
        bp.menge,
        bp.preis,
        p.name as produkt_name,
        p.bild as produkt_bild
    FROM BESTELLPOSITIONEN bp
    LEFT JOIN PRODUKTE p ON bp.produkt_id = p.id
    WHERE bp.bestell_id = ?
");
$stmt->bind_param("i", $bestellung_id);
$stmt->execute();
$result = $stmt->get_result();
$positionen = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestelldetails #<?php echo $bestellung_id; ?> – Jenny Afro & Asia Shop</title>
 <!--   <link rel="stylesheet" href="includes/style3.css">
    <link rel="stylesheet" href="includes/bestellung_details.css">--!>
</head>
<body>

<!-- Top Bar -->
<div class="topbar">
    <div class="container">
        <div class="topbar-content">
            <span>📍 Bremerhaven </span>
            <div class="topbar-links">
                <a href="profil.php">Mein Konto</a>
                <a href="logout.php">Ausloggen</a>
            </div>
        </div>
    </div>
</div>

<!-- Header -->
<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="afroseite3.php" style="text-decoration: none; color: inherit;">
                    <span class="logo-icon">🌍</span>
                    <span class="logo-text"><strong>Jenny</strong><br><strong>Afro & Asia Shop</strong></span>
                </a>
            </div>

            <nav class="main-nav">
                <a href="Warenkorb/warenkorb.html">Shop</a>
                <a href="#">Afrikanisch</a>
                <a href="#">Asiatisch</a>
                <a href="bestellungen.php">Meine Bestellungen</a>
            </nav>

            <div class="header-actions">
                <a href="Warenkorb/warenkorb.html" class="cart-btn">
                    <span class="cart-icon">🛒</span>
                    <span class="cart-count">0</span>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Details Container -->
<div class="details-container">
    <a href="bestellungen.php" class="back-link">← Zurück zu meinen Bestellungen</a>
    
    <h1 class="page-title">Bestellung #<?php echo htmlspecialchars($bestellung['id']); ?></h1>

    <!-- Bestellinformationen -->
    <div class="order-info-grid">
        <div class="info-item">
            <span class="info-label">Bestelldatum</span>
            <span class="info-value">
                <?php 
                $datum = new DateTime($bestellung['datum']);
                echo $datum->format('d.m.Y - H:i'); 
                ?> Uhr
            </span>
        </div>

        <div class="info-item">
            <span class="info-label">Status</span>
            <span class="info-value">
                <?php
                $status_class = 'status-' . strtolower($bestellung['status']);
                ?>
                <span class="status-badge <?php echo $status_class; ?>">
                    <?php echo htmlspecialchars($bestellung['status']); ?>
                </span>
            </span>
        </div>

        <div class="info-item">
            <span class="info-label">Zahlungsmethode</span>
            <span class="info-value">
                <?php echo htmlspecialchars($bestellung['zahlungsmethode'] ?? 'Nicht angegeben'); ?>
            </span>
        </div>

        <div class="info-item">
            <span class="info-label">Lieferadresse</span>
            <span class="info-value">
                <?php echo nl2br(htmlspecialchars($bestellung['lieferadresse'])); ?>
            </span>
        </div>
    </div>

    <!-- Bestellpositionen -->
    <div class="products-section">
        <h2 class="section-title">Bestellte Artikel</h2>
        
        <?php foreach ($positionen as $position): ?>
            <div class="product-item">
                <?php if (!empty($position['produkt_bild'])): ?>
                    <img src="<?php echo htmlspecialchars($position['produkt_bild']); ?>" 
                         alt="<?php echo htmlspecialchars($position['produkt_name']); ?>" 
                         class="product-image">
                <?php else: ?>
                    <div class="product-image" style="display: flex; align-items: center; justify-content: center; background: #f0f0f0;">
                        📦
                    </div>
                <?php endif; ?>
                
                <div class="product-info">
                    <div class="product-name">
                        <?php echo htmlspecialchars($position['produkt_name'] ?? 'Produkt #' . $position['produkt_id']); ?>
                    </div>
                    <div class="product-details">
                        Menge: <?php echo htmlspecialchars($position['menge']); ?> × 
                        <?php echo number_format($position['preis'], 2, ',', '.'); ?> €
                    </div>
                </div>
                
                <div class="product-price">
                    <?php echo number_format($position['menge'] * $position['preis'], 2, ',', '.'); ?> €
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Zusammenfassung -->
        <div class="order-summary">
            <div class="summary-row total">
                <span>Gesamtsumme:</span>
                <span><?php echo number_format($bestellung['gesamtpreis'], 2, ',', '.'); ?> €</span>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Jenny Afro & Asia Shop</h4>
                <p>Dein Shop für authentische afrikanische und asiatische Lebensmittel in Bremerhaven.</p>
            </div>
            <div class="footer-section">
                <h4>Links</h4>
                <a href="#">Über uns</a>
                <a href="#">Kontakt</a>
                <a href="#">AGB</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Jenny Afro & Asia Shop. Alle Rechte vorbehalten.</p>
        </div>
    </div>
</footer>

</body>
</html>

<?php
$mysqli->close();
?>
