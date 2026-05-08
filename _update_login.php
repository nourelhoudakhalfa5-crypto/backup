<?php
$c = file_get_contents('login.php');

$loginLogic = <<<'EOD'
<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header('Location: dashboard.php');
        exit;
    } else {
        header('Location: produit.php');
        exit;
    }
}

require_once 'includes/db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        // 1- connexion au serveur (via db.php)
        // 2- Récupération des données (fait)
        
        $loggedIn = false;

        // Check administrateurs
        // 3- Préparation de la requete
        $stmt_admin = $conn->prepare("SELECT id, mot_de_passe FROM administrateurs WHERE email = ?");
        $stmt_admin->bind_param("s", $email);
        // 4- Exécution de la requete
        $stmt_admin->execute();
        $res_admin = $stmt_admin->get_result();
        
        if ($res_admin->num_rows > 0) {
            $admin = $res_admin->fetch_assoc();
            // 5- Vérification
            if (password_verify($password, $admin['mot_de_passe'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['role'] = 'admin';
                header('Location: dashboard.php');
                exit;
            }
        }
        
        // If not found or wrong password for admin, check utilisateurs
        // 3- Préparation de la requete
        $stmt_user = $conn->prepare("SELECT id, mot_de_passe, role FROM utilisateurs WHERE email = ?");
        $stmt_user->bind_param("s", $email);
        // 4- Exécution de la requete
        $stmt_user->execute();
        $res_user = $stmt_user->get_result();
        
        if ($res_user->num_rows > 0) {
            $user = $res_user->fetch_assoc();
            // 5- Vérification
            if (password_verify($password, $user['mot_de_passe'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role']; // 'admin' ou 'client'
                
                if ($user['role'] === 'admin') {
                    header('Location: dashboard.php');
                } else {
                    header('Location: produit.php');
                }
                exit;
            }
        }
        
        $error = "Email ou mot de passe incorrect.";
    }
}
?>
<!doctype html>
<html lang="fr">
EOD;

$c = preg_replace('/<!doctype html>\s*<html lang="fr">/i', $loginLogic, $c, 1);

// Update the form tag
$c = preg_replace('/<form class="space-y-5 sm:space-y-6 md:space-y-8 lg:space-y-\[30px\]">/s', '<form method="post" action="" class="space-y-5 sm:space-y-6 md:space-y-8 lg:space-y-[30px]">' . "\n" . '                        <?php if (!empty($error)): ?>' . "\n" . '                            <div class="bg-red-500/20 border border-red-500 text-red-100 px-4 py-3 rounded-lg text-sm text-center">' . "\n" . '                                <?php echo htmlspecialchars($error); ?>' . "\n" . '                            </div>' . "\n" . '                        <?php endif; ?>', $c, 1);

// Update email input
$c = preg_replace('/<input\s*type="email"\s*id="email"\s*placeholder="example@gmail\.com"/s', '<input type="email" id="email" name="email" placeholder="example@gmail.com" value="<?php echo isset($_POST[\'email\']) ? htmlspecialchars($_POST[\'email\']) : \'\'; ?>" ', $c, 1);

// Update password input
// Sometimes the placeholder in login.php contains weird characters like "" so I'll just use regex
$c = preg_replace('/<input\s*type="password"\s*id="password"\s*placeholder="[^"]*"/s', '<input type="password" id="password" name="password" placeholder=".........."', $c, 1);

// Update submit button type
$c = preg_replace('/<button\s*type="button"\s*class="btn-submit/s', '<button type="submit" class="btn-submit', $c, 1);

file_put_contents('login.php', $c);
echo "login.php updated with logic.";
?>
