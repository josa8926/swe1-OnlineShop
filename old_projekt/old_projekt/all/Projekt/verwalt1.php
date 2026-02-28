<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '/var/www/html/private/dbconnection.inc.php';
// Vérification admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.php");
    exit;
}

// Inclure ta connexion existante
$mysqli = new mysqli($servername, $username, $password, $db);
if ($mysqli->connect_error) {
  die("DB Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

$message = '';
$error = '';

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = $mysqli->real_escape_string(trim($_POST['name']));
    $category = $mysqli->real_escape_string($_POST['category']);
    $image_url = $mysqli->real_escape_string(trim($_POST['image_url']));
    $quantity = intval($_POST['quantity']);
    
    if (empty($name)) {
        $error = "Le nom du produit est obligatoire.";
    } elseif (!in_array($category, ['afro', 'asia'])) {
        $error = "Catégorie invalide.";
    } elseif ($quantity < 0) {
        $error = "La quantité ne peut pas être négative.";
    } else {
        $sql = "INSERT INTO products (name, category, image_url, quantity) 
                VALUES ('$name', '$category', '$image_url', $quantity)
                ON DUPLICATE KEY UPDATE 
                    quantity = quantity + VALUES(quantity),
                    image_url = VALUES(image_url)";
        
        if ($mysqli->query($sql)) {
            if ($mysqli->affected_rows == 1) {
                $message = "✅ Nouveau produit ajouté avec succès !";
            } else {
                $result = $mysqli->query("SELECT quantity FROM products WHERE name = '$name' AND category = '$category'");
                if ($result && $row = $result->fetch_assoc()) {
                    $message = "✅ Quantité mise à jour ! Nouvelle quantité : " . $row['quantity'];
                }
            }
        } else {
            $error = "❌ Erreur : " . $mysqli->error;
        }
    }
}

// Suppression
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($mysqli->query("DELETE FROM products WHERE id = $id")) {
        $message = "✅ Produit supprimé !";
    }
}

// Récupérer les produits
$products = [];
$result = $mysqli->query("SELECT * FROM products ORDER BY category, id DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$total_products = count($products);
$total_quantity = array_sum(array_column($products, 'quantity'));

// Stats par catégorie
$afro_count = 0;
$asia_count = 0;
foreach ($products as $p) {
    if ($p['category'] == 'afro') $afro_count++;
    else $asia_count++;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Gestion Produits</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <style>
        /* CSS VARIABLEN */
        :root {
            --primary-color: #d4a017;
            --primary-dark: #a17e02;
            --secondary-color: #2d5016;
            --accent-color: #ff6b35;
            --bg-dark: #0f1419;
            --bg-light: #f8f9fa;
            --text-dark: #1a1a1a;
            --text-light: #666;
            --white: #ffffff;
            --shadow: 0 4px 20px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 40px rgba(0,0,0,0.15);
            --border-radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* RESET & BASIS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            background: linear-gradient(180deg, var(--white) 0%, var(--bg-light) 100%);
            line-height: 1.6;
            overflow-x: hidden;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: var(--white);
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
        }

        /* HEADER */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 3px solid var(--primary-color);
        }

        .header-title {
            display: flex;
            flex-direction: column;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            color: var(--text-dark);
            font-weight: 900;
            margin-bottom: 8px;
        }

        .user-info {
            color: var(--text-light);
            font-size: 14px;
            font-weight: 500;
        }

        .user-info strong {
            color: var(--primary-color);
        }

        .logout {
            background: var(--secondary-color);
            color: var(--white);
            padding: 12px 28px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(45, 80, 22, 0.2);
        }

        .logout:hover {
            background: var(--bg-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(45, 80, 22, 0.3);
        }

        /* STATS */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: var(--white);
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card:hover::before {
            top: -30%;
            right: -30%;
        }

        .stat-card.afro {
            background: linear-gradient(135deg, #2d5016 0%, #1a2f0d 100%);
        }

        .stat-card.asia {
            background: linear-gradient(135deg, var(--accent-color) 0%, #e55a2b 100%);
        }

        .stat-card h3 {
            font-size: 14px;
            opacity: 0.9;
            margin-bottom: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            z-index: 1;
        }

        .stat-card .number {
            font-size: 42px;
            font-weight: 900;
            font-family: 'Playfair Display', serif;
            position: relative;
            z-index: 1;
        }

        /* MESSAGES */
        .message {
            padding: 18px 24px;
            margin-bottom: 25px;
            border-radius: var(--border-radius);
            background: #d4edda;
            color: #155724;
            border-left: 5px solid #28a745;
            font-weight: 500;
            box-shadow: var(--shadow);
            animation: slideIn 0.5s ease-out;
        }

        .error {
            padding: 18px 24px;
            margin-bottom: 25px;
            border-radius: var(--border-radius);
            background: #f8d7da;
            color: #721c24;
            border-left: 5px solid #dc3545;
            font-weight: 500;
            box-shadow: var(--shadow);
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* FORM SECTION */
        .form-section {
            background: var(--bg-light);
            padding: 35px;
            border-radius: var(--border-radius);
            margin-bottom: 40px;
            border: 2px solid rgba(212, 160, 23, 0.1);
        }

        .form-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: var(--text-dark);
            margin-bottom: 25px;
            font-weight: 900;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 14px;
        }

        input[type="text"],
        input[type="number"],
        input[type="url"],
        select {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: var(--border-radius);
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            transition: var(--transition);
            background: var(--white);
        }

        select {
            cursor: pointer;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(212, 160, 23, 0.1);
        }

        .btn {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: var(--text-dark);
            padding: 16px 40px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 700;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(212, 160, 23, 0.3);
            font-family: 'Poppins', sans-serif;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(212, 160, 23, 0.4);
        }

        .help-text {
            font-size: 13px;
            color: var(--text-light);
            margin-top: 6px;
            font-style: italic;
        }

        /* PRODUCTS SECTION */
        .products-section {
            margin-top: 40px;
        }

        .products-section h2 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: var(--text-dark);
            margin-bottom: 25px;
            font-weight: 900;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        th {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #1a2f0d 100%);
            color: var(--white);
            padding: 18px 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }

        td {
            padding: 18px 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        tr:hover td {
            background: #fafafa;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .product-image {
            max-width: 70px;
            max-height: 70px;
            border-radius: 8px;
            object-fit: cover;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-low {
            background: #fff3cd;
            color: #856404;
        }

        .badge-medium {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-high {
            background: #d4edda;
            color: #155724;
        }

        .badge-afro {
            background: rgba(45, 80, 22, 0.1);
            color: var(--secondary-color);
            border: 1px solid var(--secondary-color);
        }

        .badge-asia {
            background: rgba(255, 107, 53, 0.1);
            color: var(--accent-color);
            border: 1px solid var(--accent-color);
        }

        .btn-delete {
            background: transparent;
            color: #dc3545;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 13px;
            transition: var(--transition);
            font-weight: 600;
            border: 2px solid #dc3545;
        }

        .btn-delete:hover {
            background: #dc3545;
            color: var(--white);
            transform: scale(1.05);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-light);
        }

        .empty-state h3 {
            margin-top: 20px;
            font-size: 24px;
            color: var(--text-dark);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 20px;
            }

            .header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            h1 {
                font-size: 28px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 13px;
            }

            th, td {
                padding: 12px 8px;
            }

            .product-image {
                max-width: 50px;
                max-height: 50px;
            }
        }

        @media (max-width: 576px) {
            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-title">
                <h1>🛍️ Gestion des Produits</h1>
                <div class="user-info">
                    Connecté : <strong><?php echo htmlspecialchars($_SESSION['email']); ?></strong>
                </div>
            </div>
            <a href="logout.php" class="logout">Déconnexion</a>
        </div>

        <div class="stats">
            <div class="stat-card">
                <h3>Total produits</h3>
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
                <h3>Quantité totale</h3>
                <div class="number"><?php echo $total_quantity; ?></div>
            </div>
        </div>

        <?php if ($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="form-section">
            <h2>➕ Ajouter un produit</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="name">Nom du produit *</label>
                    <input type="text" id="name" name="name" required placeholder="Ex: Riz Basmati">
                    <div class="help-text">Si le produit existe dans la même catégorie, la quantité sera ajoutée</div>
                </div>

                <div class="form-group">
                    <label for="category">Catégorie *</label>
                    <select id="category" name="category" required>
                        <option value="">-- Sélectionner une catégorie --</option>
                        <option value="afro">🌍 Afro</option>
                        <option value="asia">🌏 Asia</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image_url">Lien de l'image</label>
                    <input type="url" id="image_url" name="image_url" placeholder="https://example.com/image.jpg">
                    <div class="help-text">URL complète de l'image du produit</div>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantité *</label>
                    <input type="number" id="quantity" name="quantity" min="0" value="1" required>
                    <div class="help-text">Nombre d'unités à ajouter au stock</div>
                </div>

                <button type="submit" name="add_product" class="btn">Ajouter le produit</button>
            </form>
        </div>

        <div class="products-section">
            <h2>📦 Liste des produits</h2>
            <?php if (count($products) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Quantité</th>
                            <th>Date</th>
                            <th>Actions</th>
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
                                             onerror="this.src='https://via.placeholder.com/70?text=No+Image'">
                                    <?php else: ?>
                                        <img src="https://via.placeholder.com/70?text=No+Image" class="product-image">
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
                                        echo '<span class="badge badge-low">Rupture</span>';
                                    } elseif ($qty < 10) {
                                        echo '<span class="badge badge-low">' . $qty . '</span>';
                                    } elseif ($qty < 50) {
                                        echo '<span class="badge badge-medium">' . $qty . '</span>';
                                    } else {
                                        echo '<span class="badge badge-high">' . $qty . '</span>';
                                    }
                                    ?>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($p['created_at'])); ?></td>
                                <td>
                                    <a href="?delete=<?php echo $p['id']; ?>" 
                                       class="btn-delete"
                                       onclick="return confirm('Supprimer ce produit ?')">
                                        🗑️ Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <div style="font-size: 70px;">📦</div>
                    <h3>Aucun produit</h3>
                    <p>Ajoutez votre premier produit ci-dessus !</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
