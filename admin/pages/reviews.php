<?php
require_once __DIR__ . '/../../includes/auth.php';
requireAdminLogin();

$pageTitle = 'Avis Clients';
$currentPage = 'reviews';

$conn = getMySQLi();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM avis WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    redirectTo('reviews.php?deleted=1');
}

// Handle bulk delete
if (isset($_POST['bulk_delete']) && !empty($_POST['selected_ids'])) {
    $ids = array_map('intval', $_POST['selected_ids']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $conn->prepare("DELETE FROM avis WHERE id IN ($placeholders)");
    $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
    $stmt->execute();
    redirectTo('reviews.php?deleted=1');
}

// Get all reviews with product and user details
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Get total count
$totalResult = $conn->query("SELECT COUNT(*) as total FROM avis");
$totalReviews = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalReviews / $perPage);

// Get reviews with product and user info
$reviews = [];
$result = $conn->query("
    SELECT a.*, p.nom as produit_nom, u.nom as user_nom, u.prenom as user_prenom
    FROM avis a
    LEFT JOIN produits p ON a.produit_id = p.id
    LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id
    ORDER BY a.date_avis DESC
    LIMIT $offset, $perPage
");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
    }
}

// Stats
$avgRating = 0;
$ratingResult = $conn->query("SELECT AVG(note) as avg FROM avis");
if ($ratingResult) {
    $avgRating = round($ratingResult->fetch_assoc()['avg'] ?? 0, 1);
}

$ratingDistribution = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
$distResult = $conn->query("SELECT note, COUNT(*) as count FROM avis GROUP BY note");
if ($distResult) {
    while ($row = $distResult->fetch_assoc()) {
        $ratingDistribution[$row['note']] = $row['count'];
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
    <style>
        .stars { color: #fbbf24; }
    </style>
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
                <form method="POST" class="flex items-center gap-2" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer les avis sélectionnés?')">
                    <input type="hidden" name="bulk_delete" value="1">
                    <button type="submit" class="btn-outline text-sm opacity-50 cursor-not-allowed" id="bulk-delete-btn" disabled>
                        <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                        Supprimer la sélection
                    </button>
                </form>
            </header>
            
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <!-- Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
                    <div class="admin-card p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl bg-primary/20 flex items-center justify-center">
                                <i data-lucide="star" class="w-6 h-6 text-primary"></i>
                            </div>
                        </div>
                        <div class="font-orbitron text-2xl font-bold"><?= $avgRating ? number_format($avgRating, 1) : '-' ?>/5</div>
                        <div class="text-sm text-text-secondary">Note Moyenne</div>
                    </div>
                    
                    <div class="admin-card p-5">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                                <i data-lucide="message-square" class="w-6 h-6 text-yellow-400"></i>
                            </div>
                        </div>
                        <div class="font-orbitron text-2xl font-bold"><?= number_format($totalReviews) ?></div>
                        <div class="text-sm text-text-secondary">Total Avis</div>
                    </div>
                    
                    <div class="admin-card p-5 col-span-1 sm:col-span-2">
                        <div class="text-sm text-text-secondary mb-2">Répartition des notes</div>
                        <div class="flex items-center gap-2">
                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                <div class="flex-1 text-center">
                                    <div class="text-xs text-text-secondary"><?= $i ?></div>
                                    <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                                        <div class="h-full bg-yellow-400 rounded-full" style="width: <?= $totalReviews > 0 ? ($ratingDistribution[$i] / $totalReviews * 100) : 0 ?>%"></div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                
                <div class="admin-card">
                    <div class="p-4 border-b border-white/10">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <input type="text" id="search-input" placeholder="Rechercher un avis..." 
                                       class="form-input w-full">
                            </div>
                            <select id="rating-filter" class="form-input w-full sm:w-auto">
                                <option value="">Toutes les notes</option>
                                <option value="5">5 étoiles</option>
                                <option value="4">4 étoiles</option>
                                <option value="3">3 étoiles</option>
                                <option value="2">2 étoiles</option>
                                <option value="1">1 étoile</option>
                            </select>
                        </div>
                    </div>
                    
                    <form method="POST" id="reviews-form">
                        <div class="overflow-x-auto">
                            <table class="w-full admin-table">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-left w-10">
                                            <input type="checkbox" id="select-all" class="rounded bg-white/10 border-white/20">
                                        </th>
                                        <th class="px-4 py-3 text-left">ID</th>
                                        <th class="px-4 py-3 text-left">Client</th>
                                        <th class="px-4 py-3 text-left">Produit</th>
                                        <th class="px-4 py-3 text-center">Note</th>
                                        <th class="px-4 py-3 text-left">Commentaire</th>
                                        <th class="px-4 py-3 text-left">Date</th>
                                        <th class="px-4 py-3 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($reviews)): ?>
                                        <tr>
                                            <td colspan="8" class="px-4 py-8 text-center text-text-secondary">Aucun avis trouvé</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($reviews as $review): ?>
                                            <tr class="review-row" data-user="<?= strtolower($review['user_prenom'] . ' ' . $review['user_nom']) ?>" data-product="<?= strtolower($review['produit_nom'] ?? '') ?>" data-rating="<?= $review['note'] ?>">
                                                <td class="px-4 py-3">
                                                    <input type="checkbox" name="selected_ids[]" value="<?= $review['id'] ?>" class="review-checkbox rounded bg-white/10 border-white/20">
                                                </td>
                                                <td class="px-4 py-3"><?= $review['id'] ?></td>
                                                <td class="px-4 py-3 font-medium"><?= escape($review['user_prenom'] . ' ' . $review['user_nom']) ?></td>
                                                <td class="px-4 py-3"><?= escape($review['produit_nom'] ?? '-') ?></td>
                                                <td class="px-4 py-3 text-center">
                                                    <div class="stars">
                                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                                            <i data-lucide="star" class="w-3 h-3 inline <?= $i <= $review['note'] ? 'fill-current' : 'text-white/30' ?>"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 max-w-xs truncate"><?= escape($review['commentaire'] ?? '-') ?></td>
                                                <td class="px-4 py-3"><?= date('d/m/Y', strtotime($review['date_avis'])) ?></td>
                                                <td class="px-4 py-3">
                                                    <div class="flex items-center justify-end gap-2">
                                                        <button type="button" onclick="viewReview('<?= escape(addslashes($review['user_prenom'] . ' ' . $review['user_nom'])) ?>', '<?= escape(addslashes($review['produit_nom'] ?? '-')) ?>', <?= $review['note'] ?>, '<?= escape(addslashes($review['commentaire'] ?? '')) ?>', '<?= date('d/m/Y H:i', strtotime($review['date_avis'])) ?>')" 
                                                               class="p-2 rounded-lg hover:bg-white/10 transition-colors" title="Voir">
                                                            <i data-lucide="eye" class="w-4 h-4 text-primary"></i>
                                                        </button>
                                                        <a href="?action=delete&id=<?= $review['id'] ?>" 
                                                           class="p-2 rounded-lg hover:bg-white/10 transition-colors" 
                                                           title="Supprimer"
                                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?')">
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
    
    <!-- View Review Modal -->
    <div id="review-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-[#0a0a0a] border border-white/10 rounded-2xl p-6 w-full max-w-lg shadow-2xl">
                <button onclick="closeModal()" class="absolute top-4 right-4 p-2 rounded-lg hover:bg-white/10">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
                <h3 class="font-orbitron text-xl text-primary mb-4">Avis</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-sm text-text-secondary">Client</div>
                            <div class="font-medium" id="modal-user"></div>
                        </div>
                        <div>
                            <div class="text-sm text-text-secondary">Produit</div>
                            <div class="font-medium" id="modal-product"></div>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-text-secondary">Note</div>
                        <div class="stars mt-1" id="modal-rating"></div>
                    </div>
                    <div>
                        <div class="text-sm text-text-secondary">Commentaire</div>
                        <div class="bg-white/5 rounded-lg p-4 mt-2" id="modal-comment"></div>
                    </div>
                    <div class="text-sm text-text-secondary" id="modal-date"></div>
                </div>
            </div>
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
        const ratingFilter = document.getElementById('rating-filter');
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.review-checkbox');
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
        
        // Search & Filter
        const rows = document.querySelectorAll('.review-row');
        
        function filterRows() {
            const term = searchInput.value.toLowerCase();
            const rating = ratingFilter.value;
            
            rows.forEach(row => {
                const user = row.dataset.user;
                const product = row.dataset.product;
                const ratingVal = row.dataset.rating;
                
                const matchesSearch = user.includes(term) || product.includes(term);
                const matchesRating = !rating || ratingVal === rating;
                
                row.style.display = (matchesSearch && matchesRating) ? '' : 'none';
            });
        }
        
        searchInput.addEventListener('input', filterRows);
        ratingFilter.addEventListener('change', filterRows);
        
        // Bulk select
        selectAll.addEventListener('change', () => {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateBulkBtn();
        });
        
        checkboxes.forEach(cb => cb.addEventListener('change', updateBulkBtn));
        
        function updateBulkBtn() {
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
            bulkDeleteBtn.disabled = !anyChecked;
            bulkDeleteBtn.classList.toggle('opacity-50', !anyChecked);
            bulkDeleteBtn.classList.toggle('cursor-not-allowed', !anyChecked);
        }
        
        // Modal
        function viewReview(user, product, rating, comment, date) {
            document.getElementById('modal-user').textContent = user;
            document.getElementById('modal-product').textContent = product;
            document.getElementById('modal-comment').textContent = comment || 'Aucun commentaire';
            document.getElementById('modal-date').textContent = 'Publié le: ' + date;
            
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                starsHtml += `<i data-lucide="star" class="w-4 h-4 inline ${i <= rating ? 'fill-current text-yellow-400' : 'text-white/30'}"></i>`;
            }
            document.getElementById('modal-rating').innerHTML = starsHtml;
            
            document.getElementById('review-modal').classList.remove('hidden');
            lucide.createIcons();
        }
        
        function closeModal() {
            document.getElementById('review-modal').classList.add('hidden');
        }
    </script>
</body>
</html>