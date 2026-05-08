<?php
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

$pageTitle = 'Tableau de bord';
$currentPage = 'dashboard';

$conn = getMySQLi();

// Total Revenue
$res = $conn->query("SELECT COALESCE(SUM(total), 0) as total FROM commandes WHERE statut != 'annulee'");
$totalRevenue = $res ? ($res->fetch_assoc()['total'] ?? 0) : 0;

// Total Orders
$res = $conn->query("SELECT COUNT(*) as total FROM commandes");
$totalOrders = $res ? $res->fetch_assoc()['total'] : 0;

// Total Products
$res = $conn->query("SELECT COUNT(*) as total FROM produits WHERE statut = 'actif'");
$totalProducts = $res ? $res->fetch_assoc()['total'] : 0;

// Total Clients
$res = $conn->query("SELECT COUNT(*) as total FROM utilisateurs WHERE role = 'client'");
$totalClients = $res ? $res->fetch_assoc()['total'] : 0;

// Orders by status
$orderStats = ['en_attente' => 0, 'confirmee' => 0, 'expediee' => 0, 'livree' => 0, 'annulee' => 0];
$res = $conn->query("SELECT statut, COUNT(*) as count FROM commandes GROUP BY statut");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $orderStats[$row['statut']] = $row['count'];
    }
}

// Monthly revenue (last 6 months)
$monthlyRevenue = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-$i months"));
    $res = $conn->query("SELECT COALESCE(SUM(total), 0) as total FROM commandes WHERE DATE_FORMAT(date_commande, '%Y-%m') = '$month' AND statut != 'annulee'");
    $monthlyRevenue[] = [
        'month' => date('M', strtotime($month)),
        'revenue' => $res ? ($res->fetch_assoc()['total'] ?? 0) : 0
    ];
}

// Top products
$topProducts = [];
$res = $conn->query("
    SELECT p.nom, SUM(dc.quantite) as total_vendus
    FROM details_commande dc
    JOIN produits p ON dc.produit_id = p.id
    GROUP BY p.id
    ORDER BY total_vendus DESC
    LIMIT 5
");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $topProducts[] = $row;
    }
}

// Recent Orders
$recentOrders = [];
$res = $conn->query("SELECT c.*, u.nom, u.prenom FROM commandes c JOIN utilisateurs u ON c.utilisateur_id = u.id ORDER BY c.date_commande DESC LIMIT 5");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $recentOrders[] = $row;
    }
}

// Additional stats
$res = $conn->query("SELECT COUNT(*) as total FROM contacts");
$totalMessages = $res ? $res->fetch_assoc()['total'] : 0;

$res = $conn->query("SELECT COUNT(*) as total FROM newsletter");
$totalSubscribers = $res ? $res->fetch_assoc()['total'] : 0;

$res = $conn->query("SELECT AVG(note) as avg FROM avis");
$avgRating = $res ? round($res->fetch_assoc()['avg'] ?? 0, 1) : 0;
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css">
</head>
<body class="bg-bg-dark text-text-primary font-inter min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <?php require_once __DIR__ . '/layout/sidebar.php'; ?>
        
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">
            <header class="flex items-center justify-between h-16 px-4 lg:px-6 glass border-b border-white/10">
                <div class="flex items-center gap-4">
                    <button id="mobile-menu-btn" class="lg:hidden text-text-secondary hover:text-primary">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <h1 class="font-orbitron text-xl text-primary"><?= $pageTitle ?></h1>
                </div>
                <div class="flex items-center gap-4">
                    <a href="pages/profile.php" class="flex items-center gap-2 text-text-secondary hover:text-primary transition-colors">
                        <i data-lucide="user" class="w-5 h-5"></i>
                        <span class="hidden sm:inline"><?= escape(adminName()) ?></span>
                    </a>
                </div>
            </header>
            
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="admin-card p-5 animate-fade-in">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary/30 to-primary/10 flex items-center justify-center">
                                <i data-lucide="banknote" class="w-6 h-6 text-primary"></i>
                            </div>
                            <span class="text-xs text-text-secondary">Chiffre d'affaires</span>
                        </div>
                        <div class="font-orbitron text-2xl font-bold"><?= number_format($totalRevenue, 0, ',', ' ') ?> <span class="text-sm font-normal">DH</span></div>
                        <div class="text-xs text-green-400 mt-1">Total</div>
                    </div>
                    
                    <div class="admin-card p-5 animate-fade-in" style="animation-delay: 0.1s">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/30 to-blue-500/10 flex items-center justify-center">
                                <i data-lucide="shopping-cart" class="w-6 h-6 text-blue-400"></i>
                            </div>
                            <span class="text-xs text-text-secondary">Commandes</span>
                        </div>
                        <div class="font-orbitron text-2xl font-bold"><?= number_format($totalOrders) ?></div>
                        <div class="text-xs text-text-secondary mt-1"><?= $orderStats['en_attente'] ?> en attente</div>
                    </div>
                    
                    <div class="admin-card p-5 animate-fade-in" style="animation-delay: 0.2s">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500/30 to-purple-500/10 flex items-center justify-center">
                                <i data-lucide="package" class="w-6 h-6 text-purple-400"></i>
                            </div>
                            <span class="text-xs text-text-secondary">Produits</span>
                        </div>
                        <div class="font-orbitron text-2xl font-bold"><?= number_format($totalProducts) ?></div>
                        <div class="text-xs text-text-secondary mt-1">Actifs</div>
                    </div>
                    
                    <div class="admin-card p-5 animate-fade-in" style="animation-delay: 0.3s">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500/30 to-green-500/10 flex items-center justify-center">
                                <i data-lucide="users" class="w-6 h-6 text-green-400"></i>
                            </div>
                            <span class="text-xs text-text-secondary">Clients</span>
                        </div>
                        <div class="font-orbitron text-2xl font-bold"><?= number_format($totalClients) ?></div>
                        <div class="text-xs text-text-secondary mt-1">Inscrits</div>
                    </div>
                </div>
                
                <!-- Charts Row -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Revenue Chart -->
                    <div class="admin-card p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-orbitron text-lg text-primary">Revenus mensuels</h3>
                            <span class="text-xs text-text-secondary">6 derniers mois</span>
                        </div>
                        <div class="h-64">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Orders by Status Chart -->
                    <div class="admin-card p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-orbitron text-lg text-primary">Commandes par statut</h3>
                            <span class="text-xs text-text-secondary">Total</span>
                        </div>
                        <div class="h-64 flex items-center justify-center">
                            <canvas id="ordersChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Second Row -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Top Products -->
                    <div class="admin-card p-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-orbitron text-lg text-primary">Produits populaires</h3>
                        </div>
                        <div class="space-y-3">
                            <?php if (empty($topProducts)): ?>
                                <p class="text-text-secondary text-sm">Aucune donnée</p>
                            <?php else: ?>
                                <?php foreach ($topProducts as $index => $product): ?>
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="w-6 h-6 rounded-full bg-primary/20 flex items-center justify-center text-xs font-bold"><?= $index + 1 ?></span>
                                            <span class="text-sm truncate max-w-[150px]"><?= escape($product['nom']) ?></span>
                                        </div>
                                        <span class="text-sm font-bold text-primary"><?= $product['total_vendus'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Quick Stats -->
                    <div class="admin-card p-5">
                        <h3 class="font-orbitron text-lg text-primary mb-4">Aperçu rapide</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="mail" class="w-5 h-5 text-primary"></i>
                                    <span class="text-sm">Messages</span>
                                </div>
                                <span class="font-bold"><?= number_format($totalMessages) ?></span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="newspaper" class="w-5 h-5 text-purple-400"></i>
                                    <span class="text-sm">Abonnés</span>
                                </div>
                                <span class="font-bold"><?= number_format($totalSubscribers) ?></span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="star" class="w-5 h-5 text-yellow-400"></i>
                                    <span class="text-sm">Note moyenne</span>
                                </div>
                                <span class="font-bold"><?= $avgRating ? $avgRating . '/5' : '-' ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Activity -->
                    <div class="admin-card p-5">
                        <h3 class="font-orbitron text-lg text-primary mb-4">Activité récente</h3>
                        <div class="space-y-3">
                            <?php foreach ($recentOrders as $order): ?>
                                <div class="flex items-center gap-3 text-sm">
                                    <div class="w-2 h-2 rounded-full bg-primary"></div>
                                    <span class="text-text-secondary"><?= escape($order['prenom']) ?> <?= escape($order['nom']) ?></span>
                                    <span class="text-primary font-bold"><?= number_format($order['total'], 0) ?> DH</span>
                                </div>
                            <?php endforeach; ?>
                            <?php if (empty($recentOrders)): ?>
                                <p class="text-text-secondary text-sm">Aucune activité</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Orders Table -->
                <div class="admin-card p-5 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="font-orbitron text-lg text-primary">Commandes récentes</h2>
                        <a href="pages/orders.php" class="text-sm text-primary hover:underline">Voir toutes</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full admin-table">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left">ID</th>
                                    <th class="px-4 py-3 text-left">Client</th>
                                    <th class="px-4 py-3 text-left">Total</th>
                                    <th class="px-4 py-3 text-left">Statut</th>
                                    <th class="px-4 py-3 text-left">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($recentOrders)): ?>
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-text-secondary">Aucune commande trouvée</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($recentOrders as $order): ?>
                                        <tr>
                                            <td class="px-4 py-3">#<?= $order['id'] ?></td>
                                            <td class="px-4 py-3"><?= escape($order['prenom'] . ' ' . $order['nom']) ?></td>
                                            <td class="px-4 py-3"><?= number_format($order['total'], 2, ',', ' ') ?> DH</td>
                                            <td class="px-4 py-3"><?= getStatusBadge($order['statut']) ?></td>
                                            <td class="px-4 py-3"><?= date('d/m/Y H:i', strtotime($order['date_commande'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="admin-card p-5">
                    <h2 class="font-orbitron text-lg text-primary mb-4">Actions rapides</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        <a href="pages/products-form.php" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-white/5 hover:bg-primary/10 transition-colors group">
                            <i data-lucide="plus-circle" class="w-8 h-8 text-primary group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm">Nouveau produit</span>
                        </a>
                        <a href="pages/categories-form.php" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-white/5 hover:bg-primary/10 transition-colors group">
                            <i data-lucide="folder-plus" class="w-8 h-8 text-primary group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm">Nouvelle catégorie</span>
                        </a>
                        <a href="pages/blog-form.php" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-white/5 hover:bg-primary/10 transition-colors group">
                            <i data-lucide="file-plus" class="w-8 h-8 text-primary group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm">Nouvel article</span>
                        </a>
                        <a href="pages/subscription-plans-form.php" class="flex flex-col items-center gap-2 p-4 rounded-xl bg-white/5 hover:bg-primary/10 transition-colors group">
                            <i data-lucide="credit-card" class="w-8 h-8 text-primary group-hover:scale-110 transition-transform"></i>
                            <span class="text-sm">Nouveau plan</span>
                        </a>
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
        
        // Chart.js Configuration
        Chart.defaults.color = 'rgba(255, 255, 255, 0.7)';
        Chart.defaults.borderColor = 'rgba(255, 255, 255, 0.1)';
        
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($monthlyRevenue, 'month')) ?>,
                datasets: [{
                    label: 'Revenus (DH)',
                    data: <?= json_encode(array_column($monthlyRevenue, 'revenue')) ?>,
                    borderColor: '#5CC4E5',
                    backgroundColor: 'rgba(92, 196, 229, 0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#5CC4E5',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255, 255, 255, 0.05)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
        
        // Orders Chart
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        new Chart(ordersCtx, {
            type: 'doughnut',
            data: {
                labels: ['En attente', 'Confirmée', 'Expédiée', 'Livrée', 'Annulée'],
                datasets: [{
                    data: [<?= $orderStats['en_attente'] ?>, <?= $orderStats['confirmee'] ?>, <?= $orderStats['expediee'] ?>, <?= $orderStats['livree'] ?>, <?= $orderStats['annulee'] ?>],
                    backgroundColor: [
                        '#FBBF24',
                        '#3B82F6',
                        '#8B5CF6',
                        '#10B981',
                        '#EF4444'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                },
                cutout: '70%'
            }
        });
    </script>
</body>
</html>