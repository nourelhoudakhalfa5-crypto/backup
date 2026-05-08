<?php
require_once __DIR__ . '/../../includes/auth.php';
requireAdminLogin();

$pageTitle = 'Client';
$currentPage = 'users';

$conn = getMySQLi();
$error = '';

$id = $_GET['id'] ?? null;
$user = [
    'nom' => '',
    'prenom' => '',
    'email' => '',
    'telephone' => '',
    'adresse' => ''
];

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE id = ? AND role = 'client'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $pageTitle = 'Modifier le client';
    } else {
        redirectTo('users.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    
    if (empty($nom) || empty($prenom) || empty($email)) {
        $error = 'Les champs nom, prénom et email sont requis';
    } else {
        if ($id) {
            $stmt = $conn->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $nom, $prenom, $email, $telephone, $adresse, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, prenom, email, telephone, adresse, role) VALUES (?, ?, ?, ?, ?, 'client')");
            $stmt->bind_param("sssss", $nom, $prenom, $email, $telephone, $adresse);
        }
        
        if ($stmt->execute()) {
            redirectTo('users.php?saved=1');
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
            </header>
            
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="max-w-2xl mx-auto">
                    <form method="POST" class="admin-card p-6">
                        <?php if ($error): ?>
                            <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-3 mb-4 text-red-400 text-sm">
                                <?= escape($error) ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-white/80">Prénom *</label>
                                    <input type="text" name="prenom" required value="<?= escape($user['prenom']) ?>"
                                           class="form-input w-full" placeholder="Prénom">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-white/80">Nom *</label>
                                    <input type="text" name="nom" required value="<?= escape($user['nom']) ?>"
                                           class="form-input w-full" placeholder="Nom">
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2 text-white/80">Email *</label>
                                <input type="email" name="email" required value="<?= escape($user['email']) ?>"
                                       class="form-input w-full" placeholder="email@example.com">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2 text-white/80">Téléphone</label>
                                <input type="tel" name="telephone" value="<?= escape($user['telephone'] ?? '') ?>"
                                       class="form-input w-full" placeholder="+33 6 12 34 56 78">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2 text-white/80">Adresse</label>
                                <textarea name="adresse" rows="3"
                                          class="form-input w-full resize-none" placeholder="Adresse complète"><?= escape($user['adresse'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="flex gap-3 pt-4">
                                <a href="users.php" class="px-6 py-2 rounded-lg border border-white/20 text-white/70 hover:bg-white/5 transition-colors">
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