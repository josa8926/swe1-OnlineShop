<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Produktverwaltung</title>
<link rel="stylesheet" href="admin-style.css">
</head>
<body>

<div class="notification-panel">
    <?php if (!empty($message)): ?>
        <div class="notification success">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="notification error">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
</div>

<div class="container">

<h1>Produktverwaltung</h1>

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

</div>
</body>
</html>

