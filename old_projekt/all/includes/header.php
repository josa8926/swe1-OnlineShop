<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);
$isHome = ($currentPage === 'afroseite3.php');
?>

<header>
    <div class="logo">
        <a href="afroseite3.php">Jenny Afro & Asia Shop</a>
    </div>

    <?php if ($isHome): ?>
        <!--<div class="header-right">--!>
        <div class="topbar">
            <?php if (isset($_SESSION['userid'])): ?>
                <a href="profil.php">Mein Konto</a>
                <a href="logout.php">Ausloggen</a>
            <?php else: ?>
                <a href="login2.php">Einloggen</a>
                <a href="register.php">Registrieren</a>
            <?php endif; ?>
            <span class="cart">🛒</span>
        </div>
    <?php else: ?>
        <a href="afroseite3.php" class="back-home">← Zurück zur Startseite</a>
    <?php endif; ?>
</header>

