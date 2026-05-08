<?php
require_once __DIR__ . '/../../includes/auth.php';
requireAdminLogin();

$pageTitle = 'Newsletter';
$currentPage = 'newsletter';

$conn = getMySQLi();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM newsletter WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    redirectTo('newsletter.php?deleted=1');
}

// Handle bulk delete
if (isset($_POST['bulk_delete']) && !empty($_POST['selected_ids'])) {
    $ids = array_map('intval', $_POST['selected_ids']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conn->prepare("DELETE FROM newsletter WHERE id IN ($placeholders)");
    $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
    $stmt->execute();
    redirectTo('newsletter.php?deleted=1');
}

// Export to CSV
if (isset($_GET['action']) && $_GET['action'] === 'export') {
    $result = $conn->query("SELECT email, date_inscription FROM newsletter ORDER BY date_inscription DESC");
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=newsletter_' . date('Y-m-d') . '.csv');
    $output = fopen('php://output', 'w');
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM for Excel
    fputcsv($output, ['Email', 'Date d\'inscription'], ',');
    
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [$row['email'], $row['date_inscription']], ',');
    }
    fclose($output);
    exit;
}

// Get all subscribers with pagination
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$perPage = 15;
$offset = ($page - 1) * $perPage;

// Get total count
$totalResult = $conn->query("SELECT COUNT(*) as total FROM newsletter");
$totalSubscribers = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalSubscribers / $perPage);

// Get subscribers
$subscribers = [];
$result = $conn->query("SELECT * FROM newsletter ORDER BY date_inscription DESC LIMIT $offset, $perPage");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $subscribers[] = $row;
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
                    <h1 class="font-orbitron text-xl text-primary"><?= $pageTitle ?></h1>
                </div>
                <div class="flex items-center gap-2">
                    <a href="?action=export" class="btn-outline text-sm">
                        <i data-lucide="download" class="w-4 h-4 mr-1"></i>
                        Exporter CSV
                    </a>
                    <form method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer les sélectionnés?')">
                        <input type="hidden" name="bulk_delete" value="1">
                        <button type="submit" class="btn-outline text-sm opacity-50 cursor-not-allowed" id="bulk-delete-btn" disabled>
                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                            Supprimer
                        </button>
                    </form>
                </div>
            </header>
            
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <!-- Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div class="admin-card p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl bg-primary/20 flex items-center justify-center">
                                <i data-lucide="users" class="w-6 h-6 text-primary"></i>
                            </div>
                        </div>
                        <div class="font-orbitron text-2xl font-bold"><?= number_format($totalSubscribers) ?></div>
                        <div class="text-sm text-text-secondary">Abonnés Total</div>
                    </div>
                    
                    <div class="admin-card p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                                <i data-lucide="calendar" class="w-6 h-6 text-green-400"></i>
                            </div>
                        </div>
                        <div class="font-orbitron text-2xl font-bold">
                            <?php
                            $thisMonth = $conn->query("SELECT COUNT(*) as count FROM newsletter WHERE MONTH(date_inscription) = MONTH(NOW()) AND YEAR(date_inscription) = YEAR(NOW())");
                            echo number_format($thisMonth->fetch_assoc()['count'] ?? 0);
                            ?>
                        </div>
                        <div class="text-sm text-text-secondary">Ce mois</div>
                    </div>
                    
                    <div class="admin-card p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center">
                                <i data-lucide="send" class="w-6 h-6 text-purple-400"></i>
                            </div>
                        </div>
                        <div class="font-orbitron text-2xl font-bold">-</div>
                        <div class="text-sm text-text-secondary">Campagnes</div>
                    </div>
                </div>
                
                <div class="admin-card">
                    <div class="p-4 border-b border-white/10">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <input type="text" id="search-input" placeholder="Rechercher un abonné..." 
                                       class="form-input w-full">
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" id="newsletter-form">
                        <div class="overflow-x-auto">
                            <table class="w-full admin-table">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left w-10">
                                            <input type="checkbox" id="select-all" class="rounded bg-white/10 border-white/20">
                                        </th>
                                        <th class="px-4 py-3 text-left">ID</th>
                                        <th class="px-4 py-3 text-left">Email</th>
                                        <th class="px-4 py-3 text-left">Date d'inscription</th>
                                        <th class="px-4 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($subscribers)): ?>
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-text-secondary">Aucun abonnés trouvé</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($subscribers as $sub): ?>
                                            <tr class="subscriber-row" data-email="<?= strtolower($sub['email']) ?>">
                                                <td class="px-4 py-3">
                                                    <input type="checkbox" name="selected_ids[]" value="<?= $sub['id'] ?>" class="subscriber-checkbox rounded bg-white/10 border-white/20">
                                                </td>
                                                <td class="px-4 py-3"><?= $sub['id'] ?></td>
                                                <td class="px-4 py-3 font-medium"><?= escape($sub['email']) ?></td>
                                                <td class="px-4 py-3"><?= date('d/m/Y H:i', strtotime($sub['date_inscription'])) ?></td>
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center justify-end gap-2">
                                                        <a href="mailto:<?= escape($sub['email']) ?>" 
                                                           class="p-2 rounded-lg hover:bg-white/10 transition-colors" title="Envoyer un email">
                                                            <i data-lucide="mail" class="w-4 h-4 text-primary"></i>
                                                        </a>
                                                        <a href="?action=delete&id=<?= $sub['id'] ?>" 
                                                           class="p-2 rounded-lg hover:bg-white/10 transition-colors" 
                                                           title="Supprimer"
                                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet abonné ?')">
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
                    
                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="p-4 border-t border-white/10 flex justify-center gap-2">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="?page=<?= $i ?>" class="px-4 py-2 rounded-lg <?= $i === $page ? 'bg-primary text-black' : 'bg-white/5 hover:bg-white/10' ?> transition-colors">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                        </div>
                    <?php endif; ?>
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
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.subscriber-checkbox');
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
        
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
        
        // Search
        const rows = document.querySelectorAll('.subscriber-row');
        searchInput.addEventListener('input', () => {
            const term = searchInput.value.toLowerCase();
            rows.forEach(row => {
                const email = row.dataset.email;
                row.style.display = email.includes(term) ? '' : 'none';
            });
        });
        
        // Bulk select
        selectAll.addEventListener('change', () => {
            checkboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateBulkBtn();
        });
        
        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkBtn);
        });
        
        function updateBulkBtn() {
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            bulkDeleteBtn.disabled = !anyChecked;
            bulkDeleteBtn.classList.toggle('opacity-50', !anyChecked);
            bulkDeleteBtn.classList.toggle('cursor-not-allowed', !anyChecked);
        }
    </script>
</body>
</html>