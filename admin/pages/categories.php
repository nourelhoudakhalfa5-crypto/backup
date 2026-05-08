<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdminLogin();

$pageTitle = 'Catégories';
$currentPage = 'categories';

$conn = getMySQLi();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    redirectTo('categories.php?deleted=1');
}

// Handle status toggle
if (isset($_GET['action']) && $_GET['action'] === 'toggle' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("UPDATE categories SET statut = IF(statut='actif', 'inactif', 'actif') WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    redirectTo('categories.php');
}

// Get all categories
$categories = [];
$result = $conn->query("SELECT * FROM categories ORDER BY nom ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
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
                <a href="categories-form.php" class="btn-outline text-sm">
                    <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                    Nouvelle catégorie
                </a>
            </header>
            
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="admin-card">
                    <div class="p-4 border-b border-white/10">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <input type="text" id="search-input" placeholder="Rechercher une catégorie..." 
                                       class="form-input w-full">
                            </div>
                            <select id="status-filter" class="form-input w-full sm:w-auto">
                                <option value="">Tous les statuts</option>
                                <option value="actif">Actif</option>
                                <option value="inactif">Inactif</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full admin-table">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left">ID</th>
                                    <th class="px-4 py-3 text-left">Image</th>
                                    <th class="px-4 py-3 text-left">Nom</th>
                                    <th class="px-4 py-3 text-left">Description</th>
                                    <th class="px-4 py-3 text-center">Statut</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($categories)): ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-text-secondary">Aucune catégorie trouvée</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($categories as $category): ?>
                                        <tr class="category-row" data-name="<?= strtolower($category['nom']) ?>" data-status="<?= $category['statut'] ?>">
                                            <td class="px-4 py-3"><?= $category['id'] ?></td>
                                            <td class="px-4 py-3">
                                                <?php if ($category['image']): ?>
                                                    <img src="../../assets/uploads/admin/categories/<?= escape($category['image']) ?>" 
                                                         alt="<?= escape($category['nom']) ?>" 
                                                         class="w-12 h-12 object-cover rounded-lg">
                                                <?php else: ?>
                                                    <div class="w-12 h-12 rounded-lg bg-white/10 flex items-center justify-center">
                                                        <i data-lucide="image" class="w-5 h-5 text-white/30"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-3 font-medium"><?= escape($category['nom']) ?></td>
                                            <td class="px-4 py-3 max-w-xs truncate"><?= escape($category['description'] ?? '-') ?></td>
                                            <td class="px-4 py-3 text-center"><?= getStatusBadge($category['statut']) ?></td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="categories-form.php?id=<?= $category['id'] ?>" 
                                                       class="p-2 rounded-lg hover:bg-white/10 transition-colors" title="Modifier">
                                                        <i data-lucide="edit" class="w-4 h-4 text-primary"></i>
                                                    </a>
                                                    <a href="?action=toggle&id=<?= $category['id'] ?>" 
                                                       class="p-2 rounded-lg hover:bg-white/10 transition-colors" 
                                                       title="<?= $category['statut'] === 'actif' ? 'Désactiver' : 'Activer' ?>">
                                                        <i data-lucide="<?= $category['statut'] === 'actif' ? 'eye-off' : 'eye' ?>" class="w-4 h-4 text-yellow-400"></i>
                                                    </a>
                                                    <a href="?action=delete&id=<?= $category['id'] ?>" 
                                                       class="p-2 rounded-lg hover:bg-white/10 transition-colors" 
                                                       title="Supprimer"
                                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
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
        const rows = document.querySelectorAll('.category-row');
        
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
            
            rows.forEach(row => {
                const name = row.dataset.name;
                const status = row.dataset.status;
                
                const matchesSearch = name.includes(searchTerm);
                const matchesStatus = !statusValue || status === statusValue;
                
                row.style.display = matchesSearch && matchesStatus ? '' : 'none';
            });
        }
        
        searchInput.addEventListener('input', filterRows);
        statusFilter.addEventListener('change', filterRows);
    </script>
</body>
</html>