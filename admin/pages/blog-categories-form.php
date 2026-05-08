<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdminLogin();

$pageTitle = 'Catégorie Blog';
$currentPage = 'blog-categories';

$conn = getMySQLi();
$error = '';

$id = $_GET['id'] ?? null;
$category = [
    'nom' => '',
    'description' => '',
    'statut' => 'actif'
];

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM blog_categories WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $category = $result->fetch_assoc();
        $pageTitle = 'Modifier la catégorie';
    } else {
        redirectTo('blog-categories.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $statut = $_POST['statut'] === 'inactif' ? 'inactif' : 'actif';
    
    if (empty($nom)) {
        $error = 'Le nom de la catégorie est requis';
    } else {
        if ($id) {
            $stmt = $conn->prepare("UPDATE blog_categories SET nom = ?, description = ?, statut = ? WHERE id = ?");
            $stmt->bind_param("sssi", $nom, $description, $statut, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO blog_categories (nom, description, statut) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nom, $description, $statut);
        }
        
        if ($stmt->execute()) {
            redirectTo('blog-categories.php?saved=1');
        } else {
            $error = 'Erreur lors de l\'enregistrement';
        }
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
            </header>
            
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="max-w-xl mx-auto">
                    <form method="POST" class="admin-card p-6">
                        <?php if ($error): ?>
                            <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-3 mb-4 text-red-400 text-sm">
                                <?= escape($error) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2 text-white/80">Nom *</label>
                                <input type="text" name="nom" required value="<?= escape($category['nom']) ?>"
                                       class="form-input w-full" placeholder="Nom de la catégorie">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2 text-white/80">Description</label>
                                <textarea name="description" rows="4"
                                          class="form-input w-full resize-none" placeholder="Description de la catégorie"><?= escape($category['description'] ?? '') ?></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2 text-white/80">Statut</label>
                                <select name="statut" class="form-input w-full">
                                    <option value="actif" <?= $category['statut'] === 'actif' ? 'selected' : '' ?>>Actif</option>
                                    <option value="inactif" <?= $category['statut'] === 'inactif' ? 'selected' : '' ?>>Inactif</option>
                                </select>
                            </div>
                            
                            <div class="flex gap-3 pt-4">
                                <a href="blog-categories.php" class="px-6 py-2 rounded-lg border border-white/20 text-white/70 hover:bg-white/5 transition-colors">
                                    Annuler
                                </a>
                                <button type="submit" class="btn-outline">
                                    <i data-lucide="save" class="w-4 h-4 mr-1"></i>
                                    Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
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