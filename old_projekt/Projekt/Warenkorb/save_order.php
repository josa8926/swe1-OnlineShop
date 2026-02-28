<?php
// save_order.php

header("Content-Type: text/plain; charset=UTF-8");

// DB-Verbindung einbinden
include '/var/www/html/private/dbconnection.inc.php';

try {
    // PDO-Verbindung
    $pdo = new PDO(
        "mysql:host=$servername;dbname=$db;charset=utf8",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Nur POST erlauben
    $method = $_SERVER['REQUEST_METHOD'] ?? null;
    if ($method !== 'POST') {
        http_response_code(405);
        exit("Nur POST erlaubt");
    }

    // JSON-Daten vom Frontend einlesen
    $raw = file_get_contents("php://input");
    $data = json_decode($raw, true);

    if (!$data || !isset($data['produkte']) || !is_array($data['produkte']) || count($data['produkte']) === 0) {
        http_response_code(400);
        exit("Fehler: Keine Daten oder keine Produkte übergeben");
    }

    // Bestellung in orders speichern
    $stmt = $pdo->prepare("
        INSERT INTO Bestellung (vorname, nachname, adresse, ort, zusatz, plz, email, handy)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $data["vorname"],
        $data["nachname"],
        $data["adresse"],
        $data["ort"],
        $data["zusatz"],
        $data["plz"],
        $data["email"],
        $data["handy"]
    ]);

    // ID der gerade gespeicherten Bestellung
    $orderId = $pdo->lastInsertId();

    if (!$orderId) {
        throw new Exception("Fehler beim Speichern der Bestellung");
    }

    // Produkte in order_items speichern
    $stmt2 = $pdo->prepare("
        INSERT INTO Produkte (order_id, title, price, qty)
        VALUES (?, ?, ?, ?)
    ");

    foreach ($data["produkte"] as $p) {
        // Preis in Zahl umwandeln
        $price = str_replace(",", ".", str_replace("€", "", $p["price"]));
        $price = floatval($price);

        $stmt2->execute([
            $orderId,
            $p["title"],
            $price,
            $p["qty"]
        ]);
    }

    echo "OK";

} catch (PDOException $e) {
    http_response_code(500);
    echo "Datenbank-Fehler: " . $e->getMessage();
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo "Fehler: " . $e->getMessage();
    exit;
}
?>

