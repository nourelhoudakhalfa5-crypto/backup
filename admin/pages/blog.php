<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdminLogin();

$pageTitle = 'Articles Blog';
$currentPage = 'blog';

$conn = getMySQLi();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM blog_posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    redirectTo('blog.php?deleted=1');
}

// Handle publish/unpublish action
if (isset($_GET['action']) && $_GET['action'] === 'publish' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("UPDATE blog_posts SET statut = IF(statut='publie', 'brouillon', 'publie') WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    redirectTo('blog.php');
}

// Get all blog posts with category and author details
$posts = [];
$result = $conn->query("
    SELECT bp.*, bc.nom as categorie_nom, u.nom as auteur_nom, u.prenom as auteur_prenom
    FROM blog_posts bp
    LEFT JOIN blog_categories bc ON bp.categorie_id = bc.id
    LEFT JOIN utilisateurs u ON bp.auteur_id = u.id
    ORDER BY bp.date_creation DESC
");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RDOC Admin - <?= $pageTitle ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#5CC4E5',
                        'bg-dark': '#000000',
                        'bg-card': 'rgba(255, 255, 255, 0.05)',
                        'text-primary': '#FFFFFF',
                        'text-secondary': 'rgba(255, 255, 255, 0.7)',
                    },
                    fontFamily: {
                        orbitron: ['Orbitron', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css">
</head>
<body class="bg-bg-dark text-text-primary font-inter min-h-screen">
    <div class="admin-wrapper">
        <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>
        
        <div class="main-content collapsed">
            <header class="flex items-center justify-between h-16 px-4 lg:px-6 glass border-b border-white/10">
                <div class="flex items-center gap-4">
                    <button id="mobile-menu-btn" class="lg:hidden text-text-secondary hover:text-primary">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <h1 class="font-orbitron text-xl text-primary"><?= $pageTitle ?></h1>
                </div>
                <a href="blog-form.php" class="btn-outline text-sm">
                    <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                    Nouvel article
                </a>
            </header>
            
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="admin-card">
                    <div class="p-4 border-b border-white/10">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <input type="text" id="search-input" placeholder="Rechercher un article..." 
                                       class="form-input w-full">
                            </div>
                            <select id="status-filter" class="form-input w-full sm:w-auto">
                                <option value="">Tous les statuts</option>
                                <option value="publie">Publié</option>
                                <option value="brouillon">Brouillon</option>
                            </select>
                            <select id="category-filter" class="form-input w-full sm:w-auto">
                                <option value="">Toutes les catégories</option>
                                <?php
                                $categories = [];
                                $catResult = $conn->query("SELECT id, nom FROM blog_categories WHERE statut = 'actif' ORDER BY nom");
                                if ($catResult) {
                                    while ($catRow = $catResult->fetch_assoc()) {
                                        $categories[] = $catRow;
                                        echo '<option value="' . $catRow['id'] . '">' . escape($catRow['nom']) . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full admin-table">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left">ID</th>
                                    <th class="px-4 py-3 text-left">Titre</th>
                                    <th class="px-4 py-3 text-left">Catégorie</th>
                                    <th class="px-4 py-3 text-left">Auteur</th>
                                    <th class="px-4 py-3 text-center">Statut</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($posts)): ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-text-secondary">Aucun article trouvé</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($posts as $post): ?>
                                        <tr class="post-row" 
                                            data-title="<?= strtolower($post['titre']) ?>" 
                                            data-category="<?= strtolower($post['categorie_nom'] ?? '') ?>" 
                                            data-author="<?= strtolower($post['auteur_nom'] . ' ' . $post['auteur_prenom'] ?? '') ?>" 
                                            data-status="<?= $post['statut'] ?>">
                                            <td class="px-4 py-3"><?= $post['id'] ?></td>
                                            <td class="px-4 py-3 font-medium max-w-xs truncate"><?= escape($post['titre']) ?></td>
                                            <td class="px-4 py-3"><?= escape($post['categorie_nom'] ?? '-') ?></td>
                                            <td class="px-4 py-3"><?= escape($post['auteur_nom'] . ' ' . $post['auteur_prenom'] ?? '-') ?></td>
                                            <td class="px-4 py-3"><?= getStatusBadge($post['statut']) ?></td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="blog-form.php?id=<?= $post['id'] ?>" 
                                                       class="p-2 rounded-lg hover:bg-white/10 transition-colors" title="Modifier">
                                                        <i data-lucide="edit" class="w-4 h-4 text-primary"></i>
                                                    </a>
                                                    <a href="?action=publish&id=<?= $post['id'] ?>" 
                                                       class="p-2 rounded-lg hover:bg-white/10 transition-colors" 
                                                       title="<?= $post['statut'] === 'publie' ? 'Masquer' : 'Publier' ?>">
                                                        <i data-lucide="<?= $post['statut'] === 'publie' ? 'eye-off' : 'eye' ?>" class="w-4 h-4 text-yellow-400"></i>
                                                    </a>
                                                    <a href="?action=delete&id=<?= $post['id'] ?>" 
                                                       class="p-2 rounded-lg hover:bg-white/10 transition-colors" 
                                                       title="Supprimer"
                                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                                        <i data-lucide="trash-2" class="w-4 h-4 text-red-400"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <div id="sidebar-overlay" class="sidebar-overlay"></div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const searchInput = document.getElementById('search-input');
        const statusFilter = document.getElementById('status-filter');
        const categoryFilter = document.getElementById('category-filter');
        const rows = document.querySelectorAll('.post-row');
        
        if (mobileMenuBtn && sidebar && sidebarOverlay) {
            mobileMenuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
                sidebarOverlay.classList.toggle('active');
            });
            
            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.remove('active');
            });
        }
        
        function filterRows() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;
            const categoryValue = categoryFilter.value;
            
            rows.forEach(row => {
                const title = row.dataset.title;
                const category = row.dataset.category;
                const author = row.dataset.author;
                const status = row.dataset.status;
                
                const matchesSearch = title.includes(searchTerm) || author.includes(searchTerm);
                const matchesStatus = !statusValue || status === statusValue;
                const matchesCategory = !categoryValue || category === categoryValue;
                
                row.style.display = (matchesSearch && matchesStatus && matchesCategory) ? '' : 'none';
            });
        }
        
        searchInput.addEventListener('input', filterRows);
        statusFilter.addEventListener('change', filterRows);
        categoryFilter.addEventListener('change', filterRows);
    </script>
</body>
</html>