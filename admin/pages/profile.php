<?php
require_once __DIR__ . '/../../includes/auth.php';
requireAdminLogin();

$pageTitle = 'Mon Profil';
$currentPage = 'profile';

$conn = getMySQLi();
$adminId = adminId();

$error = '';
$success = '';

// Get current admin data
$stmt = $conn->prepare("SELECT id, nom, email FROM administrateurs WHERE id = ?");
$stmt->bind_param("i", $adminId);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'profile';
    
    if ($action === 'profile') {
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        
        if (empty($nom) || empty($email)) {
            $error = 'Tous les champs sont obligatoires';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Email invalide';
        } else {
            // Check if email is already used by another admin
            $checkStmt = $conn->prepare("SELECT id FROM administrateurs WHERE email = ? AND id != ?");
            $checkStmt->bind_param("si", $email, $adminId);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();
            
            if ($checkResult->num_rows > 0) {
                $error = 'Cet email est déjà utilisé';
            } else {
                $updateStmt = $conn->prepare("UPDATE administrateurs SET nom = ?, email = ? WHERE id = ?");
                $updateStmt->bind_param("ssi", $nom, $email, $adminId);
                
                if ($updateStmt->execute()) {
                    $_SESSION[ADMIN_SESSION_KEY]['nom'] = $nom;
                    $_SESSION[ADMIN_SESSION_KEY]['email'] = $email;
                    $success = 'Profil mis à jour avec succès';
                    $admin['nom'] = $nom;
                    $admin['email'] = $email;
                } else {
                    $error = 'Erreur lors de la mise à jour';
                }
            }
        }
    } elseif ($action === 'password') {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $error = 'Tous les champs sont obligatoires';
        } elseif (strlen($newPassword) < 6) {
            $error = 'Le mot de passe doit contenir au moins 6 caractères';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'Les mots de passe ne correspondent pas';
        } else {
            // Verify current password
            $pwdStmt = $conn->prepare("SELECT mot_de_passe FROM administrateurs WHERE id = ?");
            $pwdStmt->bind_param("i", $adminId);
            $pwdStmt->execute();
            $pwdResult = $pwdStmt->get_result();
            $pwdData = $pwdResult->fetch_assoc();
            
            if (!password_verify($currentPassword, $pwdData['mot_de_passe'])) {
                $error = 'Mot de passe actuel incorrect';
            } else {
                $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $updatePwdStmt = $conn->prepare("UPDATE administrateurs SET mot_de_passe = ? WHERE id = ?");
                $updatePwdStmt->bind_param("si", $newHash, $adminId);
                
                if ($updatePwdStmt->execute()) {
                    $success = 'Mot de passe modifié avec succès';
                } else {
                    $error = 'Erreur lors du changement de mot de passe';
                }
            }
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
        .form-input {
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
        .form-input:focus {
            outline: none;
            border-color: #5CC4E5;
        }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
        }
        .tab-btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .tab-btn.active {
            background: #5CC4E5;
            color: #000;
        }
        .tab-btn:not(.active) {
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.7);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
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
                    <h1 class="font-orbitron text-xl text-primary"><?= $pageTitle ?></h1>
                </div>
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
                
                <!-- Profile Header -->
                <div class="admin-card p-6 mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-20 h-20 rounded-full bg-primary/20 flex items-center justify-center">
                            <i data-lucide="user" class="w-10 h-10 text-primary"></i>
                        </div>
                        <div>
                            <h2 class="font-orbitron text-xl"><?= escape($admin['nom']) ?></h2>
                            <p class="text-text-secondary">Administrateur</p>
                        </div>
                    </div>
                </div>
                
                <!-- Tabs -->
                <div class="flex gap-2 mb-6">
                    <button onclick="switchTab('profile')" id="tab-profile" class="tab-btn active">
                        <i data-lucide="user" class="w-4 h-4 inline mr-2"></i>
                        Informations
                    </button>
                    <button onclick="switchTab('password')" id="tab-password" class="tab-btn">
                        <i data-lucide="lock" class="w-4 h-4 inline mr-2"></i>
                        Mot de passe
                    </button>
                </div>
                
                <!-- Profile Form -->
                <div id="content-profile" class="tab-content active">
                    <div class="admin-card p-6">
                        <h3 class="font-orbitron text-lg text-primary mb-6">Informations du profil</h3>
                        <form method="POST" class="space-y-6">
                            <input type="hidden" name="action" value="profile">
                            
                            <div>
                                <label class="form-label">Nom complet</label>
                                <input type="text" name="nom" class="form-input" value="<?= escape($admin['nom']) ?>" required>
                            </div>
                            
                            <div>
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-input" value="<?= escape($admin['email']) ?>" required>
                            </div>
                            
                            <div class="pt-4 border-t border-white/10">
                                <button type="submit" class="px-6 py-3 rounded-xl bg-primary text-black font-orbitron font-bold hover:bg-primary/80 transition-colors">
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Password Form -->
                <div id="content-password" class="tab-content">
                    <div class="admin-card p-6">
                        <h3 class="font-orbitron text-lg text-primary mb-6">Changer le mot de passe</h3>
                        <form method="POST" class="space-y-6">
                            <input type="hidden" name="action" value="password">
                            
                            <div>
                                <label class="form-label">Mot de passe actuel</label>
                                <input type="password" name="current_password" class="form-input" required>
                            </div>
                            
                            <div>
                                <label class="form-label">Nouveau mot de passe</label>
                                <input type="password" name="new_password" class="form-input" required minlength="6">
                            </div>
                            
                            <div>
                                <label class="form-label">Confirmer le mot de passe</label>
                                <input type="password" name="confirm_password" class="form-input" required>
                            </div>
                            
                            <div class="pt-4 border-t border-white/10">
                                <button type="submit" class="px-6 py-3 rounded-xl bg-primary text-black font-orbitron font-bold hover:bg-primary/80 transition-colors">
                                    Changer le mot de passe
                                </button>
                            </div>
                        </form>
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
        
        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            document.getElementById('tab-' + tab).classList.add('active');
            document.getElementById('content-' + tab).classList.add('active');
        }
    </script>
</body>
</html>