<?php
session_start();
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Jenny Afro & Asia Shop – Bremerhaven</title>
<link rel="stylesheet" href="includes/style_startseite.css">
<style>
body {
    /* Afrikanisches Hintergrundbild (Platzhalter) */
    background: url('https://img.freepik.com/photos-premium/table-brunch-dans-cafe-nourriture-delicieuse-table-petit-dejeuner-brunch-remplie-toutes-sortes-nourriture-delicieuse_343960-31696.jpg') center/cover no-repeat fixed;
    position: relative;
    color: #111;
}
</style>
</head>
<body class="Hauptseite">
<div class="topbar">
<?php if (isset($_SESSION['userid'])): ?>
    <a href="profil.php">Mein Konto</a>
    <a href="logout.php">Ausloggen</a>

<?php else: ?>
    <a href="login2.php">Einloggen</a>
    <a href="register.php">Registrieren</a>


<?php endif; ?>

</div>

<header>
    <div class="logo">Jenny Afro & Asia Shop</div>
    <div class="header-right"></div>
    <div class="cart"><a href="#">🛒</a></div>
</header>
<nav>
    <a href="Warenkorb/warenkorb.html">Shop</a>
    <a href="#">Afrika</a>
    <a href="#">Asien</a>
    <a href="kontakt.php">Kontakt</a>
</nav>

<section class="hero">
    <div class="hero-box">
        <h1>Afro & Asia Lebensmittel in Bremerhaven</h1>
        <p>Qualität, Vielfalt und authentische Produkte</p>
        <a href="Warenkorb/warenkorb.html"><button>Zum Shop</button></a>
    </div>
</section>

<section class="flags">
    <span title="Kamerun">🇨🇲</span>
    <span title="Nigeria">🇳🇬</span>
    <span title="Ghana">🇬🇭</span>
    <span title="Elfenbeinküste">🇨🇮</span>
    <span title="China">🇨🇳</span>
    <span title="Thailand">🇹🇭</span>
</section>

<section class="categories">
    <h2>Beliebte Kategorien</h2>
    <div class="category-grid">
        <div class="category">Reis & Getreide</div>
        <div class="category">Gewürze</div>
        <div class="category">Getränke</div>
        <div class="category">Trockene Lebensmittel</div>
        <div class="category">Afrikanische Produkte</div>
        <div class="category">Asiatische Produkte</div>
        <div class="category">Soßen & Öle</div>
        <div class="category">Hülsenfrüchte</div>
    </div>
</section>
<?php include "includes/footer.php"; ?>

</body>
</html>
