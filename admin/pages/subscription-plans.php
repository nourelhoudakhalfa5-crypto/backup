<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdminLogin();

$pageTitle = 'Plans d\'abonnement';
$currentPage = 'subscription-plans';

$conn = getMySQLi();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM subscription_plans WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    redirectTo('subscription-plans.php?deleted=1');
}

// Handle status toggle
if (isset($_GET['action']) && $_GET['action'] === 'toggle' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("UPDATE subscription_plans SET statut = IF(statut='actif', 'inactif', 'actif') WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    redirectTo('subscription-plans.php');
}

// Get all subscription plans
$plans = [];
$result = $conn->query("SELECT * FROM subscription_plans ORDER BY prix ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $plans[] = $row;
    }
}

// Get subscription stats
$stats = [
    'total' => 0,
    'active' => 0,
    'expired' => 0
];
$statsResult = $conn->query("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN statut = 'actif' THEN 1 ELSE 0 END) as active,
        SUM(CASE WHEN statut = 'expire' THEN 1 ELSE 0 END) as expired
    FROM client_subscriptions
");
if ($statsResult) {
    $stats = array_merge($stats, $statsResult->fetch_assoc());
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
                        'primary-dark': '#4AA9C9',
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
                <a href="subscription-plans-form.php" class="btn-outline text-sm">
                    <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                    Nouveau plan
                </a>
            </header>
            
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div class="admin-card p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl bg-primary/20 flex items-center justify-center">
                                <i data-lucide="credit-card" class="w-6 h-6 text-primary"></i>
                            </div>
                        </div>
                        <div class="font-orbitron text-2xl font-bold"><?= number_format($stats['total'] ?? 0) ?></div>
                        <div class="text-sm text-text-secondary">Total Abonnements</div>
                    </div>
                    
                    <div class="admin-card p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                                <i data-lucide="check-circle" class="w-6 h-6 text-green-400"></i>
                            </div>
                        </div>
                        <div class="font-orbitron text-2xl font-bold"><?= number_format($stats['active'] ?? 0) ?></div>
                        <div class="text-sm text-text-secondary">Abonnements Actifs</div>
                    </div>
                    
                    <div class="admin-card p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                                <i data-lucide="clock" class="w-6 h-6 text-yellow-400"></i>
                            </div>
                        </div>
                        <div class="font-orbitron text-2xl font-bold"><?= number_format($stats['expired'] ?? 0) ?></div>
                        <div class="text-sm text-text-secondary">Abonnements Expirés</div>
                    </div>
                </div>
                
                <!-- Plans List -->
                <div class="admin-card">
                    <div class="p-4 border-b border-white/10">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <input type="text" id="search-input" placeholder="Rechercher un plan..." 
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
                                    <th class="px-4 py-3 text-left">Nom</th>
                                    <th class="px-4 py-3 text-left">Description</th>
                                    <th class="px-4 py-3 text-right">Prix</th>
                                    <th class="px-4 py-3 text-center">Durée</th>
                                    <th class="px-4 py-3 text-center">Produits</th>
                                    <th class="px-4 py-3 text-center">Statut</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($plans)): ?>
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-text-secondary">Aucun plan trouvé</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($plans as $plan): ?>
                                        <tr class="plan-row" data-name="<?= strtolower($plan['nom']) ?>" data-status="<?= $plan['statut'] ?>">
                                            <td class="px-4 py-3"><?= $plan['id'] ?></td>
                                            <td class="px-4 py-3 font-medium"><?= escape($plan['nom']) ?></td>
                                            <td class="px-4 py-3 max-w-xs truncate"><?= escape($plan['description'] ?? '-') ?></td>
                                            <td class="px-4 py-3 text-right font-bold text-primary"><?= number_format($plan['prix'], 2, ',', ' ') ?> TND</td>
                                            <td class="px-4 py-3 text-center"><?= $plan['duree'] === 'mois' ? 'Mois' : 'Année' ?></td>
                                            <td class="px-4 py-3 text-center"><?= $plan['nombre_produits'] ?: 'Illimité' ?></td>
                                            <td class="px-4 py-3 text-center"><?= getStatusBadge($plan['statut']) ?></td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="subscription-plans-form.php?id=<?= $plan['id'] ?>" 
                                                       class="p-2 rounded-lg hover:bg-white/10 transition-colors" title="Modifier">
                                                        <i data-lucide="edit" class="w-4 h-4 text-primary"></i>
                                                    </a>
                                                    <a href="?action=toggle&id=<?= $plan['id'] ?>" 
                                                       class="p-2 rounded-lg hover:bg-white/10 transition-colors" 
                                                       title="<?= $plan['statut'] === 'actif' ? 'Désactiver' : 'Activer' ?>">
                                                        <i data-lucide="<?= $plan['statut'] === 'actif' ? 'eye-off' : 'eye' ?>" class="w-4 h-4 text-yellow-400"></i>
                                                    </a>
                                                    <a href="?action=delete&id=<?= $plan['id'] ?>" 
                                                       class="p-2 rounded-lg hover:bg-white/10 transition-colors" 
                                                       title="Supprimer"
                                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce plan ?')">
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
        const rows = document.querySelectorAll('.plan-row');
        
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