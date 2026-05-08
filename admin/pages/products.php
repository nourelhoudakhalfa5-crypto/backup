<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdminLogin();

$pageTitle = 'Produits';
$currentPage = 'products';

$conn = getMySQLi();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM produits WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    redirectTo('products.php?deleted=1');
}

// Handle status toggle
if (isset($_GET['action']) && $_GET['action'] === 'toggle' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("UPDATE produits SET statut = IF(statut='actif', 'inactif', 'actif') WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    redirectTo('products.php');
}

// Handle bulk delete
if (isset($_POST['bulk_delete']) && !empty($_POST['selected_ids'])) {
    $ids = array_map('intval', $_POST['selected_ids']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conn->prepare("DELETE FROM produits WHERE id IN ($placeholders)");
    $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
    $stmt->execute();
    redirectTo('products.php?deleted=1');
}

// Handle bulk status change
if (isset($_POST['bulk_status']) && !empty($_POST['selected_ids'])) {
    $status = $_POST['bulk_status'] === 'actif' ? 'actif' : 'inactif';
    $ids = array_map('intval', $_POST['selected_ids']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conn->prepare("UPDATE produits SET statut = ? WHERE id IN ($placeholders)");
    $stmt->bind_param("s" . str_repeat('i', count($ids)), $status, ...$ids);
    $stmt->execute();
    redirectTo('products.php?updated=1');
}

// Get all products with category names
$products = [];
$result = $conn->query("
    SELECT p.*, c.nom as categorie_nom 
    FROM produits p 
    LEFT JOIN categories c ON p.categorie_id = c.id 
    ORDER BY p.nom ASC
");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
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
                <div class="flex items-center gap-3">
                    <button type="button" onclick="openSidebar()" class="mobile-menu-btn-header" aria-label="Menu">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </button>
                    <h1 class="font-orbitron text-lg lg:text-xl text-primary"><?= $pageTitle ?></h1>
                </div>
                <div class="flex items-center gap-2">
                    <form method="POST" class="flex items-center gap-2" id="bulk-form">
                        <input type="hidden" name="bulk_delete" value="1">
                        <input type="hidden" name="bulk_status" value="">
                        <button type="button" onclick="bulkAction('delete')" class="btn-outline text-sm opacity-50 cursor-not-allowed" id="bulk-delete-btn" disabled>
                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                            Supprimer
                        </button>
                        <button type="button" onclick="bulkAction('actif')" class="btn-outline text-sm opacity-50 cursor-not-allowed" id="bulk-activate-btn" disabled>
                            <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i>
                            Activer
                        </button>
                        <button type="button" onclick="bulkAction('inactif')" class="btn-outline text-sm opacity-50 cursor-not-allowed" id="bulk-deactivate-btn" disabled>
                            <i data-lucide="eye-off" class="w-4 h-4 mr-1"></i>
                            Désactiver
                        </button>
                    </form>
                    <a href="products-form.php" class="btn-outline text-sm">
                        <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                        Nouveau produit
                    </a>
                </div>
            </header>
            
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="admin-card">
                    <div class="p-4 border-b border-white/10">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <input type="text" id="search-input" placeholder="Rechercher un produit..." 
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
                        <form method="POST" id="products-form">
                        <div class="overflow-x-auto">
                            <table class="w-full admin-table">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left w-10">
                                            <input type="checkbox" id="select-all" class="rounded bg-white/10 border-white/20">
                                        </th>
                                        <th class="px-4 py-3 text-left">ID</th>
                                        <th class="px-4 py-3 text-left">Image</th>
                                    <th class="px-4 py-3 text-left">Nom</th>
                                    <th class="px-4 py-3 text-left">Catégorie</th>
                                    <th class="px-4 py-3 text-center">Stock</th>
                                    <th class="px-4 py-3 text-right">Prix</th>
                                    <th class="px-4 py-3 text-center">Statut</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($products)): ?>
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-text-secondary">Aucun produit trouvé</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($products as $product): ?>
                                        <tr class="product-row" data-name="<?= strtolower($product['nom']) ?>" data-status="<?= $product['statut'] ?>">
                                            <td class="px-4 py-3">
                                                <input type="checkbox" name="selected_ids[]" value="<?= $product['id'] ?>" class="product-checkbox rounded bg-white/10 border-white/20">
                                            </td>
                                            <td class="px-4 py-3"><?= $product['id'] ?></td>
                                            <td class="px-4 py-3">
                                                <?php if ($product['image_url']): ?>
                                                    <img src="../../assets/uploads/admin/products/<?= escape($product['image_url']) ?>" 
                                                         alt="<?= escape($product['nom']) ?>" 
                                                         class="w-12 h-12 object-cover rounded-lg">
                                                <?php else: ?>
                                                    <div class="w-12 h-12 rounded-lg bg-white/10 flex items-center justify-center">
                                                        <i data-lucide="package" class="w-5 h-5 text-white/30"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-4 py-3 font-medium"><?= escape($product['nom']) ?></td>
                                            <td class="px-4 py-3"><?= escape($product['categorie_nom'] ?? '-') ?></td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="<?= $product['stock'] <= 0 ? 'text-red-400' : ($product['stock'] < 10 ? 'text-yellow-400' : 'text-green-400') ?>">
                                                    <?= $product['stock'] ?>
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right"><?= number_format($product['prix'], 2, ',', ' ') ?> TND</td>
                                            <td class="px-4 py-3 text-center"><?= getStatusBadge($product['statut']) ?></td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="products-form.php?id=<?= $product['id'] ?>" 
                                                       class="p-2 rounded-lg hover:bg-white/10 transition-colors" title="Modifier">
                                                        <i data-lucide="edit" class="w-4 h-4 text-primary"></i>
                                                    </a>
                                                    <a href="?action=toggle&id=<?= $product['id'] ?>" 
                                                       class="p-2 rounded-lg hover:bg-white/10 transition-colors" 
                                                       title="<?= $product['statut'] === 'actif' ? 'Désactiver' : 'Activer' ?>">
                                                        <i data-lucide="<?= $product['statut'] === 'actif' ? 'eye-off' : 'eye' ?>" class="w-4 h-4 text-yellow-400"></i>
                                                    </a>
                                                    <a href="?action=delete&id=<?= $product['id'] ?>" 
                                                       class="p-2 rounded-lg hover:bg-white/10 transition-colors" 
                                                       title="Supprimer"
                                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
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
                        </form>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        const searchInput = document.getElementById('search-input');
        const statusFilter = document.getElementById('status-filter');
        const rows = document.querySelectorAll('.product-row');
        
        // Bulk selection
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const deleteBtn = document.getElementById('bulk-delete-btn');
        const activateBtn = document.getElementById('bulk-activate-btn');
        const deactivateBtn = document.getElementById('bulk-deactivate-btn');
        
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
        
        // Bulk actions
        selectAll.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateBulkButtons();
        });
        
        checkboxes.forEach(cb => cb.addEventListener('change', updateBulkButtons));
        
        function updateBulkButtons() {
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            deleteBtn.disabled = !anyChecked;
            deleteBtn.classList.toggle('opacity-50', !anyChecked);
            deleteBtn.classList.toggle('cursor-not-allowed', !anyChecked);
            activateBtn.disabled = !anyChecked;
            activateBtn.classList.toggle('opacity-50', !anyChecked);
            activateBtn.classList.toggle('cursor-not-allowed', !anyChecked);
            deactivateBtn.disabled = !anyChecked;
            deactivateBtn.classList.toggle('opacity-50', !anyChecked);
            deactivateBtn.classList.toggle('cursor-not-allowed', !anyChecked);
        }
        
        function bulkAction(action) {
            const form = document.getElementById('bulk-form');
            if (action === 'delete') {
                if (confirm('Êtes-vous sûr de vouloir supprimer les produits sélectionnés?')) {
                    form.submit();
                }
            } else {
                form.querySelector('input[name="bulk_status"]').value = action;
                form.submit();
            }
        }
    </script>
</body>
</html>