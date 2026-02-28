<?php
session_start();
?>

<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Jenny Afro & Asia Shop – Bremerhaven</title>
<link rel="stylesheet" href="../includes/style2.css">
</head>

<body class="Hauptseite">

<!--<div class="topbar">
    <a href="login2.php">Einloggen</a>
    <a href="register.php">Registrieren</a>
</div>--!>
<div class="topbar">
<?php if (isset($_SESSION['userid'])): ?>
    <a href="profil_update.php">Mein Konto</a>
    <a href="logout.php">Ausloggen</a>
<?php else: ?>
    <a href="login2.php">Einloggen</a>
    <a href="register.php">Registrieren</a>
<?php endif; ?>
</div>


<header>
    <div class="logo">Jenny Afro & Asia Shop</div>
    <div class="header-right">
     <a href="produkte/warenkorb.html">Warenkorb</a> 
        <div class="cart">🛒</div>
    </div>
</header>

<nav>
    <a href="produkte/index.html">Shop</a>
    <a href="produkte/afro.html">Afrika</a>
    <a href="produkte/asia.html">Asien</a>
    <a href="kontakt.html">Kontakt</a>
</nav>

<section class="hero">
    <div class="hero-box">
        <h1>Afro & Asia Lebensmittel in Bremerhaven</h1>
        <p>Qualität, Vielfalt und authentische Produkte</p>
        <button>Zum Shop</button>
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

<footer class="hauptseite">
    © 2026 Jenny Afro & Asia Shop – Bremerhaven
</footer>

</body>
</html>
