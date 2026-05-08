<!-- ====== MOBILE BOTTOM NAVIGATION ====== -->
<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<div
    class="fixed bottom-0 left-0 right-0 lg:hidden z-50 bg-black/95 backdrop-blur-md border-t border-[rgba(255,255,255,0.1)] px-4 py-3"
>
    <div class="flex justify-around items-center text-white">
        <a
            href="index.php"
            class="flex flex-col items-center text-xs <?php echo ($current_page == 'index.php') ? 'text-primary' : 'text-white/70'; ?>"
        >
            <svg
                class="w-6 h-6 mb-1"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <title>Accueil</title>
                <path
                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                />
            </svg>
            Accueil
        </a>
        <a
            href="apropos.php"
            class="flex flex-col items-center text-xs <?php echo ($current_page == 'apropos.php') ? 'text-primary' : 'text-white/70'; ?>"
        >
            <svg
                class="w-6 h-6 mb-1"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <title>À propos</title>
                <path
                    fill-rule="evenodd"
                    d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                    clip-rule="evenodd"
                />
            </svg>
            A propos
        </a>
        <a
            href="categorie.php"
            class="flex flex-col items-center text-xs <?php echo ($current_page == 'categorie.php') ? 'text-primary' : 'text-white/70'; ?>"
        >
            <svg
                class="w-6 h-6 mb-1"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <title>Catégorie</title>
                <path
                    d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"
                />
            </svg>
            Catégorie
        </a>
        <a
            href="produit.php"
            class="flex flex-col items-center text-xs <?php echo ($current_page == 'produit.php' || strpos($current_page, 'produit-') === 0) ? 'text-primary' : 'text-white/70'; ?>"
        >
            <svg
                class="w-6 h-6 mb-1"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            Produit
        </a>
        <a
            href="blog.php"
            class="flex flex-col items-center text-xs <?php echo ($current_page == 'blog.php') ? 'text-primary' : 'text-white/70'; ?>"
        >
            <svg
                class="w-6 h-6 mb-1"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <title>Blog</title>
                <path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                <path d="M7 7h6M7 10h6M7 13h4"/>
            </svg>
            Blog
        </a>
        <a
            href="contact.php"
            class="flex flex-col items-center text-xs <?php echo ($current_page == 'contact.php') ? 'text-primary' : 'text-white/70'; ?>"
        >
            <svg
                class="w-6 h-6 mb-1"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <title>Contact</title>
                <path
                    d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"
                />
                <path
                    d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"
                />
            </svg>
            Contact
        </a>
    </div>
</div>
