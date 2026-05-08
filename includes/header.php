<?php
require_once 'config/session.php';
?>
<nav class="fixed top-2 left-1/2 -translate-x-1/2 z-50 w-[30%] max-w-3xl" id="navbar">
    <div class="bg-white rounded-2xl px-7 py-2 flex items-center gap-3 transition-all duration-300" id="navbar-inner">
        <a href="index.php" class="flex items-center">
            <div class="favicon-logo">
                <img src="assets/favicon.png" alt="Favicon">
            </div>
        </a>

        <button id="menu-toggle"
            class="ml-auto flex flex-col justify-center items-center w-8 h-8 gap-1.5 cursor-pointer p-1">
            <span id="line1" class="block w-6 h-0.5 bg-black transition-all duration-300"></span>
            <span id="line2" class="block w-6 h-0.5 bg-black transition-all duration-300"></span>
        </button>
    </div>

    <!-- FIXED DROPDOWN -->
    <div id="dropdown" class="absolute top-full left-0 mt-2 bg-white rounded-2xl p-6 hidden w-full">
        <div class="grid grid-cols-3 gap-4 mb-6">
            <a href="index.php" class="menu-item text-black text-center text-sm">Accueil</a>
            <a href="apropos.php" class="menu-item text-black text-center text-sm">A propos</a>
            <a href="categorie.php" class="menu-item text-black text-center text-sm">Categories</a>
            <a href="produit.php" class="menu-item text-black text-center text-sm">Produits</a>
            <a href="blog.php" class="menu-item text-black text-center text-sm">Blog</a>
            <a href="contact.php" class="menu-item text-black text-center text-sm">Contact</a>

            <?php if (isLoggedIn()): ?>
                <a href="profil.php" class="menu-item text-black text-center text-sm">Mon profil</a>
                <a href="panier.php" class="menu-item text-black text-center text-sm">Panier</a>
                <a href="logout.php" class="menu-item text-black text-center text-sm text-red-500">Déconnexion</a>
                <?php if (isAdmin()): ?>
                    <a href="dashboard.php" class="menu-item text-black text-center text-sm font-bold">Administration</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="login.ph p" class="menu-item text-black text-center text-sm">Connexion</a>
                <a href="register.php" class="menu-item text-black text-center text-sm">Inscription</a>
            <?php endif; ?>
        </div>

        <?php if (!isLoggedIn()): ?>
            <div class="flex justify-center">
                <a href="register.php" id="open-auth-btn"
                    class="bg-[#5CC4E5] text-black font-bold px-8 py-3 rounded-full text-sm transition-transform duration-200 hover:scale-105 text-center">
                    S'inscrire
                </a>
            </div>
        <?php endif; ?>
    </div>
</nav>