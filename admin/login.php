<?php
require_once __DIR__ . '/includes/auth.php';

if (adminIsLoggedIn()) {
    redirectTo('index.php');
}

$error = '';
$client_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs';
    } else {
        $conn = getMySQLi();
        
        $checkClient = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ? AND role = 'client'");
        $checkClient->bind_param("s", $email);
        $checkClient->execute();
        $clientResult = $checkClient->get_result();
        
        if ($clientResult->num_rows > 0) {
            $client_message = 'Vous êtes un client. Veuillez utiliser la <a href="../login.php" class="text-primary hover:underline">page de connexion client</a>.';
        } else {
            $stmt = $conn->prepare("SELECT id, nom, mot_de_passe FROM administrateurs WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows > 0) {
                $admin = $result->fetch_assoc();
                if (password_verify($password, $admin['mot_de_passe'])) {
                    adminLogin($admin['id'], $admin['nom'], $email);
                    redirectTo('index.php');
                } else {
                    $error = 'Email ou mot de passe incorrect';
                }
            } else {
                $error = 'Email ou mot de passe incorrect';
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
    <title>RDOC Admin - Connexion</title>
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
                    },
                    fontFamily: {
                        orbitron: ['Orbitron', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/lucide.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #000000 0%, #0a0a0a 100%);
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(29px);
            -webkit-backdrop-filter: blur(29px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .form-input:focus {
            outline: none;
            border-color: #5CC4E5;
        }
    </style>
</head>
<body class="font-inter text-white flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="glass-card rounded-3xl p-8 shadow-2xl">
            <div class="text-center mb-8">
                <img src="../assets/favicon.png" alt="Logo" class="h-12 w-auto mx-auto mb-4">
                <h1 class="font-orbitron text-3xl text-primary mb-2">RDOC Admin</h1>
                <p class="text-white/60">Connectez-vous pour accéder au tableau de bord</p>
            </div>
            
            <?php if ($client_message): ?>
                <div class="bg-primary/20 border border-primary/30 rounded-lg p-3 mb-6 text-primary text-sm">
                    <?= $client_message ?>
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="bg-red-500/20 border border-red-500/30 rounded-lg p-3 mb-6 text-red-400 text-sm">
                    <?= escape($error) ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium mb-2 text-white/80">Email</label>
                    <input type="email" name="email" required 
                           class="form-input w-full px-4 py-3 rounded-xl bg-white/5 border border-white/20 text-white placeholder-white/40"
                           placeholder="admin@example.com" value="<?= escape($_POST['email'] ?? '') ?>">
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-2 text-white/80">Mot de passe</label>
                    <input type="password" name="password" required 
                           class="form-input w-full px-4 py-3 rounded-xl bg-white/5 border border-white/20 text-white placeholder-white/40"
                           placeholder="••••••••">
                </div>
                
                <button type="submit" 
                        class="w-full bg-primary text-black font-orbitron font-bold py-3 rounded-xl hover:bg-primary/80 transition-all transform hover:scale-105">
                    Se connecter
                </button>
            </form>
            
            <div class="mt-6 text-center space-y-2">
                <a href="../login.php" class="block text-primary/70 hover:text-primary text-sm transition-colors">
                    ← Connexion client
                </a>
                <a href="../index.php" class="block text-white/40 hover:text-white/60 text-xs transition-colors">
                    Retour au site
                </a>
            </div>
        </div>
    </div>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</body>
</html>