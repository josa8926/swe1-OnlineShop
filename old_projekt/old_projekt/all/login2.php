<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Login – Jenny Afro & Asia Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="includes/style_log-regis.css">
</head>
<body>
    <div class="auth-page">
        <!-- Zurück Button -->
        <a href="afroseite2.php" class="back-button">← Zurück zur Startseite</a>

        <div class="auth-container">
            <div class="auth-box">
                <h1>Login</h1>

                <?php if (isset($_COOKIE["success"])): ?>
                    <div class="success-box">
                        <?php echo $_COOKIE["success"]; ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET["error"])): ?>
                    <div class="error-box">
                        <?php
                            if ($_GET["error"] === "false") {
                                echo "Passwort oder E-Mail ungültig";
                            }
                            if ($_GET["error"] === "empty") {
                                echo "Füllen Sie alle Felder aus!";
                            }
                        ?>
                    </div>
                <?php endif; ?>

                <form action="log.php" method="post">
                    <div class="input-group">
                        <input type="email" name="email" placeholder="E-Mail" required>
                    </div>

                    <div class="input-group password-group">
                        <input type="password" name="password" id="password" placeholder="Passwort" required>
                        <span class="toggle-password" onclick="togglePassword()">👁️</span>
                    </div>

                    <button type="submit" class="btn-submit">Anmelden</button>
                </form>

                <div class="auth-footer">
                    <p>Du hast noch kein Konto? <a href="register.php">Registrieren</a></p>
                </div>
            </div>

            <footer class="auth-page-footer">
                <p>&copy; 2026 Jenny Afro & Asia Shop – Bremerhaven</p>
            </footer>
        </div>
    </div>
                            <script>
                            // Success-Box nach 5 Sekunden ausblenden
setTimeout(function() {
    const successBox = document.querySelector('.success-box');
    if (successBox) {
        successBox.style.display = 'none';
    }
}, 5000);

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = '👁️';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = '👁️';
            }
        }
    </script>
</body>
</html>

