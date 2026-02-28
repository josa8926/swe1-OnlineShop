<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header("Location: login2.php");
    exit;
}

include '/var/www/html/private/dbconnection.inc.php';
$mysqli = new mysqli($servername, $username, $password, $db);

if ($mysqli->connect_error) {
    die("Datenbankfehler: " . $mysqli->connect_error);
}

$userid = $_SESSION['userid'];

$stmt = $mysqli->prepare("SELECT name FROM USERS WHERE id = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$stmt->bind_result($username_display);
$stmt->fetch();
$stmt->close();

if (empty($username_display)) {
    $username_display = "Kunde";
}

$sql = "SELECT id, vorname, nachname, adresse, ort, plz, email, handy, status, 
               (SELECT SUM(price * qty) FROM Produkte WHERE order_id = Bestellung.id) as gesamtpreis
        FROM Bestellung 
        WHERE email = (SELECT email FROM USERS WHERE id = $userid)
        ORDER BY id DESC";

$result = $mysqli->query($sql);

if (!$result) {
    die("SQL Fehler: " . $mysqli->error);
}

$bestellungen = [];
while ($row = $result->fetch_assoc()) {
    $bestellungen[] = $row;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meine Bestellungen – Jenny Afro & Asia Shop</title>
    <link rel="stylesheet" href="includes/bestellung.css">
</head>
<body>

<h1>🛍️ Meine Bestellungen</h1>

<p>Willkommen zurück <strong><?php echo htmlspecialchars($user); ?></strong></p>

<p>
    <a href="afroseite2.php">Startseite</a> 
</p>

<hr>

<?php if (empty($bestellungen)): ?>
    
    <h2>📦 Keine Bestellungen vorhanden</h2>
    <p>Sie haben noch keine Bestellungen aufgegeben.</p>
    <p><a href="Projekt/produkte/index.html"><strong> Jetzt einkaufen</strong></a></p>
    
<?php else: ?>
    
    <h2>Ihre Bestellungen (<?php echo count($bestellungen); ?>)</h2>
    
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <thead>
            <tr style="background-color: #f0f0f0;">
                <th>Bestellung Nr.</th>
                <th>Name</th>
                <th>Lieferadresse</th>
                <th>Status</th>
                <th>Gesamtpreis</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($bestellungen as $bestellung): ?>
            <tr>
                <td><strong>#<?php echo htmlspecialchars($bestellung['id']); ?></strong></td>
                <td>
                    <?php 
                    echo htmlspecialchars($bestellung['vorname']) . ' ' . 
                         htmlspecialchars($bestellung['nachname']); 
                    ?>
                </td>
                <td>
                    <?php 
                    echo htmlspecialchars($bestellung['adresse']) . '<br>';
                    echo htmlspecialchars($bestellung['plz']) . ' ' . 
                         htmlspecialchars($bestellung['ort']);
                    ?>
                </td>
                <td>
                    <strong><?php echo htmlspecialchars($bestellung['status']); ?></strong>
                </td>
                <td>
                    <strong>
                        <?php 
                        if ($bestellung['gesamtpreis']) {
                            echo number_format($bestellung['gesamtpreis'], 2, ',', '.') . ' €';
                        } else {
                            echo '-';
                        }
                        ?>
                    </strong>
                </td>
                <td>
                    <a href="bestellung_details.php?id=<?php echo $bestellung['id']; ?>">
                        Details ansehen 
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    
<?php endif; ?>

<hr>

<p><small>&copy; 2026 Jenny Afro & Asia Shop. Alle Rechte vorbehalten.</small></p>

</body>
</html>
<?php 
$mysqli->close(); 
?>
