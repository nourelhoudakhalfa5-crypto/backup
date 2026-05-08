<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header('Location: dashboard.php');
        exit;
    } else {
        header('Location: index.php');
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
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="RDOC - Connexion à votre compte" />
        <title>Connexion - RDOC</title>
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
            rel="stylesheet"
        />
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
                    linear-gradient(
                        rgba(92, 196, 229, 0.1),
                        rgba(92, 196, 229, 0.1)
                    ),
                    linear-gradient(135deg, #5cc4e5, #ffffff);
            }

            

            

            

            

            

            

            

            .discover-arrow {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 62px;
                height: 62px;
                border: 1px solid #ffffff;
                border-radius: 50%;
                transition: all 0.3s;
            }

            .discover-arrow:hover {
                border-color: #5cc4e5;
                background: rgba(92, 196, 229, 0.1);
            }

            .nav-pill {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 116px;
                height: 36px;
                border: 1px solid #ffffff;
                border-radius: 18px;
                font-family: "Orbitron", sans-serif;
                font-size: 16px;
                font-weight: 400;
                letter-spacing: -0.176px;
                line-height: 24px;
                color: #ffffff;
                transition: all 0.3s;
                text-decoration: none;
            }

            .nav-pill.active {
                font-weight: 700;
                color: #5cc4e5;
                border-color: #5cc4e5;
            }

            .nav-pill:hover {
                border-color: #5cc4e5;
                color: #5cc4e5;
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
        <!-- Mobile Bottom Navigation -->
       
        <main
            class="flex-1 flex items-center justify-center pt-[100px] lg:pt-[150px] pb-[100px] px-4 sm:px-6 md:px-16 lg:px-24"
        >
            <div class="w-full max-w-md">
                <div class="text-center mb-8 sm:mb-10 md:mb-12 lg:mb-[60px]">
                    <h1
                        class="font-orbitron font-bold text-[22px] sm:text-[28px] md:text-3xl lg:text-[40px] text-primary uppercase tracking-wide mb-3 sm:mb-4 lg:mb-[14px]"
                    >
                        Connexion
                    </h1>
                    <p
                        class="font-inter text-sm sm:text-base md:text-lg lg:text-xl text-white max-w-[500px] mx-auto leading-relaxed"
                    >
                        Acc�dez � votre compte RDOC
                    </p>
                </div>

                <div
                    class="glass rounded-[34px] p-4 sm:p-6 md:p-8 lg:p-[40px] border border-[rgba(92,196,229,0.3)] shadow-lg"
                    style="
                        background: rgba(255, 255, 255, 0.08);
                        backdrop-filter: blur(20px);
                        -webkit-backdrop-filter: blur(20px);
                    "
                >
                    <form method="post" action="" class="space-y-5 sm:space-y-6 md:space-y-8 lg:space-y-[30px]">
                        <?php if (!empty($error)): ?>
                            <div class="bg-red-500/20 border border-red-500 text-red-100 px-4 py-3 rounded-lg text-sm text-center">
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        <!-- Email -->
                        <div>
                            <label
                                for="email"
                                class="block font-inter text-xs sm:text-sm lg:text-base font-bold text-white mb-2 sm:mb-3 lg:mb-[15px]"
                            >
                                Email
                            </label>
                            <input type="email" id="email" name="email" placeholder="example@gmail.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                class="form-input w-full transition-all duration-300 hover:border-primary/50 focus:shadow-lg focus:shadow-primary/20"
                                required
                            />
                        </div>

                        <!-- Password -->
                        <div>
                            <label
                                for="password"
                                class="block font-inter text-xs sm:text-sm lg:text-base font-bold text-white mb-2 sm:mb-3 lg:mb-[15px]"
                            >
                                Mot de passe
                            </label>
                            <input type="password" id="password" name="password" placeholder=".........."
                                class="form-input w-full transition-all duration-300 hover:border-primary/50 focus:shadow-lg focus:shadow-primary/20"
                                required
                            />
                        </div>

                        <!-- Remember & Forgot Password -->
                        <div class="flex items-center justify-between gap-4">
                            <label
                                class="flex items-center gap-2 cursor-pointer group"
                            >
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="w-4 h-4 rounded border-primary/30 bg-white/5 accent-primary cursor-pointer transition-colors duration-300 hover:border-primary"
                                />
                                <span
                                    class="font-inter text-xs sm:text-sm lg:text-base text-white/70 group-hover:text-white transition-colors duration-300"
                                    >Se souvenir de moi</span
                                >
                            </label>
                            <a
                                href="#"
                                class="font-inter text-xs sm:text-sm lg:text-base text-primary hover:text-primary/90 transition-all duration-300 relative inline-block"
                            >
                                Mot de passe oubli�?
                                <span
                                    class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary to-cyan-400 hover:w-full transition-all duration-300"
                                ></span>
                            </a>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="btn-submit w-full mt-5 sm:mt-6 md:mt-8 lg:mt-[30px] relative overflow-hidden group hover:shadow-xl hover:shadow-primary/30 transition-all duration-300"
                        >
                            <span class="relative z-10">Se connecter</span>
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-primary/20 to-cyan-400/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                            ></div>
                        </button>
                    </form>

                    <!-- Sign Up Link -->
                    <div
                        class="mt-5 sm:mt-6 md:mt-8 lg:mt-[30px] text-center pt-5 sm:pt-6 md:pt-8 lg:pt-[30px] border-t border-[rgba(92,196,229,0.2)]"
                    >
                        <p class="font-inter text-xs sm:text-sm lg:text-base text-white/70">
                            Pas encore de compte?
                            <a
                                href="register.php"
                                class="text-primary hover:text-primary/90 font-bold transition-all duration-300 relative inline-block"
                            >
                                Cr�er un compte
                                <span
                                    class="absolute bottom-0 left-0 w-0 h-0.5 bg-gradient-to-r from-primary to-cyan-400 group-hover:w-full transition-all duration-300"
                                ></span>
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

