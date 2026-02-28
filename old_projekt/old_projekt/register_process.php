<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $vorname = trim($_POST['vorname']);
    $nachname = trim($_POST['nachname']);
    
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
    $mysqli = new mysqli('localhost', 'username', 'passwort', 'jenny_shop');
    
    $stmt = $mysqli->prepare("INSERT INTO users (email, password_hash, vorname, nachname) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $email, $password_hash, $vorname, $nachname);
    
    if ($stmt->execute()) {
        header("Location: login2.php?success=1");
    } else {
        header("Location: register.php?error=1");
    }
    
    $stmt->close();
    $mysqli->close();
}
?>
