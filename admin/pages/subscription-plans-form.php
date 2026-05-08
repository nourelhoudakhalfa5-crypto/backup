<?php
require_once __DIR__ . '/../../includes/auth.php';
requireAdminLogin();

$pageTitle = 'Plan d\'abonnement';
$currentPage = 'subscription-plans';

$conn = getMySQLi();
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$plan = ['nom' => '', 'description' => '', 'prix' => '', 'duree' => 'mois', 'nombre_produits' => '', 'nombre_categories' => '', 'statut' => 'actif'];

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM subscription_plans WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($planData = $result->fetch_assoc()) {
        $plan = array_merge($plan, $planData);
    }
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $prix = floatval($_POST['prix'] ?? 0);
    $duree = $_POST['duree'] ?? 'mois';
    $nombre_produits = $_POST['nombre_produits'] !== '' ? (int) $_POST['nombre_produits'] : null;
    $nombre_categories = $_POST['nombre_categories'] !== '' ? (int) $_POST['nombre_categories'] : null;
    $statut = $_POST['statut'] ?? 'actif';
    
    if (empty($nom)) {
        $error = 'Le nom du plan est obligatoire';
    } elseif ($prix <= 0) {
        $error = 'Le prix doit être supérieur à 0';
    } else {
        if ($id) {
            $stmt = $conn->prepare("UPDATE subscription_plans SET nom = ?, description = ?, prix = ?, duree = ?, nombre_produits = ?, nombre_categories = ?, statut = ? WHERE id = ?");
            $stmt->bind_param("ssdssssi", $nom, $description, $prix, $duree, $nombre_produits, $nombre_categories, $statut, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO subscription_plans (nom, description, prix, duree, nombre_produits, nombre_categories, statut) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdssss", $nom, $description, $prix, $duree, $nombre_produits, $nombre_categories, $statut);
        }
        
        if ($stmt->execute()) {
            $success = $id ? 'Plan modifié avec succès' : 'Plan créé avec succès';
            redirectTo('subscription-plans.php?success=1');
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
            min-height: 100px;
            resize: vertical;
        }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
        }
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
                    <h1 class="font-orbitron text-xl text-primary"><?= $id ? 'Modifier' : 'Nouveau' ?> Plan</h1>
                </div>
                <a href="subscription-plans.php" class="btn-outline text-sm">
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
                    <form method="POST" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Nom du plan *</label>
                                <input type="text" name="nom" class="form-input" value="<?= escape($plan['nom']) ?>" required placeholder="Ex: Premium">
                            </div>
                            
                            <div>
                                <label class="form-label">Prix (DH) *</label>
                                <input type="number" name="prix" class="form-input" value="<?= $plan['prix'] ?>" required step="0.01" min="0" placeholder="99.00">
                            </div>
                        </div>
                        
                        <div>
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-textarea" rows="3" placeholder="Description du plan..."><?= escape($plan['description']) ?></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="form-label">Durée</label>
                                <select name="duree" class="form-input">
                                    <option value="mois" <?= $plan['duree'] === 'mois' ? 'selected' : '' ?>>Mensuel</option>
                                    <option value="annee" <?= $plan['duree'] === 'annee' ? 'selected' : '' ?>>Annuel</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="form-label">Nombre de produits</label>
                                <input type="number" name="nombre_produits" class="form-input" value="<?= $plan['nombre_produits'] ?>" min="0" placeholder="0 = illimité">
                            </div>
                            
                            <div>
                                <label class="form-label">Nombre de catégories</label>
                                <input type="number" name="nombre_categories" class="form-input" value="<?= $plan['nombre_categories'] ?>" min="0" placeholder="0 = illimité">
                            </div>
                        </div>
                        
                        <div>
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-input">
                                <option value="actif" <?= $plan['statut'] === 'actif' ? 'selected' : '' ?>>Actif</option>
                                <option value="inactif" <?= $plan['statut'] === 'inactif' ? 'selected' : '' ?>>Inactif</option>
                            </select>
                        </div>
                        
                        <div class="flex justify-end gap-4 pt-4 border-t border-white/10">
                            <a href="subscription-plans.php" class="px-6 py-3 rounded-xl border border-white/20 text-white hover:bg-white/5 transition-colors">
                                Annuler
                            </a>
                            <button type="submit" class="px-6 py-3 rounded-xl bg-primary text-black font-orbitron font-bold hover:bg-primary/80 transition-colors">
                                <?= $id ? 'Mettre à jour' : 'Créer le plan' ?>
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