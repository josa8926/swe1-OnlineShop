<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
</head>
<body>
<script>
  localStorage.removeItem('ware');
  window.location.href = 'afroseite2.php';
</script>
</body>
</html>
