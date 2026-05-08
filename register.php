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
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $fullname = trim($_POST['fullname'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm-password'] ?? '';
    $terms = isset($_POST['terms']);
    
    if (empty($email) || empty($fullname) || empty($password) || empty($confirm_password)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format d'email invalide.";
    } elseif (strlen($password) < 8) {
        $error = "Le mot de passe doit contenir au moins 8 caractères.";
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (!$terms) {
        $error = "Vous devez accepter les conditions d'utilisation.";
    } else {
        // 1- connexion au serveur (déjà fait via db.php)
        // 2- Récupération des données (fait ci-dessus)
        
        // Vérifier si l'email existe déjà dans administrateurs
        // 3- Préparation de la requete
        $stmt_admin = $conn->prepare("SELECT id FROM administrateurs WHERE email = ?");
        $stmt_admin->bind_param("s", $email);
        // 4- Exécution de la requete
        $stmt_admin->execute();
        $res_admin = $stmt_admin->get_result();
        
        // Vérifier si l'email existe déjà dans utilisateurs
        $stmt_user = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $stmt_user->bind_param("s", $email);
        $stmt_user->execute();
        $res_user = $stmt_user->get_result();
        
        if ($res_admin->num_rows > 0 || $res_user->num_rows > 0) {
            $error = "Cet email est déjà utilisé.";
        } else {
            // Créer le compte
            // Diviser fullname en nom et prenom (basique)
            $parts = explode(' ', $fullname, 2);
            $prenom = $parts[0];
            $nom = isset($parts[1]) ? $parts[1] : '';
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = 'client';
            
            // 3- Préparation de la requete
            $stmt_insert = $conn->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("sssss", $nom, $prenom, $email, $hashed_password, $role);
            // 4- Exécution de la requete
            if ($stmt_insert->execute()) {
                // 5- Vérification
                $success = "Compte créé avec succès ! Redirection vers la page de connexion...";
                echo "<script>setTimeout(() => { window.location.href = 'login.php'; }, 2000);</script>";
            } else {
                $error = "Une erreur est survenue lors de la création du compte.";
            }
        }
    }
}
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="RDOC - cr�er un compte" />
  <title>Inscription - RDOC</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: "#5CC4E5",
            "bg-dark": "#000000",
            "text-white": "#FFFFFF",
            "text-muted": "rgba(255, 255, 255, 0.7)",
            "text-dim": "rgba(255, 255, 255, 0.5)",
            "glass-bg": "rgba(255, 255, 255, 0.21)",
            "glass-border": "rgba(255, 255, 255, 0.1)",
          },
          fontFamily: {
            orbitron: ["Orbitron", "sans-serif"],
            inter: ["Inter", "sans-serif"],
          },
          backdropBlur: {
            29: "29px",
          },
          boxShadow: {
            primary: "1px -1px 19.7px 0px #5CC4E5",
            "primary-lg": "1px -1px 30px 0px #5CC4E5",
          },
          maxWidth: {
            container: "1440px",
          },
        },
      },
    };
  </script>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Orbitron:wght@400;700&display=swap"
    rel="stylesheet" />
  <style>
  body {
    background-color: var(--bg-dark);
    background-image: none;
}

    .logo-img {
      height: 38px;
      width: auto;
    }

    .auth-button {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 6px 23px;
      height: 40px;
      border-radius: 18px;
      border: 1px solid transparent;
      background-image:
        linear-gradient(#000, #000),
        linear-gradient(135deg, #ffffff, #5cc4e5);
      background-origin: border-box;
      background-clip: padding-box, border-box;
      font-family: "Inter", sans-serif;
      font-size: 16px;
      font-weight: 500;
      letter-spacing: -0.176px;
      line-height: 24px;
      color: #ffffff;
      transition: all 0.3s;
      text-decoration: none;
    }

    .auth-button:hover {
      background-image:
        linear-gradient(rgba(92, 196, 229, 0.1),
          rgba(92, 196, 229, 0.1)),
        linear-gradient(135deg, #5cc4e5, #ffffff);
    }

    

    

    

    

    

    

    

    @media (max-width: 1200px) {
      

      

      
    }

    @media (max-width: 1024px) {
      

      

      

      
    }

    @media (max-width: 768px) {
      

      

      
    }
  </style>
  <link rel="stylesheet" href="assets/css/responsive.css" />
    <link rel="stylesheet" href="assets/css/layout-refresh.css" />
  <link rel="stylesheet" href="assets/css/nav-footer.css" />
</head>

<body class="min-h-screen flex flex-col">
<?php include 'includes/nav.php'; ?>

  <!-- Desktop NAVBAR - Fixed at top -->


  <!-- Desktop padding -->
  <div class="hidden lg:block" style="height: 80px"></div>
  <!-- Mobile Logo -->


  <!-- Add padding bottom for mobile to account for bottom nav -->
  <div class="lg:hidden" style="height: 70px"></div>
  <!-- Mobile top padding for fixed logo -->
  <div class="lg:hidden" style="height: 70px"></div>
  <!-- Extra bottom padding for mobile bottom nav -->
  <div class="lg:hidden" style="height: 80px"></div>

  <!-- Main Content -->
  <main class="flex-1 flex items-center justify-center pt-[100px] lg:pt-[150px] pb-[100px] px-4 sm:px-6 md:px-16 lg:px-24">
    <div class="w-full max-w-md">
      <div class="text-center mb-8 sm:mb-10 md:mb-12 lg:mb-[60px]">
        <h1
          class="font-orbitron font-bold text-[22px] sm:text-[28px] md:text-3xl lg:text-[30px] text-primary uppercase tracking-wide mb-3 sm:mb-4 lg:mb-[14px]">
          cr�er un compte
        </h1>
        
      </div>

      <div class="glass rounded-[34px] p-4 sm:p-6 md:p-8 lg:p-[40px] border border-[rgba(92,196,229,0.3)] shadow-lg" style="
                        background: rgba(255, 255, 255, 0.08);
                        backdrop-filter: blur(20px);
                        -webkit-backdrop-filter: blur(20px);
                    ">
        <form method="post" action="" class="space-y-5 sm:space-y-6 md:space-y-8 lg:space-y-[30px]">
          <?php if (!empty($error)): ?>
              <div class="bg-red-500/20 border border-red-500 text-red-100 px-4 py-3 rounded-lg text-sm text-center">
                  <?php echo htmlspecialchars($error); ?>
              </div>
          <?php endif; ?>
          <?php if (!empty($success)): ?>
              <div class="bg-green-500/20 border border-green-500 text-green-100 px-4 py-3 rounded-lg text-sm text-center">
                  <?php echo htmlspecialchars($success); ?>
              </div>
          <?php endif; ?>
          <!-- Email -->
          <div>
            <label for="email" class="block font-inter text-xs sm:text-sm lg:text-base font-bold text-white mb-2 sm:mb-3 lg:mb-[15px]">
              Email
            </label>
            <input type="email" id="email" name="email" placeholder="example@gmail.com"
              class="form-input w-full transition-all duration-300 hover:border-primary/50 focus:shadow-lg focus:shadow-primary/20"
              required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
          </div>

          <!-- Nom Complet -->
          <div>
            <label for="fullname" class="block font-inter text-xs sm:text-sm lg:text-base font-bold text-white mb-2 sm:mb-3 lg:mb-[15px]">
              Nom Complet
            </label>
            <input type="text" id="fullname" name="fullname" placeholder="John Doe"
              class="form-input w-full transition-all duration-300 hover:border-primary/50 focus:shadow-lg focus:shadow-primary/20"
              required value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>" />
          </div>

          <!-- Mot de passe -->
          <div>
            <label for="password" class="block font-inter text-xs sm:text-sm lg:text-base font-bold text-white mb-2 sm:mb-3 lg:mb-[15px]">
              Mot de passe
            </label>
            <input type="password" id="password" name="password" placeholder=".........."
              class="form-input w-full transition-all duration-300 hover:border-primary/50 focus:shadow-lg focus:shadow-primary/20"
              required />
          </div>

          <!-- Confirmer Mot de passe -->
          <div>
            <label for="confirm-password" class="block font-inter text-xs sm:text-sm lg:text-base font-bold text-white mb-2 sm:mb-3 lg:mb-[15px]">
              Confirmer le mot de passe
            </label>
            <input type="password" id="confirm-password" name="confirm-password" placeholder="................."
              class="form-input w-full transition-all duration-300 hover:border-primary/50 focus:shadow-lg focus:shadow-primary/20"
              required />
          </div>

          <!-- Terms & Conditions -->
          <div>
            <label class="flex items-start gap-3 cursor-pointer group">
              <input type="checkbox" name="terms"
                class="w-4 h-4 mt-1 rounded border-primary/30 bg-white/5 accent-primary cursor-pointer transition-colors duration-300 hover:border-primary"
                required />
              <span class="font-inter text-xs sm:text-sm lg:text-base text-white/70 group-hover:text-white transition-colors duration-300">
                J'accepte les
                <a href="#"
                  class="text-primary hover:text-primary/90 transition-all duration-300 relative inline-block">conditions
                  d'utilisation</a>
                et la
                <a href="#"
                  class="text-primary hover:text-primary/90 transition-all duration-300 relative inline-block">politique
                  de confidentialit�</a>
              </span>
            </label>
          </div>

          <!-- Submit Button -->
          <button type="submit"
            class="btn-submit w-full mt-5 sm:mt-6 md:mt-8 lg:mt-[30px] relative overflow-hidden group hover:shadow-xl hover:shadow-primary/30 transition-all duration-300">
            <span class="relative z-10">Cr�er un compte</span>
            <div
              class="absolute inset-0 bg-gradient-to-r from-primary/20 to-cyan-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            </div>
          </button>
        </form>

        <!-- Sign In Link -->
        <div class="mt-5 sm:mt-6 md:mt-8 lg:mt-[30px] text-center pt-5 sm:pt-6 md:pt-8 lg:pt-[30px] border-t border-[rgba(92,196,229,0.2)]">
          <p class="font-inter text-xs sm:text-sm lg:text-base text-white/70">
            Vous avez d�j� un compte?
            <a href="login.php"
              class="text-primary hover:text-primary/90 font-bold transition-all duration-300 relative inline-block">
              Se connecter
              <span
                class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary to-cyan-400 group-hover:w-full transition-all duration-300"></span>
            </a>
          </p>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->


  <script src="assets/js/main.js"></script>
<?php include 'includes/footer.php'; ?>
<script src="assets/js/nav.js"></script>
</body>

</html>

