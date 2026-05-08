<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdminLogin();

$pageTitle = 'Détails de la commande';
$currentPage = 'orders';

$conn = getMySQLi();

$orderId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (!$orderId) {
    redirectTo('orders.php');
}

// Get order details
$orderStmt = $conn->prepare("
    SELECT c.*, u.nom, u.prenom, u.email, u.telephone, u.adresse
    FROM commandes c
    JOIN utilisateurs u ON c.utilisateur_id = u.id
    WHERE c.id = ?
");
$orderStmt->bind_param("i", $orderId);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();
$order = $orderResult->fetch_assoc();

if (!$order) {
    redirectTo('orders.php');
}

// Get order items
$itemsStmt = $conn->prepare("
    SELECT dc.*, p.nom as produit_nom, p.image_url
    FROM details_commande dc
    JOIN produits p ON dc.produit_id = p.id
    WHERE dc.commande_id = ?
");
$itemsStmt->bind_param("i", $orderId);
$itemsStmt->execute();
$itemsResult = $itemsStmt->get_result();

$items = [];
while ($item = $itemsResult->fetch_assoc()) {
    $items[] = $item;
}

// Handle status update
if (isset($_POST['update_status']) && isset($_POST['status'])) {
    $newStatus = $_POST['status'];
    $allowedStatuses = ['en_attente', 'confirmee', 'expediee', 'livree', 'annulee'];
    
    if (in_array($newStatus, $allowedStatuses)) {
        $updateStmt = $conn->prepare("UPDATE commandes SET statut = ? WHERE id = ?");
        $updateStmt->bind_param("si", $newStatus, $orderId);
        $updateStmt->execute();
        $order['statut'] = $newStatus;
    }
}

$statusOptions = [
    'en_attente' => 'En attente',
    'confirmee' => 'Confirmée',
    'expediee' => 'Expédiée',
    'livree' => 'Livrée',
    'annulee' => 'Annulée'
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RDOC Admin - <?= $pageTitle ?> #<?= $orderId ?></title>
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
    <div class="flex h-screen overflow-hidden">
        <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>
        
        <div class="flex-1 flex flex-col overflow-hidden lg:ml-0">
            <header class="flex items-center justify-between h-16 px-4 lg:px-6 glass border-b border-white/10">
                <div class="flex items-center gap-4">
                    <button id="mobile-menu-btn" class="lg:hidden text-text-secondary hover:text-primary">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                    <h1 class="font-orbitron text-xl text-primary">Commande #<?= $orderId ?></h1>
                </div>
                <a href="orders.php" class="btn-outline text-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                    Retour
                </a>
            </header>
            
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Order Info -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Order Items -->
                        <div class="admin-card p-6">
                            <h3 class="font-orbitron text-lg text-primary mb-4">Produits commandés</h3>
                            <div class="overflow-x-auto">
                                <table class="w-full admin-table">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 text-left">Produit</th>
                                            <th class="px-4 py-3 text-center">Quantité</th>
                                            <th class="px-4 py-3 text-right">Prix unitaire</th>
                                            <th class="px-4 py-3 text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center gap-3">
                                                        <?php if ($item['image_url']): ?>
                                                            <img src="../../assets/uploads/admin/products/<?= escape($item['image_url']) ?>" 
                                                                 alt="<?= escape($item['produit_nom']) ?>" 
                                                                 class="w-12 h-12 object-cover rounded-lg">
                                                        <?php else: ?>
                                                            <div class="w-12 h-12 rounded-lg bg-white/10 flex items-center justify-center">
                                                                <i data-lucide="package" class="w-5 h-5 text-white/30"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                        <span class="font-medium"><?= escape($item['produit_nom']) ?></span>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-center"><?= $item['quantite'] ?></td>
                                                <td class="px-4 py-3 text-right"><?= number_format($item['prix_unitaire'], 2, ',', ' ') ?> DH</td>
                                                <td class="px-4 py-3 text-right font-bold"><?= number_format($item['quantite'] * $item['prix_unitaire'], 2, ',', ' ') ?> DH</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="px-4 py-3 text-right font-bold">Total</td>
                                            <td class="px-4 py-3 text-right font-bold text-primary text-lg"><?= number_format($order['total'], 2, ',', ' ') ?> DH</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Timeline -->
                        <div class="admin-card p-6">
                            <h3 class="font-orbitron text-lg text-primary mb-4">Historique</h3>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full bg-primary"></div>
                                    <div class="text-sm">
                                        <span class="font-medium">Commande passée</span>
                                        <span class="text-text-secondary"> - <?= date('d/m/Y H:i', strtotime($order['date_commande'])) ?></span>
                                    </div>
                                </div>
                                <?php if ($order['statut'] !== 'en_attente'): ?>
                                <div class="flex items-center gap-3">
                                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                                    <div class="text-sm">
                                        <span class="font-medium">Statut: <?= $statusOptions[$order['statut']] ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sidebar -->
                    <div class="space-y-6">
                        <!-- Status Update -->
                        <div class="admin-card p-6">
                            <h3 class="font-orbitron text-lg text-primary mb-4">Statut de la commande</h3>
                            <form method="POST" class="space-y-4">
                                <input type="hidden" name="update_status" value="1">
                                <select name="status" class="form-input">
                                    <?php foreach ($statusOptions as $value => $label): ?>
                                        <option value="<?= $value ?>" <?= $order['statut'] === $value ? 'selected' : '' ?>>
                                            <?= $label ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="w-full btn-outline">
                                    Mettre à jour
                                </button>
                            </form>
                        </div>
                        
                        <!-- Customer Info -->
                        <div class="admin-card p-6">
                            <h3 class="font-orbitron text-lg text-primary mb-4">Client</h3>
                            <div class="space-y-3">
                                <div>
                                    <div class="text-sm text-text-secondary">Nom</div>
                                    <div class="font-medium"><?= escape($order['prenom'] . ' ' . $order['nom']) ?></div>
                                </div>
                                <div>
                                    <div class="text-sm text-text-secondary">Email</div>
                                    <div class="font-medium"><?= escape($order['email']) ?></div>
                                </div>
                                <?php if ($order['telephone']): ?>
                                <div>
                                    <div class="text-sm text-text-secondary">Téléphone</div>
                                    <div class="font-medium"><?= escape($order['telephone']) ?></div>
                                </div>
                                <?php endif; ?>
                                <?php if ($order['adresse']): ?>
                                <div>
                                    <div class="text-sm text-text-secondary">Adresse</div>
                                    <div class="font-medium"><?= escape($order['adresse']) ?></div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="admin-card p-6">
                            <h3 class="font-orbitron text-lg text-primary mb-4">Résumé</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-text-secondary">Numéro</span>
                                    <span class="font-medium">#<?= $order['id'] ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-text-secondary">Date</span>
                                    <span class="font-medium"><?= date('d/m/Y H:i', strtotime($order['date_commande'])) ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-text-secondary">Statut</span>
                                    <?= getStatusBadge($order['statut']) ?>
                                </div>
                                <div class="flex justify-between pt-3 border-t border-white/10">
                                    <span class="font-bold">Total</span>
                                    <span class="font-bold text-primary"><?= number_format($order['total'], 2, ',', ' ') ?> DH</span>
                                </div>
                            </div>
                        </div>
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
    </script>
</body>
</html>