<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login – Jenny Afro & Asia Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../includes/style2.css">
  </head>
<body class="login">
<header class="reglog">
<h2> Jenny Afro and Asia Shop </h2>
<a href="afroseite2.php" class="back-home">Startseite</a>
</header>
<div class="login-box">
<?php if (isset($_COOKIE["success"])): ?>
    <div class="success-box">
        <?php echo $_COOKIE["success"]; ?>
    </div>
<?php endif; ?>
<?php if (isset($_GET["error"])): ?>
      <div class="error-box">
<?php
if ($_GET["error"] === "false") {
  echo "Passwort oder E-mail ungültig";
}
if ($_GET["error"] === "empty") {
  echo "Füllen Sie alle Felder aus!";
}
?>
      </div>
      <?php endif; ?>

<!--<h1>Jenny Afro & Asia Shop</h1>--!>
    <p>Login </p>

    <form action="log.php" method="post">
        <label for="email">E-Mail</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Passwort</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Anmelden</button>
    </form>

    <div class="footer">
        © Jenny Afro & Asia Shop
    </div>
</div>
</body>
</html>
