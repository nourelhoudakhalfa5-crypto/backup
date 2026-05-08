<?php
// Determine base path for sidebar links based on current file location
$scriptPath = $_SERVER['SCRIPT_NAME'];
$basePath = (strpos($scriptPath, '/admin/pages/') !== false) ? '../' : '';
?>
<aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-black/40 backdrop-blur-[29px] border-r border-white/10 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="flex flex-col h-full">
                <div class="flex items-center justify-center h-16 border-b border-white/10">
                    <a href="<?= $basePath ?>index.php" class="flex items-center gap-2">
                        <img src="../assets/favicon.png" alt="Logo" class="h-8 w-auto">
                        <span class="font-orbitron text-primary text-xl font-bold">RDOC</span>
                    </a>
                </div>
                
                <nav class="flex-1 overflow-y-auto py-4 px-3">
                    <div class="space-y-1">
                        <div class="px-3 py-2 text-xs font-semibold text-text-secondary uppercase tracking-wider">
                            Tableau de bord
                        </div>
                        <a href="<?= $basePath ?>index.php" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-primary hover:bg-white/5 transition-colors <?= $currentPage === 'dashboard' ? 'bg-primary/20 text-primary' : '' ?>">
                            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </div>
                    
                    <div class="mt-6 space-y-1">
                        <div class="px-3 py-2 text-xs font-semibold text-text-secondary uppercase tracking-wider">
                            E-Commerce
                        </div>
                        <a href="<?= $basePath ?>pages/categories.php" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-primary hover:bg-white/5 transition-colors <?= $currentPage === 'categories' ? 'bg-primary/20 text-primary' : '' ?>">
                            <i data-lucide="layers" class="w-5 h-5"></i>
                            <span>Catégories</span>
                        </a>
                        <a href="<?= $basePath ?>pages/products.php" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-primary hover:bg-white/5 transition-colors <?= $currentPage === 'products' ? 'bg-primary/20 text-primary' : '' ?>">
                            <i data-lucide="package" class="w-5 h-5"></i>
                            <span>Produits</span>
                        </a>
                        <a href="<?= $basePath ?>pages/orders.php" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-primary hover:bg-white/5 transition-colors <?= $currentPage === 'orders' ? 'bg-primary/20 text-primary' : '' ?>">
                            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                            <span>Commandes</span>
                        </a>
                        <a href="<?= $basePath ?>pages/reviews.php" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-primary hover:bg-white/5 transition-colors <?= $currentPage === 'reviews' ? 'bg-primary/20 text-primary' : '' ?>">
                            <i data-lucide="star" class="w-5 h-5"></i>
                            <span>Avis Clients</span>
                        </a>
                    </div>
                    
                    <div class="mt-6 space-y-1">
                        <div class="px-3 py-2 text-xs font-semibold text-text-secondary uppercase tracking-wider">
                            Utilisateurs
                        </div>
                        <a href="<?= $basePath ?>pages/users.php" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-primary hover:bg-white/5 transition-colors <?= $currentPage === 'users' ? 'bg-primary/20 text-primary' : '' ?>">
                            <i data-lucide="users" class="w-5 h-5"></i>
                            <span>Clients</span>
                        </a>
                        <a href="<?= $basePath ?>pages/profile.php" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-primary hover:bg-white/5 transition-colors <?= $currentPage === 'profile' ? 'bg-primary/20 text-primary' : '' ?>">
                            <i data-lucide="user-cog" class="w-5 h-5"></i>
                            <span>Mon Profil</span>
                        </a>
                    </div>
                    
                    <div class="mt-6 space-y-1">
                        <div class="px-3 py-2 text-xs font-semibold text-text-secondary uppercase tracking-wider">
                            Contenu
                        </div>
                        <a href="<?= $basePath ?>pages/blog-categories.php" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-primary hover:bg-white/5 transition-colors <?= $currentPage === 'blog-categories' ? 'bg-primary/20 text-primary' : '' ?>">
                            <i data-lucide="folder" class="w-5 h-5"></i>
                            <span>Catégories Blog</span>
                        </a>
                        <a href="<?= $basePath ?>pages/blog.php" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-primary hover:bg-white/5 transition-colors <?= $currentPage === 'blog' ? 'bg-primary/20 text-primary' : '' ?>">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                            <span>Articles Blog</span>
                        </a>
                    </div>
                    
                    <div class="mt-6 space-y-1">
                        <div class="px-3 py-2 text-xs font-semibold text-text-secondary uppercase tracking-wider">
                            Abonnements
                        </div>
                        <a href="<?= $basePath ?>pages/subscription-plans.php" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-primary hover:bg-white/5 transition-colors <?= $currentPage === 'subscription-plans' ? 'bg-primary/20 text-primary' : '' ?>">
                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                            <span>Plans d'abonnement</span>
                        </a>
                    </div>
                    
                    <div class="mt-6 space-y-1">
                        <div class="px-3 py-2 text-xs font-semibold text-text-secondary uppercase tracking-wider">
                            Communications
                        </div>
                        <a href="<?= $basePath ?>pages/contacts.php" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-primary hover:bg-white/5 transition-colors <?= $currentPage === 'contacts' ? 'bg-primary/20 text-primary' : '' ?>">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                            <span>Messages</span>
                        </a>
                        <a href="<?= $basePath ?>pages/newsletter.php" class="flex items-center gap-3 px-3 py-2 rounded-lg text-text-primary hover:bg-white/5 transition-colors <?= $currentPage === 'newsletter' ? 'bg-primary/20 text-primary' : '' ?>">
                            <i data-lucide="newspaper" class="w-5 h-5"></i>
                            <span>Newsletter</span>
                        </a>
                    </div>
                </nav>
                
                <div class="border-t border-white/10 p-4">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center">
                            <i data-lucide="user" class="w-5 h-5 text-primary"></i>
                        </div>
                        <div>
                            <div class="font-medium text-sm"><?= escape(adminName()) ?></div>
                            <div class="text-xs text-text-secondary">Administrateur</div>
                        </div>
                    </div>
                    <a href="<?= $basePath ?>logout.php" class="flex items-center gap-2 px-3 py-2 rounded-lg text-red-400 hover:bg-red-500/10 transition-colors text-sm">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span>Déconnexion</span>
                    </a>
                </div>
            </div>
        </aside>