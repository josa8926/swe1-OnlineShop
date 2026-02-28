<?php
// DEBUG Version - Zeigt alle Fehler an
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>DEBUG: Bestellungen</h1>";

// Schritt 1: Session prüfen
echo "<p><strong>Schritt 1:</strong> Session starten...</p>";
session_start();

echo "<p>Session gestartet ✓</p>";
echo "<p>Session ID: " . session_id() . "</p>";

// Schritt 2: User ID prüfen
echo "<p><strong>Schritt 2:</strong> User ID prüfen...</p>";

if (!isset($_SESSION['userid'])) {
    echo "<p style='color: red;'>❌ FEHLER: Nicht eingeloggt!</p>";
    echo "<p>Session-Inhalt:</p>";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    echo "<p><a href='login2.php'>Zum Login</a></p>";
    exit;
}

echo "<p>✓ Eingeloggt als User ID: " . $_SESSION['userid'] . "</p>";

$userid = $_SESSION['userid'];

// Schritt 3: Datenbankverbindung
echo "<p><strong>Schritt 3:</strong> Datenbankverbindung...</p>";

$dbfile = '/var/www/html/private/dbconnection.inc.php';
echo "<p>Suche Datei: $dbfile</p>";

if (!file_exists($dbfile)) {
    echo "<p style='color: red;'>❌ FEHLER: dbconnection.inc.php nicht gefunden!</p>";
    exit;
}

echo "<p>✓ Datei gefunden</p>";

include $dbfile;

echo "<p>DB Server: $servername</p>";
echo "<p>DB Name: $db</p>";
echo "<p>DB User: $username</p>";

$mysqli = new mysqli($servername, $username, $password, $db);

if ($mysqli->connect_error) {
    echo "<p style='color: red;'>❌ DB Fehler: " . $mysqli->connect_error . "</p>";
    exit;
}

echo "<p>✓ Datenbank verbunden</p>";

// Schritt 4: Tabelle prüfen
echo "<p><strong>Schritt 4:</strong> Tabelle BESTELLUNGEN prüfen...</p>";

$result = $mysqli->query("SHOW TABLES LIKE 'BESTELLUNGEN'");
if ($result->num_rows == 0) {
    echo "<p style='color: red;'>❌ FEHLER: Tabelle BESTELLUNGEN existiert nicht!</p>";
    echo "<p>Verfügbare Tabellen:</p>";
    $tables = $mysqli->query("SHOW TABLES");
    echo "<ul>";
    while ($row = $tables->fetch_array()) {
        echo "<li>" . $row[0] . "</li>";
    }
    echo "</ul>";
    exit;
}

echo "<p>✓ Tabelle BESTELLUNGEN gefunden</p>";

// Schritt 5: Bestellungen laden
echo "<p><strong>Schritt 5:</strong> Bestellungen laden...</p>";

$sql = "SELECT id, datum, gesamtpreis, status FROM BESTELLUNGEN WHERE user_id = $userid ORDER BY datum DESC";
echo "<p>SQL: <code>$sql</code></p>";

$result = $mysqli->query($sql);

if (!$result) {
    echo "<p style='color: red;'>❌ SQL FEHLER: " . $mysqli->error . "</p>";
    exit;
}

echo "<p>✓ SQL ausgeführt</p>";
echo "<p>Anzahl Bestellungen: " . $result->num_rows . "</p>";

// Schritt 6: Anzeigen
echo "<p><strong>Schritt 6:</strong> Bestellungen anzeigen...</p>";

if ($result->num_rows == 0) {
    echo "<p>Keine Bestellungen vorhanden.</p>";
} else {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr><th>ID</th><th>Datum</th><th>Preis</th><th>Status</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['datum'] . "</td>";
        echo "<td>" . $row['gesamtpreis'] . " €</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}

echo "<hr>";
echo "<p><a href='afroseite2.php'>Zurück zur Startseite</a></p>";

$mysqli->close();

echo "<p><strong>✓ ALLES FERTIG!</strong></p>";
?>
