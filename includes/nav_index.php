<!-- ====== HOMEPAGE NAVIGATION (index.php) ====== -->
<div class="menu">
  <svg class="ham hamRotate ham1" viewBox="0 0 100 100" width="80" onclick="this.classList.toggle('active')">
  <path class="line top" d="m 30,33 h 40 c 0,0 9.044436,-0.654587 9.044436,-8.508902 0,-7.854315 -8.024349,-11.958003 -14.89975,-10.85914 -6.875401,1.098863 -13.637059,4.171617 -13.637059,16.368042 v 40" />
  <path class="line middle" d="m 30,50 h 40" />
  <path class="line bottom" d="m 30,67 h 40 c 12.796276,0 15.357889,-11.717785 15.357889,-26.851538 0,-15.133752 -4.786586,-27.274118 -16.667516,-27.274118 -11.88093,0 -18.499247,6.994427 -18.435284,17.125656 l 0.252538,40" />
</svg>
</div>




   <nav class="fixed top-2 left-1/2 -translate-x-1/2 z-50 w-[30%] max-w-3xl" id="navbar">

    <div class="bg-white rounded-2xl px-7 py-2 flex items-center gap-3 transition-all duration-300" id="navbar-inner">
        <a href="#" class="flex items-center">
            <div class="favicon-logo">
                <img src="assets/favicon.png" alt="Favicon">
            </div>
        </a>

        <button id="menu-toggle" class="ml-auto flex flex-col justify-center items-center w-8 h-8 gap-1.5 cursor-pointer p-1">
            <span id="line1" class="block w-6 h-0.5 bg-black transition-all duration-300"></span>
            <span id="line2" class="block w-6 h-0.5 bg-black transition-all duration-300"></span>
        </button>

      
    </div>

<!-- FIXED DROPDOWN -->
    <div id="dropdown" class="absolute top-full left-0 mt-2 bg-white rounded-2xl p-6 hidden w-full">

<?php $current_page = basename($_SERVER['PHP_SELF']); ?>
        <div class="grid grid-cols-3 gap-4 mb-6">
            <a href="index.php" class="menu-item text-center text-sm <?php echo ($current_page == 'index.php') ? 'text-[#5CC4E5] font-bold' : 'text-black'; ?>">Accueil</a>
            <a href="apropos.php" class="menu-item text-center text-sm <?php echo ($current_page == 'apropos.php') ? 'text-[#5CC4E5] font-bold' : 'text-black'; ?>">A propos</a>
            <a href="categorie.php" class="menu-item text-center text-sm <?php echo ($current_page == 'categorie.php') ? 'text-[#5CC4E5] font-bold' : 'text-black'; ?>">Categories</a>
            <a href="produit.php" class="menu-item text-center text-sm <?php echo ($current_page == 'produit.php') ? 'text-[#5CC4E5] font-bold' : 'text-black'; ?>">Produits</a>
            <a href="blog.php" class="menu-item text-center text-sm <?php echo ($current_page == 'blog.php') ? 'text-[#5CC4E5] font-bold' : 'text-black'; ?>">Blog</a>
            <a href="contact.php" class="menu-item text-center text-sm <?php echo ($current_page == 'contact.php') ? 'text-[#5CC4E5] font-bold' : 'text-black'; ?>">Contact</a>
        </div>

        <div class="flex justify-center">
            <a href="register.php" id="open-auth-btn" class="bg-[#5CC4E5] text-black font-bold px-8 py-3 rounded-full text-sm transition-transform duration-200 hover:scale-105 text-center">
                S'inscrire
            </a>
        </div>

    </div>

</nav>
