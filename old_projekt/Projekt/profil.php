<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit;
}
if (isset($_SESSION['userid'])) {
    header("Location: afroseite3.php");
    exit;
}
include '/var/www/html/private/dbconnection.inc.php';

$mysqli = new mysqli($servername, $username, $password, $db);
if ($mysqli->connect_error) {
    die("DB Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

$userid = $_SESSION['userid'];

$stmt = $mysqli->prepare("SELECT name, email FROM USERS WHERE id=?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Profil bearbeiten</title>
</head>
<body>

<h1>Profil bearbeiten</h1>

<form action="profil_update.php" method="post">
    <input type="hidden" name="action" value="update_profile">

    <label>Name</label>
    <input type="text" name="name" value=" <?php echo htmlspecialchars($name); ?>" required>

    <label>E-Mail</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

    <button type="submit">Speichern</button>
</form>

<hr>

<h2>Passwort ändern</h2>

<form action="profil_update.php" method="post">
    <input type="hidden" name="action" value="update_password">

    <label>Aktuelles Passwort</label>
    <input type="password" name="old_password">

    <label>Neues Passwort</label>
    <input type="password" name="new_password">

    <label>Neues Passwort wiederholen</label>
    <input type="password" name="new_password2">

    <button type="submit">Passwort ändern</button>
</form>

</body>
</html>

