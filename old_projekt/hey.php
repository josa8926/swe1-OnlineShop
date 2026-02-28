<?php
session_start();
$user_id = intval($_SESSION['userid'] ?? 0);  // ← intval() hinzufügen
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8">
    <title>Kontakt - Hafenstraße 179</title>
    <link rel="stylesheet" href="includes/style_kontakt.css">
  </head>
  <body data-user-id="<?php echo $user_id; ?>">
    
    <!-- Dein Inhalt hier -->
    
    <!-- JavaScript am Ende -->
    <script>
      const userId = parseInt(document.body.dataset.userId || 0);
      console.log('User-ID:', userId);  // Test-Ausgabe
    </script>
  </body>
</html>
