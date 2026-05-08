<?php
require_once __DIR__ . '/../includes/auth.php';
requireAdminLogin();

$pageTitle = 'Article Blog';
$currentPage = 'blog';

$conn = getMySQLi();
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$post = ['titre' => '', 'slug' => '', 'extrait' => '', 'contenu' => '', 'image' => '', 'categorie_id' => '', 'statut' => 'brouillon'];

// Get blog categories
$categories = [];
$catResult = $conn->query("SELECT id, nom FROM blog_categories WHERE statut = 'actif' ORDER BY nom");
if ($catResult) {
    while ($cat = $catResult->fetch_assoc()) {
        $categories[] = $cat;
    }
}

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM blog_posts WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($postData = $result->fetch_assoc()) {
        $post = array_merge($post, $postData);
    }
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $extrait = trim($_POST['extrait'] ?? '');
    $contenu = $_POST['contenu'] ?? '';
    $categorie_id = $_POST['categorie_id'] ?: null;
    $statut = $_POST['statut'] ?? 'brouillon';
    $deleteImage = isset($_POST['delete_image']);
    
    if (empty($titre)) {
        $error = 'Le titre est obligatoire';
    } else {
        if (empty($slug)) {
            $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]+/', '-', $titre)));
        }
        
        $imagePath = $post['image'];
        
        if ($deleteImage && $imagePath) {
            $fullPath = __DIR__ . '/../../assets/uploads/admin/blog/' . basename($imagePath);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            $imagePath = null;
        }
        
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../assets/uploads/admin/blog/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            
            if (in_array($fileExt, $allowedExts)) {
                $newFilename = 'blog-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $fileExt;
                $targetPath = $uploadDir . $newFilename;
                
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    if ($imagePath && $imagePath !== basename($imagePath)) {
                        $oldPath = __DIR__ . '/../../assets/uploads/admin/blog/' . basename($imagePath);
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }
                    $imagePath = 'assets/uploads/admin/blog/' . $newFilename;
                }
            }
        }
        
        if ($id) {
            $stmt = $conn->prepare("UPDATE blog_posts SET titre = ?, slug = ?, extrait = ?, contenu = ?, image = ?, categorie_id = ?, statut = ?, date_publication = IF(statut = 'publie', COALESCE(date_publication, NOW()), date_publication) WHERE id = ?");
            $stmt->bind_param("sssssssi", $titre, $slug, $extrait, $contenu, $imagePath, $categorie_id, $statut, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO blog_posts (titre, slug, extrait, contenu, image, categorie_id, statut, auteur_id, date_publication) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $authorId = adminId();
            $stmt->bind_param("sssssssi", $titre, $slug, $extrait, $contenu, $imagePath, $categorie_id, $statut, $authorId);
        }
        
        if ($stmt->execute()) {
            $success = $id ? 'Article modifié avec succès' : 'Article créé avec succès';
            if (!$id) {
                $id = $conn->insert_id;
            }
            redirectTo('blog.php?success=1');
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
        .form-input, .form-select, .form-textarea {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none;
            border-color: #5CC4E5;
        }
        .form-textarea {
            min-height: 200px;
            resize: vertical;
        }
        .form-select {
            cursor: pointer;
        }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
        }
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 12px;
            object-fit: cover;
            margin-top: 10px;
        }
    </style>
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
                    <h1 class="font-orbitron text-xl text-primary"><?= $id ? 'Modifier' : 'Nouvel' ?> Article</h1>
                </div>
                <a href="blog.php" class="btn-outline text-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-1"></i>
                    Retour
                </a>
            </header>
            
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <?php if ($error): ?>
                    <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-4 mb-6 text-red-400">
                        <?= escape($error) ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 mb-6 text-green-400">
                        <?= escape($success) ?>
                    </div>
                <?php endif; ?>
                
                <div class="admin-card p-6">
                    <form method="POST" enctype="multipart/form-data" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Titre *</label>
                                <input type="text" name="titre" class="form-input" value="<?= escape($post['titre']) ?>" required>
                            </div>
                            
                            <div>
                                <label class="form-label">Slug (URL)</label>
                                <input type="text" name="slug" class="form-input" value="<?= escape($post['slug']) ?>" placeholder="auto-generate-si-vide">
                            </div>
                        </div>
                        
                        <div>
                            <label class="form-label">Extrait (Résumé)</label>
                            <textarea name="extrait" class="form-textarea" rows="3" placeholder="Bref résumé de l'article..."><?= escape($post['extrait']) ?></textarea>
                        </div>
                        
                        <div>
                            <label class="form-label">Contenu</label>
                            <textarea name="contenu" class="form-textarea" rows="12" placeholder="Contenu de l'article..."><?= escape($post['contenu']) ?></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Catégorie</label>
                                <select name="categorie_id" class="form-select">
                                    <option value="">-- Sélectionner --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= $post['categorie_id'] == $cat['id'] ? 'selected' : '' ?>>
                                            <?= escape($cat['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div>
                                <label class="form-label">Statut</label>
                                <select name="statut" class="form-select">
                                    <option value="brouillon" <?= $post['statut'] === 'brouillon' ? 'selected' : '' ?>>Brouillon</option>
                                    <option value="publie" <?= $post['statut'] === 'publie' ? 'selected' : '' ?>>Publié</option>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label class="form-label">Image</label>
                            <div class="flex flex-col gap-4">
                                <input type="file" name="image" accept="image/*" class="form-input p-3">
                                <?php if ($post['image']): ?>
                                    <div class="flex items-center gap-4">
                                        <img src="../../<?= escape($post['image']) ?>" alt="Current" class="image-preview">
                                        <label class="flex items-center gap-2 text-sm text-yellow-400 cursor-pointer">
                                            <input type="checkbox" name="delete_image" value="1" class="rounded">
                                            Supprimer l'image
                                        </label>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="flex justify-end gap-4 pt-4 border-t border-white/10">
                            <a href="blog.php" class="px-6 py-3 rounded-xl border border-white/20 text-white hover:bg-white/5 transition-colors">
                                Annuler
                            </a>
                            <button type="submit" class="px-6 py-3 rounded-xl bg-primary text-black font-orbitron font-bold hover:bg-primary/80 transition-colors">
                                <?= $id ? 'Mettre à jour' : 'Créer l\'article' ?>
                            </button>
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