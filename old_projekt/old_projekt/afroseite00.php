<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Jenny Afro & Asia Shop – Bremerhaven</title>
<link rel="stylesheet" href="includes/style_startseite.css">
</head>
<body>

<!-- Top Bar mit Login/Register -->
<div class="topbar">
    <div class="container">
        <div class="topbar-content">
            <span>📍 Bremerhaven </span>
            <div class="topbar-links">
                <?php if (isset($_SESSION['userid'])): ?>
                    <a href="profil.php">Mein Konto</a>
                    <a href="logout.php">Ausloggen</a>
                <?php else: ?>
                    <a href="login2.php">Einloggen <span style="filter: brightness(0) invert(1);">👤</span></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Header -->
<header class="header">
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <span class="logo-icon">🌍</span>
                <span class="logo-text"><strong>Jenny</strong><br><strong>Afro & Asia Shop</strong></span>
            </div>

            <nav class="main-nav">
                <a href="Projekt/produkte/index.html">Shop</a>
                <a href="Projekt/produkte/afro.html">Afrikanisch</a>
                <a href="Projekt/produkte/asia.html">Asiatisch</a>
                
                <!-- ⭐⭐⭐ HIER IST DER LINK ZU BESTELLUNGEN ⭐⭐⭐ -->
                <?php if (isset($_SESSION['userid'])): ?>
                    <a href="bestellungen.php">Meine Bestellungen</a>
                <?php endif; ?>
                <!-- ⭐⭐⭐ ENDE ⭐⭐⭐ -->
            </nav>

            <div class="header-actions">
                <a href="Projekt/produkte/warenkorb.html" class="cart-btn">
                    <span class="cart-icon">🛒</span>
                    <span id="cart-count">0</span>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-background"></div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">Authentisch • Vielfältig • Frisch</div>
            <h1 class="hero-title">
                Entdecke die Welt der
                <span class="highlight">afrikanischen</span> &
                <span class="highlight">asiatischen</span> Küche
            </h1>
            <p class="hero-subtitle">
                Von traditionellen Gewürzen bis zu exotischen Spezialitäten –
                Dein Shop für authentische Produkte in Bremerhaven
            </p>
            <div class="hero-buttons">
                <a href="Projekt/produkte/index.html" class="btn btn-primary">
                    Jetzt einkaufen
                </a>
                <a href="#kategorien" class="btn btn-secondary">
                    Kategorien entdecken
                </a>
            </div>

            <!-- Vertrauens-Elemente -->
            <div class="trust-badges">
                <div class="trust-item">
                    <span class="trust-icon">⭐</span>
                    <span>500+ zufriedene Kunden</span>
                </div>
                <div class="trust-item">
                    <span class="trust-icon">🚚</span>
                    <span>Schnelle Lieferung</span>
                </div>
                <div class="trust-item">
                    <span class="trust-icon">✓</span>
                    <span>Authentische Produkte</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Dekorative Elemente -->
    <div class="hero-decoration">
        <div class="floating-card card-1">🌶️ Gewürze</div>
        <div class="floating-card card-2">🍚 Reis</div>
        <div class="floating-card card-3">🥥 Exotisch</div>
    </div>
</section>

<!-- Länder/Herkunft Section -->
<section class="countries-section">
    <div class="container">
        <h2 class="section-subtitle">Produkte aus</h2>
        <div class="countries-grid">
            <div class="country-card" data-country="kamerun">
                <span class="country-flag">🇨🇲</span>
                <span class="country-name">Kamerun</span>
            </div>
            <div class="country-card" data-country="nigeria">
                <span class="country-flag">🇳🇬</span>
                <span class="country-name">Nigeria</span>
            </div>
            <div class="country-card" data-country="ghana">
                <span class="country-flag">🇬🇭</span>
                <span class="country-name">Ghana</span>
            </div>
            <div class="country-card" data-country="elfenbeinkueste">
                <span class="country-flag">🇨🇮</span>
                <span class="country-name">Elfenbeinküste</span>
            </div>
            <div class="country-card" data-country="china">
                <span class="country-flag">🇨🇳</span>
                <span class="country-name">China</span>
            </div>
            <div class="country-card" data-country="thailand">
                <span class="country-flag">🇹🇭</span>
                <span class="country-name">Thailand</span>
            </div>
        </div>
    </div>
</section>

<!-- Kategorien Section -->
<section class="categories" id="kategorien">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Kategorien</h2>
            <p class="section-description">Stöbere durch unser vielfältiges Sortiment</p>
        </div>

        <div class="category-grid">
            <div class="category-card featured">
                <h3 class="category-name">Reis & Getreide</h3>
                <p class="category-desc">Basmati, Jasmin & mehr</p>
                <a href="Projekt/produkte/Kategorien/getreide.html" class="category-link">Entdecken →</a>
            </div>

            <div class="category-card featured">
                <h3 class="category-name">Gewürze</h3>
                <p class="category-desc">Authentische Aromen</p>
                <a href="Projekt/produkte/Kategorien/gewürze.html" class="category-link">Entdecken →</a>
            </div>

            <div class="category-card featured">
                <h3 class="category-name">Getränke</h3>
                <p class="category-desc">Erfrischend & exotisch</p>
                <a href="Projekt/produkte/Kategorien/getränke.html" class="category-link">Entdecken →</a>
            </div>

            <div class="category-card featured">
                <h3 class="category-name">Trockene Lebensmittel</h3>
                <p class="category-desc">Lange haltbar</p>
                <a href="Projekt/produkte/Kategorien/lebensmittel.html" class="category-link">Entdecken →</a>
            </div>

            <div class="category-card featured">
                <h3 class="category-name">Soßen & Öle</h3>
                <p class="category-desc">Verfeinere deine Gerichte</p>
                <a href="Projekt/produkte/Kategorien/öl.html" class="category-link">Entdecken →</a>
            </div>
        </div>
    </div>
</section>

<!-- Warum bei uns Section -->
<section class="features-section">
    <div class="container">
        <h2 class="section-title centered">Warum Jenny Afro & Asia Shop?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🌟</div>
                <h3>Authentische Qualität</h3>
                <p>Direkt importiert von vertrauenswürdigen Partnern</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💚</div>
                <h3>Faire Preise</h3>
                <p>Beste Qualität zu erschwinglichen Preisen</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🚀</div>
                <h3>Schnelle Lieferung</h3>
                <p>In Bremerhaven oft am selben Tag</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <h4>Jenny Afro & Asia Shop</h4>
                <p>Dein Shop für authentische afrikanische und asiatische Lebensmittel in Bremerhaven.</p>
            </div>
            <div class="footer-section">
                <h4>Links</h4>
                <a href="#">Impressum & Datenschutz</a>
                <a href="kontakt.html">Kontakt</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 Jenny Afro & Asia Shop. Alle Rechte vorbehalten.</p>
        </div>
    </div>
</footer>

<script>
// Smooth Scroll für Anchor Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});

// Animation beim Scrollen
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
        }
    });
}, observerOptions);

// Kategorien animieren
document.querySelectorAll('.category-card').forEach(card => {
    observer.observe(card);
});

// Country Cards animieren
document.querySelectorAll('.country-card').forEach(card => {
    observer.observe(card);
});

// Feature Cards animieren
document.querySelectorAll('.feature-card').forEach(card => {
    observer.observe(card);
});
</script>

</body>
</html>
