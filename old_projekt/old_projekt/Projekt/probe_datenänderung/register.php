<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8">
    <title>Registrierung - Jenny Afro & Asia Shop</title>
    <meta name="viewport" content="width=device-width , initial-scale=1.0">
    <style>
.error-box {
  background: #ffebee;
  color: #b71c1c;
  padding: 12px;
  border-radius: 6px;
  margin-bottom: 20px;
  text-align: center;
  font-size: 14px;
}

body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
  background: linear-gradient(135deg, #1f4037, #99f2c8);
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

        .Registrierung-box {
          background: white;
          padding: 40px;
          width: 100%;
          max-width: 380px;
          border-radius: 12px;
          box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .Registrierung-box h1 {
          text-align: center;
          margin-bottom: 10px;
          color: #1f4037;
        }

        .Registrierung-box p {
          text-align: center;
          font-size: 14px;
          color: #555;
          margin-bottom: 30px;
        }

        .Registrierung-box label {
          display: block;
          margin-bottom: 5px;
          font-weight: bold;
          color: #333;
        }

        .Registrierung-box input {
          width: 100%;
          padding: 12px;
          margin-bottom: 20px;
          border-radius: 6px;
          border: 1px solid #ccc;
          font-size: 14px;
        }

        .Registrierung-box button {
          width: 100%;
          padding: 12px;
          background: #1f4037;
          color: white;
          border: none;
          border-radius: 6px;
          font-size: 16px;
          cursor: pointer;
        }

        .Registrierung-box button:hover {
          background: #16362f;
        }

        .footer {
          text-align: center;
          margin-top: 20px;
          font-size: 12px;
          color: #777;
        }
    </style>
  </head>
  <body>
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

      <h1>Jenny Afro & Asia Shop</h1>
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

