<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdminLogin();

$pageTitle = 'Commandes';
$currentPage = 'orders';

$conn = getMySQLi();

// Handle order status change
if (isset($_GET['action']) && $_GET['action'] === 'update_status' && isset($_GET['id']) && isset($_GET['status'])) {
    $id = (int) $_GET['id'];
    $status = $conn->real_escape_string($_GET['status']);
    $allowed_statuses = ['en_attente', 'confirmee', 'expediee', 'livree', 'annulee'];
    if (in_array($status, $allowed_statuses)) {
        $stmt = $conn->prepare("UPDATE commandes SET statut = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();
        redirectTo('orders.php?updated=1');
    } else {
        redirectTo('orders.php');
    }
}

// Get all orders with user details
$orders = [];
$result = $conn->query("
    SELECT c.*, u.nom, u.prenom 
    FROM commandes c 
    JOIN utilisateurs u ON c.utilisateur_id = u.id 
    ORDER BY c.date_commande DESC
");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
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
    <div class="flex h-screen overflow-hidden">
        <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>
        
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">
            <header class="flex items-center justify-between h-16 px-4 lg:px-6 glass border-b border-white/10">
                <div class="flex items-center gap-4">
                    <button id="mobile-menu-btn" class="lg:hidden text-text-secondary hover:text-primary">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <h1 class="font-orbitron text-xl text-primary"><?= $pageTitle ?></h1>
                </div>
            </header>
            
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="admin-card">
                    <div class="p-4 border-b border-white/10">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <input type="text" id="search-input" placeholder="Rechercher une commande..." 
                                       class="form-input w-full">
                            </div>
                            <select id="status-filter" class="form-input w-full sm:w-auto">
                                <option value="">Tous les statuts</option>
                                <option value="en_attente">En attente</option>
                                <option value="confirmee">Confirmée</option>
                                <option value="expediee">Expédiée</option>
                                <option value="livree">Livrée</option>
                                <option value="annulee">Annulée</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full admin-table">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left">ID</th>
                                    <th class="px-4 py-3 text-left">Client</th>
                                    <th class="px-4 py-3 text-left">Total</th>
                                    <th class="px-4 py-3 text-center">Statut</th>
                                    <th class="px-4 py-3 text-left">Date</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($orders)): ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-text-secondary">Aucune commande trouvée</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($orders as $order): ?>
                                        <tr class="order-row" data-name="<?= strtolower($order['prenom'] . ' ' . $order['nom']) ?>" data-status="<?= $order['statut'] ?>">
                                            <td class="px-4 py-3"><?= $order['id'] ?></td>
                                            <td class="px-4 py-3"><?= escape($order['prenom'] . ' ' . $order['nom']) ?></td>
                                            <td class="px-4 py-3"><?= number_format($order['total'], 2, ',', ' ') ?> DH</td>
                                            <td class="px-4 py-3">
                                                <select class="form-input w-full order-status-select" 
                                                        data-id="<?= $order['id'] ?>"
                                                        onchange="updateOrderStatus(this, <?= $order['id'] ?>)">
                                                    <option value="en_attente" <?= $order['statut'] === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                                    <option value="confirmee" <?= $order['statut'] === 'confirmee' ? 'selected' : '' ?>>Confirmée</option>
                                                    <option value="expediee" <?= $order['statut'] === 'expediee' ? 'selected' : '' ?>>Expédiée</option>
                                                    <option value="livree" <?= $order['statut'] === 'livree' ? 'selected' : '' ?>>Livrée</option>
                                                    <option value="annulee" <?= $order['statut'] === 'annulee' ? 'selected' : '' ?>>Annulée</option>
                                                </select>
                                            </td>
                                            <td class="px-4 py-3"><?= date('d/m/Y H:i', strtotime($order['date_commande'])) ?></td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="orders-view.php?id=<?= $order['id'] ?>" 
                                                       class="p-2 rounded-lg hover:bg-white/10 transition-colors" title="Voir les détails">
                                                        <i data-lucide="eye" class="w-4 h-4 text-primary"></i>
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
        const rows = document.querySelectorAll('.order-row');
        
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
        
        function updateOrderStatus(selectElement, orderId) {
            const status = selectElement.value;
            // In a real app, this would be an AJAX call
            // For now, we'll simulate by changing the URL
            window.location.href = '?action=update_status&id=' + orderId + '&status=' + status;
        }
    </script>
</body>
</html>