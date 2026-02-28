<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8">
    <title>Registrierung - Jenny Afro & Asia Shop</title>
    <meta name="viewport" content="width=device-width , initial-scale=1.0">
<link rel="stylesheet" href="includes/style.css">
  </head>
  <body class="log">
<?php include "includes/header.php"; ?>
<main class="auth-container">
      <?php if (isset($_GET["error"])): ?>
      <div class="error-box">
        <?php
           if ($_GET["error"] === "empty") {
           echo "Bitte alle Felder ausfüllen";
           } elseif ($_GET["error"] === "password") {
           echo "Passwörter stimmen nicht überein";
           } elseif ($_GET["error"] === "email_exists") {
           echo "E-Mail existiert bereits";
           }
           ?>
      </div>
      <?php endif; ?>

  <div class="auth-box">
    <h1>Registrierung</h1>

    <form action="reg_process.php" method="post">
      <input type="text" name="name" placeholder="Name" required>
      <input type="email" name="email" placeholder="E-Mail" required>
      <input type="password" name="passwort1" placeholder="Passwort" required>
      <input type="password" name="passwort2" placeholder="Passwort wiederholen" required>
      <button>Registrieren</button>
    </form>
<?php include "includes/footer.php"; ?>
  </div>
</main>
  </body>
</html>

