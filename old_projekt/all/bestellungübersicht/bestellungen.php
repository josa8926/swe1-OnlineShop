<?php
session_start();

// Prüfen ob Benutzer eingeloggt ist
if (!isset($_SESSION['userid'])) {
    header("Location: ../login2.php");
    exit;
}

include '/var/www/html/private/dbconnection.inc.php';

$mysqli = new mysqli($servername, $username, $password, $db);
if ($mysqli->connect_error) {
    die("DB Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

$userid = $_SESSION['userid'];

// Benutzername für Anzeige holen
$stmt = $mysqli->prepare("SELECT name FROM USERS WHERE id=?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$stmt->bind_result($username_display);
$stmt->fetch();
$stmt->close();

// Alle Bestellungen des Benutzers holen
$stmt = $mysqli->prepare("
    SELECT
        b.id,
        b.datum,
        b.gesamtpreis,
        b.status,
        b.lieferadresse,
        b.zahlungsmethode
    FROM BESTELLUNGEN b
    WHERE b.user_id = ?
    ORDER BY b.datum DESC
");
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$bestellungen = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meine Bestellungen – Jenny Afro & Asia Shop</title>
    <link rel="stylesheet" href="../includes/style3.css">
    <link rel="stylesheet" href="includes/bestellungen.css">
</head>
<body>

<!-- Top Bar mit Login/Register -->
<div class="topbar">
    <div class="container">
        <div class="topbar-content">
            <span>📍 Bremerhaven </span>
            <div class="topbar-links">
                <a href="../profil.php">Mein Konto</a>
                <a href="../logout.php">Ausloggen</a>
            </div>
        </div>
    </div>
</div>

<!-- Header -->
<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <a href="../afroseite2.php" style="text-decoration: none; color: inherit;">
                    <span class="logo-icon">🌍</span>
                    <span class="logo-text"><strong>Jenny</strong><br><strong>Afro & Asia Shop</strong></span>
                </a>
            </div>

            <nav class="main-nav">
                <a href="../Projekt/produkte/index.html">Shop</a>
                <a href="../Projekt/produkte/afro.html">Afrikanisch</a>
                <a href="../Projekt/produkte/asia.html">Asiatisch</a>
                <a href="bestellungen.php">Meine Bestellungen</a>
            </nav>

            <div class="header-actions">
                <a href="../Projekt/produkte/warenkorb.html" class="cart-btn">
                    <span class="cart-icon">🛒</span>
                    <span class="cart-count">0</span>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Bestellungen Container -->
<div class="bestellungen-container">
    <h1 class="page-title">Meine Bestellungen</h1>
    <p class="welcome-text">Willkommen zurück, <?php echo htmlspecialchars($username_display); ?>!</p>

    <?php if (empty($bestellungen)): ?>
        <div class="no-orders">
            <div class="no-orders-icon">📦</div>
            <p class="no-orders-text">Sie haben noch keine Bestellungen aufgegeben.</p>
            <a href="../Projekt/produkte/index.html" class="btn-details">Jetzt einkaufen</a>
        </div>
    <?php else: ?>
        <?php foreach ($bestellungen as $bestellung): ?>
            <div class="bestellung-card">
                <div class="bestellung-header">
                    <div>
                        <div class="bestellung-nummer">Bestellung #<?php echo htmlspecialchars($bestellung['id']); ?></div>
                        <div class="bestellung-datum">
                            <?php
                            $datum = new DateTime($bestellung['datum']);
                            echo $datum->format('d.m.Y - H:i');
                            ?> Uhr
                        </div>
                    </div>
                    <div class="preis">
                        <?php echo number_format($bestellung['gesamtpreis'], 2, ',', '.'); ?> €
                    </div>
                </div>

                <div class="bestellung-details">
                    <div class="detail-item">
                        <span class="detail-label">Status</span>
                        <span class="detail-value">
                            <?php
                            $status_class = 'status-' . strtolower($bestellung['status']);
                            $status_text = $bestellung['status'];
                            ?>
                            <span class="status-badge <?php echo $status_class; ?>">
                                <?php echo htmlspecialchars($status_text); ?>
                            </span>
                        </span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Zahlungsmethode</span>
                        <span class="detail-value">
                            <?php echo htmlspecialchars($bestellung['zahlungsmethode'] ?? 'Nicht angegeben'); ?>
                        </span>
                    </div>

                    <div class="detail-item">
                        <span class="detail-label">Lieferadresse</span>
                        <span class="detail-value">
                            <?php echo htmlspecialchars($bestellung['lieferadresse']); ?>
                        </span>
                    </div>
                </div>

                <div class="bestellung-actions">
                    <a href="bestellung_details.php?id=<?php echo $bestellung['id']; ?>" class="btn-details">
                        Details ansehen
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
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
