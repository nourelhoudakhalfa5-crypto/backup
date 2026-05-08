<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header('Location: login.php');
    exit;
}

require_once 'includes/db.php';

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

$stmt = $conn->prepare("SELECT id, nom, prenom, email, telephone, adresse, date_inscription FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_profile') {
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $adresse = trim($_POST['adresse'] ?? '');
        
        if (empty($nom) || empty($prenom)) {
            $error = 'Le nom et le prénom sont obligatoires.';
        } else {
            $updateStmt = $conn->prepare("UPDATE utilisateurs SET nom = ?, prenom = ?, telephone = ?, adresse = ? WHERE id = ?");
            $updateStmt->bind_param("ssssi", $nom, $prenom, $telephone, $adresse, $user_id);
            
            if ($updateStmt->execute()) {
                $_SESSION['user_nom'] = $nom;
                $_SESSION['user_prenom'] = $prenom;
                $user['nom'] = $nom;
                $user['prenom'] = $prenom;
                $user['telephone'] = $telephone;
                $user['adresse'] = $adresse;
                $success = 'Profil mis à jour avec succès.';
            } else {
                $error = 'Erreur lors de la mise à jour.';
            }
        }
    } elseif ($_POST['action'] === 'update_password') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = 'Tous les champs sont obligatoires.';
        } elseif (strlen($new_password) < 6) {
            $error = 'Le mot de passe doit contenir au moins 6 caractères.';
        } elseif ($new_password !== $confirm_password) {
            $error = 'Les mots de passe ne correspondent pas.';
        } else {
            $pwdStmt = $conn->prepare("SELECT mot_de_passe FROM utilisateurs WHERE id = ?");
            $pwdStmt->bind_param("i", $user_id);
            $pwdStmt->execute();
            $pwdResult = $pwdStmt->get_result();
            $pwdData = $pwdResult->fetch_assoc();
            
            if (!password_verify($current_password, $pwdData['mot_de_passe'])) {
                $error = 'Mot de passe actuel incorrect.';
            } else {
                $newHash = password_hash($new_password, PASSWORD_DEFAULT);
                $updatePwdStmt = $conn->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
                $updatePwdStmt->bind_param("si", $newHash, $user_id);
                
                if ($updatePwdStmt->execute()) {
                    $success = 'Mot de passe modifié avec succès.';
                } else {
                    $error = 'Erreur lors du changement de mot de passe.';
                }
            }
        }
    }
}

$orders = [];
$orderStmt = $conn->prepare("
    SELECT c.*, 
           (SELECT COUNT(*) FROM details_commande WHERE commande_id = c.id) as items_count
    FROM commandes c 
    WHERE c.utilisateur_id = ? 
    ORDER BY c.date_commande DESC
    LIMIT 10
");
$orderStmt->bind_param("i", $user_id);
$orderStmt->execute();
$orderResult = $orderStmt->get_result();
while ($order = $orderResult->fetch_assoc()) {
    $orders[] = $order;
}

function getOrderStatusBadge($status) {
    $badges = [
        'en_attente' => '<span class="px-2 py-1 text-xs rounded-full bg-yellow-500/20 text-yellow-400">En attente</span>',
        'confirmee' => '<span class="px-2 py-1 text-xs rounded-full bg-blue-500/20 text-blue-400">Confirmée</span>',
        'expediee' => '<span class="px-2 py-1 text-xs rounded-full bg-purple-500/20 text-purple-400">Expédiée</span>',
        'livree' => '<span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">Livrée</span>',
        'annulee' => '<span class="px-2 py-1 text-xs rounded-full bg-red-500/20 text-red-400">Annulée</span>'
    ];
    return $badges[$status] ?? $status;
}

function formatPrice($price) {
    return number_format((float) $price, 2, ',', ' ') . ' DH';
}

function formatDate($date) {
    return date('d/m/Y à H:i', strtotime($date));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - RDOC</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#5CC4E5",
                        "bg-dark": "#000000",
                    },
                    fontFamily: {
                        orbitron: ["Orbitron", "sans-serif"],
                        inter: ["Inter", "sans-serif"],
                    },
                },
            },
        };
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Orbitron:wght@400;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css">
    <style>
        body {
            background-color: #000000;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .tab-btn.active {
            background: #5CC4E5;
            color: #000;
        }
    </style>
</head>
<body class="min-h-screen text-white font-inter">
    <?php include 'includes/nav.php'; ?>
    
    <div class="hidden lg:block" style="height: 80px"></div>
    
    <main class="container mx-auto px-4 py-8 lg:py-12 max-w-6xl">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="font-orbitron text-2xl lg:text-3xl text-primary">Mon Profil</h1>
                <p class="text-white/60 mt-1">Bienvenue, <?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></p>
            </div>
            <a href="logout.php" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 transition-colors">
                <i data-lucide="log-out" class="w-4 h-4"></i>
                <span>Déconnexion</span>
            </a>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-4 mb-6 text-red-400">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="bg-green-500/20 border border-green-500/30 rounded-lg p-4 mb-6 text-green-400">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <div class="flex flex-col lg:flex-row gap-6">
            <div class="lg:w-1/3">
                <div class="glass-card rounded-2xl p-6">
                    <div class="text-center mb-6">
                        <div class="w-20 h-20 rounded-full bg-primary/20 flex items-center justify-center mx-auto mb-4">
                            <span class="font-orbitron text-3xl text-primary">
                                <?= strtoupper(substr($user['prenom'], 0, 1) . substr($user['nom'], 0, 1)) ?>
                            </span>
                        </div>
                        <h2 class="font-orbitron text-xl"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h2>
                        <p class="text-white/60 text-sm"><?= htmlspecialchars($user['email']) ?></p>
                        <p class="text-white/40 text-xs mt-2">Membre depuis <?= formatDate($user['date_inscription']) ?></p>
                    </div>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center gap-3 text-white/70">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                            <span><?= htmlspecialchars($user['telephone'] ?: 'Non spécifié') ?></span>
                        </div>
                        <div class="flex items-center gap-3 text-white/70">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                            <span><?= htmlspecialchars($user['adresse'] ?: 'Non spécifiée') ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="lg:w-2/3">
                <div class="glass-card rounded-2xl overflow-hidden">
                    <div class="flex border-b border-white/10">
                        <button onclick="showTab('profile')" id="tab-profile" class="tab-btn active flex-1 px-6 py-4 text-center font-medium transition-colors">
                            <i data-lucide="user" class="w-4 h-4 inline-block mr-2"></i>
                            Profil
                        </button>
                        <button onclick="showTab('orders')" id="tab-orders" class="tab-btn flex-1 px-6 py-4 text-center font-medium text-white/60 transition-colors hover:bg-white/5">
                            <i data-lucide="shopping-bag" class="w-4 h-4 inline-block mr-2"></i>
                            Commandes
                        </button>
                        <button onclick="showTab('password')" id="tab-password" class="tab-btn flex-1 px-6 py-4 text-center font-medium text-white/60 transition-colors hover:bg-white/5">
                            <i data-lucide="lock" class="w-4 h-4 inline-block mr-2"></i>
                            Sécurité
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <div id="content-profile">
                            <form method="POST" class="space-y-4">
                                <input type="hidden" name="action" value="update_profile">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-2 text-white/80">Prénom</label>
                                        <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" 
                                               class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/20 text-white focus:border-primary focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-2 text-white/80">Nom</label>
                                        <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" 
                                               class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/20 text-white focus:border-primary focus:outline-none">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-white/80">Email</label>
                                    <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled
                                           class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/20 text-white/50 cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-white/80">Téléphone</label>
                                    <input type="tel" name="telephone" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>" 
                                           class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/20 text-white focus:border-primary focus:outline-none"
                                           placeholder="Numéro de téléphone">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-white/80">Adresse</label>
                                    <textarea name="adresse" rows="2" 
                                              class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/20 text-white focus:border-primary focus:outline-none"
                                              placeholder="Votre adresse"><?= htmlspecialchars($user['adresse'] ?? '') ?></textarea>
                                </div>
                                <button type="submit" class="px-6 py-3 bg-primary text-black font-semibold rounded-xl hover:bg-primary/80 transition-colors">
                                    Enregistrer les modifications
                                </button>
                            </form>
                        </div>
                        
                        <div id="content-orders" class="hidden">
                            <?php if (empty($orders)): ?>
                                <div class="text-center py-8 text-white/60">
                                    <i data-lucide="shopping-bag" class="w-12 h-12 mx-auto mb-4 opacity-50"></i>
                                    <p>Aucune commande trouvée.</p>
                                </div>
                            <?php else: ?>
                                <div class="space-y-4">
                                    <?php foreach ($orders as $order): ?>
                                        <div class="glass-card rounded-xl p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                            <div>
                                                <div class="flex items-center gap-3 mb-2">
                                                    <span class="font-orbitron text-primary">#<?= $order['id'] ?></span>
                                                    <?= getOrderStatusBadge($order['statut']) ?>
                                                </div>
                                                <p class="text-white/60 text-sm">
                                                    <?= $order['items_count'] ?> article(s) • <?= formatDate($order['date_commande']) ?>
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="font-orbitron text-xl text-primary"><?= formatPrice($order['total']) ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div id="content-password" class="hidden">
                            <form method="POST" class="space-y-4 max-w-md">
                                <input type="hidden" name="action" value="update_password">
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-white/80">Mot de passe actuel</label>
                                    <input type="password" name="current_password" required
                                           class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/20 text-white focus:border-primary focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-white/80">Nouveau mot de passe</label>
                                    <input type="password" name="new_password" required minlength="6"
                                           class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/20 text-white focus:border-primary focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2 text-white/80">Confirmer le mot de passe</label>
                                    <input type="password" name="confirm_password" required
                                           class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/20 text-white focus:border-primary focus:outline-none">
                                </div>
                                <button type="submit" class="px-6 py-3 bg-primary text-black font-semibold rounded-xl hover:bg-primary/80 transition-colors">
                                    Changer le mot de passe
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        
        function showTab(tab) {
            document.getElementById('content-profile').classList.add('hidden');
            document.getElementById('content-orders').classList.add('hidden');
            document.getElementById('content-password').classList.add('hidden');
            
            document.getElementById('tab-profile').classList.remove('active');
            document.getElementById('tab-profile').classList.add('text-white/60');
            document.getElementById('tab-orders').classList.remove('active');
            document.getElementById('tab-orders').classList.add('text-white/60');
            document.getElementById('tab-password').classList.remove('active');
            document.getElementById('tab-password').classList.add('text-white/60');
            
            document.getElementById('content-' + tab).classList.remove('hidden');
            
            document.getElementById('tab-' + tab).classList.add('active');
            document.getElementById('tab-' + tab).classList.remove('text-white/60');
        }
    </script>
    <script src="assets/js/nav.js"></script>
</body>
</html>