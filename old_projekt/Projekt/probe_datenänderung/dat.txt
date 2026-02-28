<?php
session_start();
include '/var/www/html/private/dbconnection.inc.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.html?error=not_logged_in");
    exit();
}

$email = $_SESSION['user'];

$conn = new mysqli($servername, $username, $password, $db);
if ($conn->connect_error) die("DB Verbindung fehlgeschlagen: " . $conn->connect_error);

$stmt = $conn->prepare("SELECT name, email FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($name, $current_email);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Profil bearbeiten</title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
.profile-box { background: white; padding: 30px; max-width: 400px; margin: auto; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
label { display: block; margin-top: 15px; font-weight: bold; }
input { width: 100%; padding: 10px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
button { margin-top: 20px; padding: 12px; background: #1f4037; color: white; border: none; border-radius: 6px; cursor: pointer; width: 100%; }
button:hover { background: #16362f; }
.success { color: green; margin-top: 10px; }
.error { color: red; margin-top: 10px; }
.logout { text-align: center; margin-top: 20px; }
</style>
</head>
<body>
<div class="profile-box">
<h2>Profil bearbeiten</h2>

<?php if (isset($_GET['success'])): ?>
    <div class="success">Daten erfolgreich aktualisiert!</div>
<?php elseif (isset($_GET['error'])): ?>
    <div class="error"><?= htmlspecialchars($_GET['error']) ?></div>
<?php endif; ?>

<form action="update_profile.php" method="post">
    <label for="email">E-Mail</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($current_email) ?>" required>

    <label for="password">Neues Passwort (leer lassen, falls nicht ändern)</label>
    <input type="password" id="password" name="password">

    <label for="password_confirm">Neues Passwort wiederholen</label>
    <input type="password" id="password_confirm" name="password_confirm">

    <button type="submit">Speichern</button>
</form>

<div class="logout">
    <a href="logout.php">Ausloggen</a>
</div>
</div>
</body>
</html>

