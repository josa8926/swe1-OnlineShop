<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Registrierung – Jenny Afro & Asia Shop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="includes/style_log-regis.css">
</head>
<body>
    <div class="auth-page">
        <!-- Zurück Button -->
        <a href="afroseite2.php" class="back-button">← Zurück zur Startseite</a>

        <div class="auth-container">
            <div class="auth-box">
                <h1>Registrierung</h1>

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

                <form action="reg_process.php" method="post">
                    <div class="input-group">
                        <input type="text" name="name" placeholder="Name" required>
                    </div>

                    <div class="input-group">
                        <input type="email" name="email" placeholder="E-Mail" required>
                    </div>

                    <div class="input-group password-group">
                        <input type="password" name="passwort1" id="password1" placeholder="Passwort" required>
                        <span class="toggle-password" onclick="togglePassword('password1')">👁️</span>
                    </div>

                    <div class="input-group password-group">
                        <input type="password" name="passwort2" id="password2" placeholder="Passwort wiederholen" required>
                        <span class="toggle-password" onclick="togglePassword('password2')">👁️</span>
                    </div>

                    <button type="submit" class="btn-submit">Registrieren</button>
                </form>

                <div class="auth-footer">
                    <p>Du hast bereits ein Konto? <a href="login2.php">Anmelden</a></p>
                </div>
            </div>

            <footer class="auth-page-footer">
                <p>&copy; 2026 Jenny Afro & Asia Shop – Bremerhaven</p>
            </footer>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = passwordInput.parentElement.querySelector('.toggle-password');
            
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
