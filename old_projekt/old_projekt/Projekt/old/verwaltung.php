<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verwaltung - Produktverwaltung</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>
    <div class="container">
        <!-- HEADER -->
        <div class="header">
            <div class="header-title">
                <h1>🛍️ Produktverwaltung</h1>
                <div class="user-info">
                    Angemeldet als: <strong><?php echo htmlspecialchars($_SESSION['email']); ?></strong>
                </div>
            </div>
            <a href="index.php" class="logout">Zur Startseite</a>
        </div>

        <!-- STATISTIKEN -->
        <div class="stats">
            <div class="stat-card">
                <h3>Gesamt Produkte</h3>
                <div class="number"><?php echo $total_products; ?></div>
            </div>
            <div class="stat-card afro">
                <h3>🌍 Afro</h3>
                <div class="number"><?php echo $afro_count; ?></div>
            </div>
            <div class="stat-card asia">
                <h3>🌏 Asia</h3>
                <div class="number"><?php echo $asia_count; ?></div>
            </div>
            <div class="stat-card">
                <h3>Gesamtmenge</h3>
                <div class="number"><?php echo $total_quantity; ?></div>
            </div>
        </div>

        <!-- NACHRICHTEN -->
        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- FORMULAR -->
        <div class="form-section">
            <h2>➕ Produkt hinzufügen</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Produktname *</label>
                    <input type="text" id="name" name="name" required placeholder="z.B. Basmati Reis">
                    <div class="help-text">Wenn das Produkt in derselben Kategorie existiert, wird die Menge hinzugefügt</div>
                </div>

                <div class="form-group">
                    <label for="category">Kategorie *</label>
                    <select id="category" name="category" required>
                        <option value="">-- Kategorie wählen --</option>
                        <option value="afro">🌍 Afro</option>
                        <option value="asia">🌏 Asia</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image_url">Bild-Link</label>
                    <input type="url" id="image_url" name="image_url" placeholder="https://beispiel.com/bild.jpg">
                    <div class="help-text">Vollständige URL des Produktbilds</div>
                </div>

                <div class="form-group">
                    <label for="quantity">Menge *</label>
                    <input type="number" id="quantity" name="quantity" min="0" value="1" required>
                    <div class="help-text">Anzahl der Einheiten, die dem Lager hinzugefügt werden sollen</div>
                </div>

                <button type="submit" name="add_product" class="btn">Produkt hinzufügen</button>
            </form>
        </div>

        <!-- PRODUKTLISTE -->
        <div class="products-section">
            <h2>📦 Produktliste</h2>
            <?php if (count($products) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Bild</th>
                            <th>Name</th>
                            <th>Kategorie</th>
                            <th>Menge</th>
                            <th>Datum</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td><strong>#<?php echo $p['id']; ?></strong></td>
                                <td>
                                    <?php if (!empty($p['image_url'])): ?>
                                        <img src="<?php echo htmlspecialchars($p['image_url']); ?>" 
                                             class="product-image" 
                                             alt="<?php echo htmlspecialchars($p['name']); ?>"
                                             onerror="this.src='https://via.placeholder.com/70?text=Kein+Bild'">
                                    <?php else: ?>
                                        <img src="https://via.placeholder.com/70?text=Kein+Bild" 
                                             class="product-image" 
                                             alt="Kein Bild">
                                    <?php endif; ?>
                                </td>
                                <td><strong><?php echo htmlspecialchars($p['name']); ?></strong></td>
                                <td>
                                    <?php if ($p['category'] == 'afro'): ?>
                                        <span class="badge badge-afro">🌍 Afro</span>
                                    <?php else: ?>
                                        <span class="badge badge-asia">🌏 Asia</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php 
                                    $qty = $p['quantity'];
                                    if ($qty == 0) {
                                        echo '<span class="badge badge-low">Ausverkauft</span>';
                                    } elseif ($qty < 10) {
                                        echo '<span class="badge badge-low">' . $qty . '</span>';
                                    } elseif ($qty < 50) {
                                        echo '<span class="badge badge-medium">' . $qty . '</span>';
                                    } else {
                                        echo '<span class="badge badge-high">' . $qty . '</span>';
                                    }
                                    ?>
                                </td>
                                <td><?php echo date('d.m.Y H:i', strtotime($p['created_at'])); ?></td>
                                <td>
                                    <a href="?delete=<?php echo $p['id']; ?>" 
                                       class="btn-delete"
                                       onclick="return confirm('Möchten Sie dieses Produkt wirklich löschen?')">
                                        🗑️ Löschen
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div style="font-size: 70px;">📦</div>
                    <h3>Keine Produkte</h3>
                    <p>Fügen Sie Ihr erstes Produkt oben hinzu!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
