<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login – Jenny Afro & Asia Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="includes/style.css">
  </head>
<body class="log">
<?php include "includes/header.php"; ?>
<main class="auth-container">
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

  <div class="auth-box">
    <h1>Login</h1>

    <form action="log.php" method="post">
      <input type="email" name="email" placeholder="E-Mail" required>
      <input type="password" name="password" placeholder="Passwort" required>
      <button>Anmelden</button>
    </form>
<?php include "includes/footer.php"; ?>
  </div>
</main>
</body>
</html>
