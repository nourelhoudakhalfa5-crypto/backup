<?php
require_once 'config/db.php';
require_once 'config/session.php';

requireAdmin();

$type = $_GET['type'] ?? 'produits';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$item = null;
$error = null;

// Charger les données si modification
if ($id) {
    $table = '';
    if ($type === 'produits') $table = 'produits';
    elseif ($type === 'categories') $table = 'categories';
    elseif ($type === 'blog') $table = 'blog_articles';
    
    if ($table) {
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();
    }
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if ($type === 'produits') {
            $nom = $_POST['nom'] ?? '';
            $prix = $_POST['prix'] ?? 0;
            $cat_id = $_POST['categorie_id'] ?? null;
            $desc = $_POST['description'] ?? '';
            $image = $_POST['image'] ?? 'default.png';
            $stock = $_POST['stock'] ?? 0;
            
            if ($id) {
                $stmt = $pdo->prepare("UPDATE produits SET nom = ?, prix = ?, categorie_id = ?, description = ?, image = ?, stock = ? WHERE id = ?");
                $stmt->execute([$nom, $prix, $cat_id, $desc, $image, $stock, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO produits (nom, prix, categorie_id, description, image, stock) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nom, $prix, $cat_id, $desc, $image, $stock]);
            }
        } elseif ($type === 'categories') {
            $nom = $_POST['nom'] ?? '';
            $slug = $_POST['slug'] ?? strtolower(str_replace(' ', '-', $nom));
            $desc = $_POST['description'] ?? '';
            $image = $_POST['image'] ?? 'cat_default.png';
            $statut = $_POST['statut'] ?? 'active';
            
            if ($id) {
                $stmt = $pdo->prepare("UPDATE categories SET nom = ?, slug = ?, description = ?, image = ?, statut = ? WHERE id = ?");
                $stmt->execute([$nom, $slug, $desc, $image, $statut, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO categories (nom, slug, description, image, statut) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$nom, $slug, $desc, $image, $statut]);
            }
        } elseif ($type === 'blog') {
            $titre = $_POST['titre'] ?? '';
            $contenu = $_POST['contenu'] ?? '';
            $image = $_POST['image'] ?? 'blog_default.png';
            $statut = $_POST['statut'] ?? 'publié';
            
            if ($id) {
                $stmt = $pdo->prepare("UPDATE blog_articles SET titre = ?, contenu = ?, image = ?, statut = ? WHERE id = ?");
                $stmt->execute([$titre, $contenu, $image, $statut, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO blog_articles (titre, contenu, image, statut) VALUES (?, ?, ?, ?)");
                $stmt->execute([$titre, $contenu, $image, $statut]);
            }
        }
        
        header("Location: gestion.php?type=$type&msg=saved");
        exit;
    } catch (Exception $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ($id ? 'Modifier' : 'Ajouter') . ' ' . $type; ?> - RDOC Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #050505; color: #fff; }
        .font-orbitron { font-family: 'Orbitron', sans-serif; }
        .text-primary { color: #5CC4E5; }
        .bg-primary { background-color: #5CC4E5; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .form-input { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 12px 16px; width: 100%; color: #fff; outline: none; transition: all 0.3s; }
        .form-input:focus { border-color: #5CC4E5; background: rgba(92, 196, 229, 0.05); }
    </style>
</head>
<body class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-72 bg-black border-r border-white/10 p-8 flex flex-col gap-10 sticky top-0 h-screen shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="font-orbitron font-bold text-xl tracking-tighter">RDOC <span class="text-primary">ADMIN</span></span>
        </div>
        <nav class="flex flex-col gap-4">
            <a href="dashboard.php" class="text-gray-400 hover:text-white hover:bg-white/5 p-4 rounded-2xl transition flex items-center gap-3">Tableau de bord</a>
            <a href="gestion.php?type=produits" class="<?php echo $type === 'produits' ? 'bg-primary text-black' : 'text-gray-400 hover:text-white hover:bg-white/5'; ?> p-4 rounded-2xl font-bold flex items-center gap-3">Produits</a>
            <a href="gestion.php?type=categories" class="<?php echo $type === 'categories' ? 'bg-primary text-black' : 'text-gray-400 hover:text-white hover:bg-white/5'; ?> p-4 rounded-2xl font-bold flex items-center gap-3">Catégories</a>
            <a href="gestion.php?type=blog" class="<?php echo $type === 'blog' ? 'bg-primary text-black' : 'text-gray-400 hover:text-white hover:bg-white/5'; ?> p-4 rounded-2xl font-bold flex items-center gap-3">Blog</a>
        </nav>
    </aside>

    <main class="flex-1 p-12 max-w-4xl mx-auto">
        <header class="mb-12">
            <a href="gestion.php?type=<?php echo $type; ?>" class="text-primary hover:underline flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Retour à la liste
            </a>
            <h1 class="text-4xl font-orbitron font-bold"><?php echo ($id ? 'Modifier' : 'Ajouter') . ' ' . $type; ?></h1>
        </header>

        <?php if ($error): ?>
            <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-2xl mb-8">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="glass p-10 rounded-3xl space-y-8">
            <?php if ($type === 'produits'): ?>
                <div class="grid sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-widest text-gray-500 font-bold">Nom du produit</label>
                        <input type="text" name="nom" class="form-input" required value="<?php echo htmlspecialchars($item['nom'] ?? ''); ?>">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-widest text-gray-500 font-bold">Prix (€)</label>
                        <input type="number" step="0.01" name="prix" class="form-input" required value="<?php echo $item['prix'] ?? ''; ?>">
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-widest text-gray-500 font-bold">Catégorie</label>
                        <select name="categorie_id" class="form-input">
                            <?php foreach($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo (isset($item['categorie_id']) && $item['categorie_id'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['nom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-widest text-gray-500 font-bold">Stock</label>
                        <input type="number" name="stock" class="form-input" required value="<?php echo $item['stock'] ?? 0; ?>">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-widest text-gray-500 font-bold">Image (Nom du fichier)</label>
                    <input type="text" name="image" class="form-input" placeholder="robot-xyz.png" value="<?php echo htmlspecialchars($item['image'] ?? ''); ?>">
                </div>
                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-widest text-gray-500 font-bold">Description</label>
                    <textarea name="description" class="form-input h-40"><?php echo htmlspecialchars($item['description'] ?? ''); ?></textarea>
                </div>

            <?php elseif ($type === 'categories'): ?>
                <div class="grid sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-widest text-gray-500 font-bold">Nom de la catégorie</label>
                        <input type="text" name="nom" class="form-input" required value="<?php echo htmlspecialchars($item['nom'] ?? ''); ?>">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-widest text-gray-500 font-bold">Slug (URL)</label>
                        <input type="text" name="slug" class="form-input" placeholder="ma-categorie" value="<?php echo htmlspecialchars($item['slug'] ?? ''); ?>">
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-widest text-gray-500 font-bold">Statut</label>
                    <select name="statut" class="form-input">
                        <option value="active" <?php echo (isset($item['statut']) && $item['statut'] == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo (isset($item['statut']) && $item['statut'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-widest text-gray-500 font-bold">Description</label>
                    <textarea name="description" class="form-input h-32"><?php echo htmlspecialchars($item['description'] ?? ''); ?></textarea>
                </div>

            <?php elseif ($type === 'blog'): ?>
                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-widest text-gray-500 font-bold">Titre de l'article</label>
                    <input type="text" name="titre" class="form-input" required value="<?php echo htmlspecialchars($item['titre'] ?? ''); ?>">
                </div>
                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-widest text-gray-500 font-bold">Image</label>
                    <input type="text" name="image" class="form-input" placeholder="article.jpg" value="<?php echo htmlspecialchars($item['image'] ?? ''); ?>">
                </div>
                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-widest text-gray-500 font-bold">Contenu</label>
                    <textarea name="contenu" class="form-input h-64"><?php echo htmlspecialchars($item['contenu'] ?? ''); ?></textarea>
                </div>
                <div class="space-y-2">
                    <label class="text-xs uppercase tracking-widest text-gray-500 font-bold">Statut</label>
                    <select name="statut" class="form-input">
                        <option value="publié" <?php echo (isset($item['statut']) && $item['statut'] == 'publié') ? 'selected' : ''; ?>>Publié</option>
                        <option value="brouillon" <?php echo (isset($item['statut']) && $item['statut'] == 'brouillon') ? 'selected' : ''; ?>>Brouillon</option>
                    </select>
                </div>
            <?php endif; ?>

            <div class="pt-6">
                <button type="submit" class="w-full bg-primary text-black py-4 rounded-2xl font-bold hover:scale-[1.02] transition shadow-[0_0_30px_rgba(92,196,229,0.3)]">
                    Enregistrer les modifications
                </button>
            </div>
        </form>
    </main>
</body>
</html>
