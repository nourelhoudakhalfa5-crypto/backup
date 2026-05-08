<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdminLogin();

$pageTitle = 'Clients';
$currentPage = 'users';

$conn = getMySQLi();

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $conn->query("DELETE FROM utilisateurs WHERE id = $id AND role = 'client'");
    redirectTo('users.php?deleted=1');
}

// Get all clients
$users = [];
$result = $conn->query("SELECT * FROM utilisateurs WHERE role = 'client' ORDER BY date_inscription DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
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
                <a href="users-form.php" class="btn-outline text-sm">
                    <i data-lucide="plus" class="w-4 h-4 mr-1"></i>
                    Nouveau client
                </a>
            </header>
            
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="admin-card">
                    <div class="p-4 border-b border-white/10">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <input type="text" id="search-input" placeholder="Rechercher un client..." 
                                       class="form-input w-full">
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full admin-table">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left">ID</th>
                                    <th class="px-4 py-3 text-left">Nom</th>
                                    <th class="px-4 py-3 text-left">Email</th>
                                    <th class="px-4 py-3 text-left">Téléphone</th>
                                    <th class="px-4 py-3 text-left">Date inscription</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users)): ?>
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-text-secondary">Aucun client trouvé</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($users as $user): ?>
                                        <tr class="user-row" data-name="<?= strtolower($user['nom'] . ' ' . $user['prenom']) ?>" data-email="<?= strtolower($user['email']) ?>">
                                            <td class="px-4 py-3"><?= $user['id'] ?></td>
                                            <td class="px-4 py-3 font-medium"><?= escape($user['prenom'] . ' ' . $user['nom']) ?></td>
                                            <td class="px-4 py-3"><?= escape($user['email']) ?></td>
                                            <td class="px-4 py-3"><?= escape($user['telephone'] ?? '-') ?></td>
                                            <td class="px-4 py-3"><?= date('d/m/Y', strtotime($user['date_inscription'])) ?></td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="users-form.php?id=<?= $user['id'] ?>" 
                                                       class="p-2 rounded-lg hover:bg-white/10 transition-colors" title="Modifier">
                                                        <i data-lucide="edit" class="w-4 h-4 text-primary"></i>
                                                    </a>
                                                    <a href="?action=delete&id=<?= $user['id'] ?>" 
                                                       class="p-2 rounded-lg hover:bg-white/10 transition-colors" 
                                                       title="Supprimer"
                                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')">
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
        const rows = document.querySelectorAll('.user-row');
        
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
            
            rows.forEach(row => {
                const name = row.dataset.name;
                const email = row.dataset.email;
                
                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                
                row.style.display = matchesSearch ? '' : 'none';
            });
        }
        
        searchInput.addEventListener('input', filterRows);
    </script>
</body>
</html>