<?php
// Determine base path for sidebar links based on current file location
$scriptPath = $_SERVER['SCRIPT_NAME'];
$basePath = (strpos($scriptPath, '/admin/pages/') !== false) ? '../' : '';
?>
<aside id="sidebar" class="collapsed">
    <!-- Close Button (Mobile) -->
    <button class="mobile-close-btn" onclick="closeSidebar()" aria-label="Fermer le menu">
        <i data-lucide="x" class="w-5 h-5"></i>
    </button>
    
    <!-- Collapse Toggle Button -->
    <button class="sidebar-collapse-btn" onclick="toggleSidebar()" aria-label="Basculer la barre latérale">
        <i data-lucide="chevron-left" class="w-4 h-4"></i>
    </button>
    
    <!-- Logo Section -->
    <div class="sidebar-logo">
        <img src="../assets/favicon.png" alt="RDOC" class="rounded-xl">
        <span class="sidebar-logo-text">RDOC</span>
    </div>
    
    <!-- Navigation -->
    <div class="sidebar-nav-wrapper">
    <nav class="sidebar-nav">
        <div class="sidebar-section">
            <a href="<?= $basePath ?>index.php" class="sidebar-nav-item <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span>Tableau de bord</span>
            </a>
        </div>
        
        <!-- E-Commerce -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">E-Commerce</div>
            <a href="<?= $basePath ?>pages/categories.php" class="sidebar-nav-item <?= $currentPage === 'categories' ? 'active' : '' ?>">
                <i data-lucide="layers" class="w-5 h-5"></i>
                <span>Catégories</span>
            </a>
            <a href="<?= $basePath ?>pages/products.php" class="sidebar-nav-item <?= $currentPage === 'products' ? 'active' : '' ?>">
                <i data-lucide="package" class="w-5 h-5"></i>
                <span>Produits</span>
            </a>
            <a href="<?= $basePath ?>pages/orders.php" class="sidebar-nav-item <?= $currentPage === 'orders' ? 'active' : '' ?>">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                <span>Commandes</span>
            </a>
            <a href="<?= $basePath ?>pages/reviews.php" class="sidebar-nav-item <?= $currentPage === 'reviews' ? 'active' : '' ?>">
                <i data-lucide="star" class="w-5 h-5"></i>
                <span>Avis Clients</span>
            </a>
        </div>
        
        <!-- Utilisateurs -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Utilisateurs</div>
            <a href="<?= $basePath ?>pages/users.php" class="sidebar-nav-item <?= $currentPage === 'users' ? 'active' : '' ?>">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span>Clients</span>
            </a>
            <a href="<?= $basePath ?>pages/profile.php" class="sidebar-nav-item <?= $currentPage === 'profile' ? 'active' : '' ?>">
                <i data-lucide="user-cog" class="w-5 h-5"></i>
                <span>Mon Profil</span>
            </a>
        </div>
        
        <!-- Contenu -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Contenu</div>
            <a href="<?= $basePath ?>pages/blog-categories.php" class="sidebar-nav-item <?= $currentPage === 'blog-categories' ? 'active' : '' ?>">
                <i data-lucide="folder" class="w-5 h-5"></i>
                <span>Catégories Blog</span>
            </a>
            <a href="<?= $basePath ?>pages/blog.php" class="sidebar-nav-item <?= $currentPage === 'blog' ? 'active' : '' ?>">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                <span>Articles Blog</span>
            </a>
        </div>
        
        <!-- Abonnements -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Abonnements</div>
            <a href="<?= $basePath ?>pages/subscription-plans.php" class="sidebar-nav-item <?= $currentPage === 'subscription-plans' ? 'active' : '' ?>">
                <i data-lucide="credit-card" class="w-5 h-5"></i>
                <span>Plans d'abonnement</span>
            </a>
        </div>
        
        <!-- Communications -->
        <div class="sidebar-section">
            <div class="sidebar-section-title">Communications</div>
            <a href="<?= $basePath ?>pages/contacts.php" class="sidebar-nav-item <?= $currentPage === 'contacts' ? 'active' : '' ?>">
                <i data-lucide="mail" class="w-5 h-5"></i>
                <span>Messages</span>
            </a>
            <a href="<?= $basePath ?>pages/newsletter.php" class="sidebar-nav-item <?= $currentPage === 'newsletter' ? 'active' : '' ?>">
                <i data-lucide="newspaper" class="w-5 h-5"></i>
                <span>Newsletter</span>
            </a>
        </div>
    </nav>
    </div>
    
    <!-- User Section -->
    <div class="sidebar-user">
        <div class="sidebar-user-avatar">
            <i data-lucide="user" class="w-5 h-5 text-primary"></i>
        </div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name"><?= escape(adminName()) ?></div>
            <div class="sidebar-user-role">Administrateur</div>
        </div>
        <a href="<?= $basePath ?>logout.php" class="sidebar-logout-icon" onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?');" title="Déconnexion">
            <i data-lucide="log-out" class="w-5 h-5"></i>
        </a>
    </div>
</aside>

<!-- Sidebar Overlay -->
<div id="sidebar-overlay" class="sidebar-overlay" onclick="closeSidebar()"></div>

<script>
// Sidebar Toggle Functions
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    
    sidebar.classList.toggle('collapsed');
    if (mainContent) {
        mainContent.classList.toggle('collapsed');
    }
    
    // Save state to localStorage
    const isCollapsed = sidebar.classList.contains('collapsed');
    localStorage.setItem('sidebarCollapsed', isCollapsed);
}

function openSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.classList.add('active');
    sidebar.classList.remove('collapsed');
    if (overlay) overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    sidebar.classList.remove('active');
    if (overlay) overlay.classList.remove('active');
    document.body.style.overflow = '';
}

// Initialize sidebar state on page load
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');
    
    // Check if we should restore collapsed state
    const savedState = localStorage.getItem('sidebarCollapsed');
    if (savedState === 'true' && window.innerWidth >= 1024) {
        sidebar.classList.add('collapsed');
        if (mainContent) mainContent.classList.add('collapsed');
    }
});
</script>