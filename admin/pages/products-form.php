<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdminLogin();

$pageTitle = 'Produit';
$currentPage = 'products';

$conn = getMySQLi();
$error = '';

$id = $_GET['id'] ?? null;
$product = [
    'nom' => '',
    'description' => '',
    'prix' => '',
    'stock' => '0',
    'categorie_id' => '',
    'statut' => 'actif'
];

// Get categories for dropdown
$categories = [];
$result = $conn->query("SELECT id, nom FROM categories WHERE statut = 'actif' ORDER BY nom");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM produits WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $pageTitle = 'Modifier le produit';
    } else {
        redirectTo('products.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $prix = floatval($_POST['prix'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);
    $categorie_id = $_POST['categorie_id'] ?: null;
    $statut = $_POST['statut'] === 'inactif' ? 'inactif' : 'actif';
    
    if (empty($nom)) {
        $error = 'Le nom du produit est requis';
    } elseif ($prix <= 0) {
        $error = 'Le prix doit être supérieur à 0';
    } else {
        // Handle image upload
        $image_url = $product['image_url'] ?? null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image_url = 'prod_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $uploadPath = '../../assets/uploads/admin/products/' . $image_url;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
        }
        
        if ($id) {
            $stmt = $conn->prepare("UPDATE produits SET nom = ?, description = ?, prix = ?, stock = ?, categorie_id = ?, statut = ?, image_url = ? WHERE id = ?");
            $stmt->bind_param("ssdsssii", $nom, $description, $prix, $stock, $categorie_id, $statut, $image_url, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO produits (nom, description, prix, stock, categorie_id, statut, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdsssss", $nom, $description, $prix, $stock, $categorie_id, $statut, $image_url);
        }
        
        if ($stmt->execute()) {
            redirectTo('products.php?saved=1');
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
                <div class="max-w-2xl mx-auto">
                    <form method="POST" enctype="multipart/form-data" class="admin-card p-6">
                        <?php if ($error): ?>
                            <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-3 mb-4 text-red-400 text-sm">
                                <?= escape($error) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-2 text-white/80">Nom *</label>
                                <input type="text" name="nom" required value="<?= escape($product['nom']) ?>"
                                       class="form-input w-full" placeholder="Nom du produit">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2 text-white/80">Description</label>
                                <textarea name="description" rows="4"
                                          class="form-input w-full resize-none" placeholder="Description du produit"><?= escape($product['description'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-white/80">Prix (DH) *</label>
                                    <input type="number" name="prix" step="0.01" min="0" required 
                                           value="<?= $product['prix'] ?>"
                                           class="form-input w-full" placeholder="0.00">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-white/80">Stock</label>
                                    <input type="number" name="stock" min="0" 
                                           value="<?= $product['stock'] ?? 0 ?>"
                                           class="form-input w-full" placeholder="0">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2 text-white/80">Catégorie</label>
                                <select name="categorie_id" class="form-input w-full">
                                    <option value="">Aucune catégorie</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= ($product['categorie_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                            <?= escape($cat['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2 text-white/80">Image</label>
                                <?php if (!empty($product['image_url'])): ?>
                                    <div class="mb-2">
                                        <img src="../../assets/uploads/admin/products/<?= escape($product['image_url']) ?>" 
                                             alt="Image actuelle" class="w-32 h-32 object-cover rounded-lg">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="image" accept="image/*"
                                       class="form-input w-full">
                                <p class="text-xs text-text-secondary mt-1">Formats acceptés: JPG, PNG, WebP</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2 text-white/80">Statut</label>
                                <select name="statut" class="form-input w-full">
                                    <option value="actif" <?= $product['statut'] === 'actif' ? 'selected' : '' ?>>Actif</option>
                                    <option value="inactif" <?= $product['statut'] === 'inactif' ? 'selected' : '' ?>>Inactif</option>
                                </select>
                            </div>
                            
                            <div class="flex gap-3 pt-4">
                                <a href="products.php" class="px-6 py-2 rounded-lg border border-white/20 text-white/70 hover:bg-white/5 transition-colors">
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