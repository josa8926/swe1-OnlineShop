<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8">
    <title>Registrierung - Jenny Afro & Asia Shop</title>
    <meta name="viewport" content="width=device-width , initial-scale=1.0">
     <link rel="stylesheet" href="../includes/style2.css">
  </head>
  <body class="reg">
<header class="reglog">
<h2> Jenny Afro and Asia Shop </h2>
<a href="afroseite3.php" class="back-home">Startseite</a>
</header>
    <div class="Registrierung-box">

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

<!--      <h1>Jenny Afro & Asia Shop</h1>--!>
      <p>Registrierung</p>
      <form action="reg_process.php" method="post">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="name" required>
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" placeholder="e-Mail" required>
        <label for="passwort1">Passwort</label>
        <input type="password" id="passwort1" name="passwort1" placeholder="passwort" required>
        <input type="password" id="passwort2" name="passwort2" placeholder="passwort wiederholen" required>
        <button type="submit">Registrieren</button>
      </form>
      <div class="footer">
        © Jenny Afro & Asia Shop  
      </div>

  </body>
</html>

