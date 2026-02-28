<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Produktverwaltung</title>
<link rel="stylesheet" href="./includes/admin-style.css">
</head>
<body>
<div class="notification-panel">
    <?php if (!empty($message)): ?>
        <div class="notification success"><?php echo $message; ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="notification error"><?php echo $error; ?></div>
    <?php endif; ?>
</div>

<div class="container">

    <!-- Header avec lien page principale -->
    <div class="top-bar">
        <h1>Verwaltung</h1>
        <a href="afroseite2.php" class="link-home">🏠 Zur Hauptseite</a>
    </div>

    <!-- Onglets -->
    <div class="tab-bar">
        <a href="?tab=produkte" class="<?php echo ($tab === 'produkte') ? 'active' : ''; ?>">📦 Produktverwaltung</a>
        <a href="?tab=nachrichten" class="<?php echo ($tab === 'nachrichten') ? 'active' : ''; ?>">✉️ Nachrichten</a>
    </div>

    <!-- TAB: PRODUKTVERWALTUNG -->
    <?php if ($tab === 'produkte'): ?>

        <?php if ($message): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

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
            <?php foreach ($products as $p): ?>
            <tr>
                <td><?php echo $p['produkt_id']; ?></td>
                <td><?php echo htmlspecialchars($p['name']); ?></td>
                <td><?php echo $p['preis']; ?> €</td>
                <td><?php echo $p['preis_pro_kg']; ?> €</td>
                <td><?php echo $p['menge']; ?></td>
                <td><?php echo $p['Herkunft']; ?></td>
                <td><?php echo $p['Kategorien']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

    <!-- TAB: NACHRICHTEN (Bestellungen bezahlt) -->
    <?php elseif ($tab === 'nachrichten'): ?>

        <h2>📬 Offene Bestellungen <small style="font-size:13px; color:#aaa;">(Status: bezahlt)</small></h2>

        <?php if (empty($bestellungen)): ?>
            <div class="empty-state">✅ Keine offenen Bestellungen vorhanden.</div>
        <?php else: ?>
            <?php foreach ($bestellungen as $b): ?>
                <div class="bestellung-card" id="card-<?php echo $b['id']; ?>">
                    <div class="bestellung-info">
                        <h3>
                            Bestellung #<?php echo htmlspecialchars($b['id']); ?>
                            &nbsp;<a href="bestell_details_verwalterin.php?id=<?php echo $b['id']; ?>" style="font-size:12px; font-weight:normal; color:#1976D2; text-decoration:none;">🔍 Details</a>
                        </h3>
                        <p>
                            <strong><?php echo htmlspecialchars($b['vorname']) . ' ' . htmlspecialchars($b['nachname']); ?></strong>
                            &nbsp;|&nbsp; <?php echo htmlspecialchars($b['email']); ?>
                        </p>
                        <p>
                            <?php echo htmlspecialchars($b['adresse']); ?>,
                            <?php echo htmlspecialchars($b['plz']); ?> <?php echo htmlspecialchars($b['ort']); ?>
                        </p>
                        <p>
                            Status: <span class="status-badge"><?php echo htmlspecialchars($b['status']); ?></span>
                            &nbsp;|&nbsp;
                            Gesamt: <strong>
                                <?php echo $b['gesamtpreis'] ? number_format($b['gesamtpreis'], 2, ',', '.') . ' €' : '–'; ?>
                            </strong>
                        </p>
                    </div>
                    <div class="bestellung-actions">
                        <span class="feedback-msg" id="msg-<?php echo $b['id']; ?>">✔ Bestellung verarbeitet</span>
                        <button class="btn-ok" onclick="bestellungOk(<?php echo $b['id']; ?>)">OK</button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    <?php endif; ?>

</div>

<script>
function bestellungOk(id) {
    fetch('verwalt.php?action=verarbeiten&id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const msg  = document.getElementById('msg-' + id);
                const card = document.getElementById('card-' + id);
                msg.style.display = 'inline';
                setTimeout(function() {
                    card.classList.add('verarbeitet');
                    setTimeout(function() {
                        card.style.display = 'none';
                    }, 450);
                }, 900);
            }
        })
        .catch(err => console.error('Fehler:', err));
}
</script>

<footer style="text-align:center; padding: 24px 0; color:#aaa; font-size:13px;">
    &copy; 2026 Jenny Afro &amp; Asia Shop. Alle Rechte vorbehalten.
</footer>
</body>
</html>
