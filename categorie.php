<?php
require_once 'includes/pdo.php';

$categories = [];
$stmt = $pdo->query("SELECT * FROM categories WHERE statut = 'actif' ORDER BY nom");
$categories = $stmt->fetchAll();

$categoryLinks = [
    'ÉDUCATIVE' => 'educative.php',
    'GESTION' => 'gestion.php',
    'LOCALISATIVE' => 'localisative.php',
    'INFORMATIVE' => 'informative.php',
    'EDUCATIVE' => 'educative.php',
    'AI' => 'produit.php'
];
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="RDOC - Solutions robotiques innovantes pour l'éducation, la gestion et l'assistance." />
    <title>RDOC - Catégorie</title>
    <link rel="stylesheet" href="assets/css/style.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: '#5CC4E5',
              'bg-dark': '#000000',
              'text-white': '#FFFFFF',
            },
            fontFamily: {
              'orbitron': ['Orbitron', 'sans-serif'],
              'inter': ['Inter', 'sans-serif'],
            },
          }
        }
      }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
      body { background-color: #000000; }
      .logo-img { height: 38px; width: auto; }
      .auth-button {
        display: inline-flex; align-items: center; justify-content: center; padding: 6px 23px;
        height: 40px; border-radius: 18px; border: 1px solid transparent;
        background-image: linear-gradient(#000, #000), linear-gradient(135deg, #FFFFFF, #5CC4E5);
        background-origin: border-box; background-clip: padding-box, border-box;
        font-family: 'Inter', sans-serif; font-size: 16px; font-weight: 500;
        color: #FFFFFF; transition: all 0.3s; text-decoration: none;
      }
      .auth-button:hover { background-image: linear-gradient(rgba(92,196,229,0.1), rgba(92,196,229,0.1)), linear-gradient(135deg, #5CC4E5, #FFFFFF); }
      
      @media (max-width: 768px) {
        
      }
      .hero-section { position: relative; height: 760px; }
      .categorie-hero { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; background: url('assets/images/categories/banner.png') no-repeat center center; background-size: cover; }
      
      
      
      .cat-hero-title { font-family: 'Orbitron', sans-serif; font-size: clamp(28px, 5vw, 40px); font-weight: 700; color: #5CC4E5; margin-bottom: 20px; letter-spacing: -1.8px; }
      .cat-hero-subtitle { font-family: 'Inter', sans-serif; font-size: clamp(16px, 3vw, 24px); font-weight: 500; color: #FFFFFF; line-height: 1.6; margin-bottom: 40px; }
      .btn-black { display: inline-flex; align-items: center; justify-content: center; padding: 16px 50px; background: #000000; color: #FFFFFF; font-family: 'Orbitron', sans-serif; font-size: 18px; font-weight: 700; border-radius: 40px; border: 1px solid #000000; text-decoration: none; transition: all 0.3s; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4); }
      .btn-black:hover { background: #000000; border-color: #5CC4E5; color: #5CC4E5; transform: translateY(-2px); }
      .btn-white { display: inline-flex; align-items: center; justify-content: center; padding: 16px 50px; background: #FFFFFF; color: #000000; font-family: 'Orbitron', sans-serif; font-size: 18px; font-weight: 700; border-radius: 40px; border: 1px solid #FFFFFF; text-decoration: none; transition: all 0.3s; box-shadow: 0 10px 30px rgba(255, 255, 255, 0.1); }
      .btn-white:hover { background: #5CC4E5; border-color: #5CC4E5; color: #000000; transform: translateY(-2px); }
      .cat-section-title { text-align: center; font-family: 'Orbitron', sans-serif; font-size: clamp(24px, 4vw, 36px); font-weight: 700; color: #5CC4E5; margin-bottom: clamp(40px, 6vw, 60px); letter-spacing: 2px; }
      .cat-grid { position: relative; z-index: 2; width: 100%; display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; row-gap: 40px; }
      .cat-card { position: static; width: 100%; text-align: center; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 20px; padding: 40px 30px; transition: all 0.4s ease; }
      .cat-card:hover { background: rgba(255, 255, 255, 0.06); border-color: rgba(92, 196, 229, 0.4); transform: translateY(-5px); }
      .cat-card h2 { font-family: 'Orbitron', sans-serif; font-size: clamp(18px, 2.5vw, 28px); font-weight: 700; color: #5CC4E5; margin-bottom: 20px; letter-spacing: 2px; }
      .cat-card p { font-family: 'Inter', sans-serif; font-size: clamp(11px, 1.3vw, 13px); font-weight: 500; color: #FFFFFF; line-height: 1.7; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px; }
      .btn-acceder { font-family: 'Orbitron', sans-serif; font-size: 14px; font-weight: 700; color: #FFFFFF; text-decoration: none; background: #000000; padding: 12px 40px; border-radius: 30px; display: inline-block; transition: all 0.3s; border: 1px solid rgba(255, 255, 255, 0.2); }
      .btn-acceder:hover { background: #5CC4E5; color: #000000; border-color: #5CC4E5; }
      .reveal-cat { opacity: 0; transform: translateY(40px); transition: opacity 0.7s ease, transform 0.7s ease; }
      .reveal-cat.revealed { opacity: 1; transform: translateY(0); }
      .reveal-cat:nth-child(1) { transition-delay: 0.1s; }
      .reveal-cat:nth-child(2) { transition-delay: 0.2s; }
      .reveal-cat:nth-child(3) { transition-delay: 0.3s; }
      .reveal-cat:nth-child(4) { transition-delay: 0.4s; }
      .trust-title { text-align: center; font-family: 'Orbitron', sans-serif; font-size: clamp(24px, 4vw, 32px); font-weight: 700; color: #5CC4E5; margin-bottom: clamp(40px, 8vw, 80px); text-transform: uppercase; letter-spacing: 2px; }
      .trust-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20PX }
      .trust-item { text-align: center; display: flex; flex-direction: column; align-items: center; }
      .trust-item img { height: clamp(40px, 5vw, 50px); width: auto; margin-bottom: 25px; filter: brightness(0) invert(1); }
      .trust-item h3 { font-family: 'Inter', sans-serif; font-size: clamp(16px, 2vw, 20px); font-weight: 700; color: #FFFFFF; margin-bottom: 15px; }
      .trust-item p { font-family: 'Inter', sans-serif; font-size: 14px; color: #FFFFFF; line-height: 1.6; max-width: 250px; }
      .reveal-item { opacity: 0; transform: translateY(30px); transition: opacity 0.6s ease, transform 0.6s ease; }
      .reveal-item.revealed { opacity: 1; transform: translateY(0); }
      .reveal-item:nth-child(1) { transition-delay: 0.1s; }
      .reveal-item:nth-child(2) { transition-delay: 0.2s; }
      .reveal-item:nth-child(3) { transition-delay: 0.3s; }
      .reveal-item:nth-child(4) { transition-delay: 0.4s; }
      .reveal-step { opacity: 0; transform: translateY(30px); transition: opacity 0.6s ease, transform 0.6s ease; }
      .reveal-step.revealed { opacity: 1; transform: translateY(0); }
      .reveal-step:nth-child(1) { transition-delay: 0.1s; }
      .reveal-step:nth-child(2) { transition-delay: 0.3s; }
      .reveal-step:nth-child(3) { transition-delay: 0.5s; }
      .steps-title { text-align: center; font-family: 'Orbitron', sans-serif; font-size: clamp(24px, 4vw, 32px); font-weight: 700; color: #5CC4E5; margin-bottom: clamp(40px, 6vw, 60px); letter-spacing: 2px; }
      .step-intro-cards { display: flex; justify-content: center; gap: clamp(20px, 4vw, 40px); margin-bottom: 40px; }
      .step-intro-card { display: flex; flex-direction: column; align-items: center; gap: 10px; }
      .step-intro-card .step-num { font-family: 'Orbitron', sans-serif; font-size: clamp(36px, 5vw, 48px); font-weight: 700; color: #5CC4E5; }
      .step-intro-card .step-label { font-family: 'Inter', sans-serif; font-size: clamp(14px, 1.5vw, 16px); font-weight: 700; color: #FFFFFF; letter-spacing: 2px; }
      .steps-main-panel { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 30px; padding: clamp(24px, 4vw, 40px); }
      .steps-main-container { display: flex; gap: clamp(30px, 5vw, 60px); align-items: center; }
      .steps-left { flex: 1; }
      .robot-ready-title { font-family: 'Orbitron', sans-serif; font-size: clamp(24px, 4vw, 36px); font-weight: 700; color: #FFFFFF; margin-bottom: 30px; line-height: 1.3; }
      .steps-list { display: flex; flex-direction: column; gap: 15px; }
      .step-card { background: #000000; padding: 20px 25px; border-radius: 20px; font-family: 'Inter', sans-serif; font-size: 16px; color: #FFFFFF; line-height: 1.5; border-left: 4px solid #5CC4E5; }
      .steps-right { flex: 1; display: flex; justify-content: center; }
      .steps-image { max-width: 100%; height: auto; }
      .btn-commander-large { display: inline-flex; align-items: center; justify-content: center; padding: 20px 80px; background: #5CC4E5; color: #000000; font-family: 'Orbitron', sans-serif; font-size: 20px; font-weight: 700; border-radius: 40px; border: none; text-decoration: none; transition: all 0.3s; }
      .btn-commander-large:hover { box-shadow: 0 10px 40px rgba(92, 196, 229, 0.4); transform: translateY(-2px); }
      @media (max-width: 768px) {
        
        .trust-grid { grid-template-columns: repeat(2, 1fr); }
        .flower-container { height: auto; padding: 40px 20px; }
        .cat-grid { grid-template-columns: 1fr; gap: 20px; }
        .cat-card { position: static; transform: none !important; }
        .steps-main-container { flex-direction: column; }
      }
    </style>
    <link rel="stylesheet" href="assets/css/responsive.css" />
    <link rel="stylesheet" href="assets/css/layout-refresh.css" />
    <link rel="stylesheet" href="assets/css/nav-footer.css" />
</head>
  <body class="categorie-page">
<?php include 'includes/nav.php'; ?>


    <!-- Mobile Bottom Navigation -->
    </div>

    <!-- ====== HERO CATEGORIE ====== -->
    <section class="hero-section -mt-20" id="accueil">
      <section class="categorie-hero">
        <div class="container mx-auto px-4 sm:px-6">
          <div class="categorie-hero-text max-w-[900px] mx-auto">
            <h1 class="cat-hero-title">Apprenez en vous amusant à la maison</h1>
            <p class="cat-hero-subtitle">
              Apprentissage interactif pour développer la créativité et les compétences des enfants.
            </p>
            <div class="cat-hero-actions flex gap-4 sm:gap-[30px] justify-center mt-6 sm:mt-10">
                 <a href="commander.php" class="btn-commander-large inline-block">Commander</a>
            </div>
          </div>
        </div>
      </section>
    </section>

    <!-- ====== CATEGORY LIST ====== -->
    <section class="py-[50px] sm:py-[70px] lg:py-[100px] bg-black" id="decouvrir">
      <div class="container mx-auto px-4 sm:px-6 md:px-16 lg:px-24">
        <h2 class="cat-section-title">Nos catégories</h2>
<div class="cat-grid">
            <?php if (empty($categories)): ?>
            <div class="cat-card reveal-cat">
               <div class="cat-card-content">
                  <h2>AUCUNE CATÉGORIE</h2>
                  <p>Aucune catégorie disponible pour le moment.</p>
                  <a href="produit.php" class="btn-acceder">Voir tous les produits</a>
               </div>
            </div>
            <?php else: ?>
            <?php foreach ($categories as $index => $category): ?>
            <div class="cat-card reveal-cat" style="transition-delay: <?= $index * 0.1 ?>s">
               <div class="cat-card-content">
                  <h2><?= strtoupper(htmlspecialchars($category['nom'])) ?></h2>
                  <p><?= htmlspecialchars($category['description'] ?? 'Découvrez nos produits dans cette catégorie') ?></p>
                  <a href="produit.php?categorie=<?= $category['id'] ?>" class="btn-acceder">Accéder</a>
               </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
         </div>
      </div>
    </section>

    <!-- ====== POURQUOI NOUS FAIRE CONFIANCE ====== -->
    <section class="py-[50px] sm:py-[60px] lg:py-[70px] bg-black">
      <div class="container mx-auto px-4 sm:px-6 md:px-16 lg:px-24">
         <h2 class="trust-title">Pourquoi nous faire confiance ?</h2>
         <div class="trust-grid">
            <div class="trust-item reveal-item">
               <img src="assets/images/categories/secure.png" alt="Sécurisé">
               <h3>Sécurisé</h3>
               <p>Toutes vos données sont cryptées et protégées contre tout accès non autorisé</p>
            </div>
            <div class="trust-item reveal-item">
               <img src="assets/images/categories/protect.png" alt="Protection">
               <h3>Protection</h3>
               <p>Conçu pour un usage sûr, avec des mesures de sécurité intégrées pour les enfants</p>
            </div>
            <div class="trust-item reveal-item">
               <img src="assets/images/categories/offline.png" alt="Offline">
               <h3>Offline</h3>
               <p>Travaille hors ligne, pour un usage sûr et constant partout.</p>
            </div>
            <div class="trust-item reveal-item">
               <img src="assets/images/categories/intelligence.png" alt="Intelligence">
               <h3>Intelligence</h3>
               <p>Nos robots intègrent des IA avancées pour s'adapter à votre environnement.</p>
            </div>
         </div>
      </div>
    </section>

    <!-- ====== 3 STEPS SECTION ====== -->
    <section class="py-[40px] sm:py-[60px] lg:py-[80px] bg-black" id="etapes">
       <div class="container mx-auto px-4 sm:px-6 md:px-16 lg:px-24">
          <h2 class="steps-title">VOTRE ROBOT EN 3 ÉTAPES SIMPLES</h2>
          
          <div class="step-intro-cards">
             <div class="step-intro-card reveal-step">
                <span class="step-num">1</span>
                <span class="step-label">CHOIX</span>
             </div>
             <div class="step-intro-card reveal-step">
                <span class="step-num">2</span>
                <span class="step-label">VALIDATION</span>
             </div>
             <div class="step-intro-card reveal-step">
                <span class="step-num">3</span>
                <span class="step-label">COMMANDER</span>
             </div>
          </div>

          
             <div class="steps-main-container flex-col lg:flex-row">
                <div class="steps-left w-full lg:w-1/2">
                   <h3 class="robot-ready-title">ROBOT PRÊT <br>EN QUELQUES CLICS</h3>
                   <div class="steps-list">
                      <div class="step-card">
                         Sélectionnez la catégorie adaptée à vos besoins pour trouver le robot idéal.
                      </div>
                      <div class="step-card">
                         Vérifiez votre sélection et confirmez que le robot choisi correspond à vos attentes.
                      </div>
                      <div class="step-card">
                         Remplissez le formulaire avec vos informations et envoyez votre commande facilement
                      </div>
                   </div>
                </div>
                <div class="steps-right w-full lg:w-1/2 flex justify-center">
                   <div class="steps-image-container">
                      <img src="assets/images/categories/delivery.png" alt="Delivery illustration" class="steps-image max-w-full">
                   </div>
                </div>
             </div>
          </div>
       </div>
    </section>

    <!-- ====== COMMANDER CTA ====== -->
    <section class="py-[40px] sm:py-[50px] bg-black text-center" id="commander">
       <div class="container mx-auto px-4 sm:px-6">
          <a href="commander.php" class="btn-commander-large inline-block">Commander</a>
       </div>
    </section>

    <!-- ====== FOOTER ====== -->
    
    <!-- Mobile bottom padding for fixed bottom nav -->
    <div class="lg:hidden" style="height: 60px"></div>

    <!-- Add padding top for fixed navbar on desktop -->
    <div class="hidden lg:block" style="height: 40px"></div>

    <script src="assets/js/main.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.classList.add('revealed');
            }
          });
        }, { threshold: 0.2 });
        document.querySelectorAll('.reveal-item, .reveal-cat, .reveal-step').forEach(el => observer.observe(el));
      });
    </script>
  <?php include 'includes/footer.php'; ?>
<script src="assets/js/nav.js"></script>
</body>
</html>
