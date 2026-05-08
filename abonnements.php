<?php
require_once 'includes/pdo.php';

$plans = [];
$stmt = $pdo->query("SELECT * FROM subscription_plans WHERE statut = 'actif' ORDER BY prix ASC");
$plans = $stmt->fetchAll();

function formatPrice($price) {
    return number_format((float) $price, 2, ',', ' ') . ' TND';
}
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="RDOC - Plans d'abonnement pour robots intelligents" />
    <title>RDOC - Abonnements</title>
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
      
      .hero-section { position: relative; height: 500px; }
      .abonnement-hero { 
        position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; 
        text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;
        background: linear-gradient(180deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.9) 100%);
      }
      
      .hero-title { font-family: 'Orbitron', sans-serif; font-size: clamp(28px, 5vw, 48px); font-weight: 700; color: #5CC4E5; margin-bottom: 20px; letter-spacing: -1px; }
      .hero-subtitle { font-family: 'Inter', sans-serif; font-size: clamp(16px, 3vw, 20px); font-weight: 400; color: #FFFFFF; line-height: 1.6; margin-bottom: 30px; max-width: 600px; }
      
      .plan-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 20px;
        padding: 40px 30px;
        transition: all 0.4s ease;
        text-align: center;
        height: 100%;
        display: flex;
        flex-direction: column;
      }
      .plan-card:hover {
        background: rgba(255, 255, 255, 0.06);
        border-color: rgba(92, 196, 229, 0.4);
        transform: translateY(-5px);
      }
      
      .plan-name { font-family: 'Orbitron', sans-serif; font-size: clamp(20px, 3vw, 28px); font-weight: 700; color: #5CC4E5; margin-bottom: 15px; letter-spacing: 2px; }
      .plan-price { font-family: 'Orbitron', sans-serif; font-size: clamp(32px, 4vw, 48px); font-weight: 700; color: #FFFFFF; margin-bottom: 10px; }
      .plan-price span { font-size: 16px; color: #FFFFFF80; }
      .plan-duration { font-family: 'Inter', sans-serif; font-size: 14px; color: #FFFFFF60; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px; }
      .plan-description { font-family: 'Inter', sans-serif; font-size: 14px; color: #FFFFFF80; line-height: 1.7; margin-bottom: 25px; flex-grow: 1; }
      .plan-features { text-align: left; margin-bottom: 25px; }
      .plan-feature { font-family: 'Inter', sans-serif; font-size: 13px; color: #FFFFFF70; margin-bottom: 10px; display: flex; align-items: center; gap: 10px; }
      .plan-feature::before { content: "✓"; color: #5CC4E5; font-weight: bold; }
      
      .plan-btn {
        font-family: 'Orbitron', sans-serif; font-size: 14px; font-weight: 700;
        color: #000000; text-decoration: none; background: #5CC4E5;
        padding: 14px 40px; border-radius: 30px; display: inline-block;
        transition: all 0.3s; border: none; cursor: pointer; width: 100%;
      }
      .plan-btn:hover { background: #FFFFFF; transform: translateY(-2px); }
      
      .plans-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; }
      
      .trust-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
      .trust-item { text-align: center; display: flex; flex-direction: column; align-items: center; }
      .trust-item img { height: clamp(40px, 5vw, 50px); width: auto; margin-bottom: 25px; filter: brightness(0) invert(1); }
      .trust-item h3 { font-family: 'Inter', sans-serif; font-size: clamp(16px, 2vw, 20px); font-weight: 700; color: #FFFFFF; margin-bottom: 15px; }
      .trust-item p { font-family: 'Inter', sans-serif; font-size: 14px; color: #FFFFFF; line-height: 1.6; max-width: 250px; }
      
      @media (max-width: 768px) {
        .trust-grid { grid-template-columns: repeat(2, 1fr); }
        .plans-grid { grid-template-columns: 1fr; }
      }
    </style>
    <link rel="stylesheet" href="assets/css/responsive.css" />
    <link rel="stylesheet" href="assets/css/layout-refresh.css" />
    <link rel="stylesheet" href="assets/css/nav-footer.css" />
  </head>
  <body>
<?php include 'includes/nav.php'; ?>

    <!-- Mobile Bottom Navigation -->
    </div>

    <!-- ====== HERO SECTION ====== -->
    <section class="hero-section -mt-20" id="accueil">
      <section class="abonnement-hero">
        <div class="container mx-auto px-4 sm:px-6">
          <div class="max-w-[900px] mx-auto">
            <h1 class="hero-title">Nos Plans d'Abonnement</h1>
            <p class="hero-subtitle">
              Choisissez le plan qui correspond le mieux à vos besoins. Accédez à nos robots intelligents avec des options flexibles.
            </p>
          </div>
        </div>
      </section>
    </section>

    <!-- ====== PLANS LIST ====== -->
    <section class="py-[50px] sm:py-[70px] lg:py-[100px] bg-black" id="plans">
      <div class="container mx-auto px-4 sm:px-6 md:px-16 lg:px-24">
        <h2 class="text-center font-orbitron text-[28px] sm:text-[36px] text-primary font-bold mb-[40px] sm:mb-[60px]">Choisissez votre plan</h2>
        
        <?php if (empty($plans)): ?>
        <div class="text-center text-white/60 py-20">
          <p class="text-xl">Aucun plan d'abonnement disponible pour le moment.</p>
          <a href="produit.php" class="inline-block mt-6 text-primary hover:underline">Voir nos produits</a>
        </div>
        <?php else: ?>
        <div class="plans-grid">
          <?php foreach ($plans as $index => $plan): ?>
          <div class="plan-card" style="animation-delay: <?= $index * 0.1 ?>s">
            <h3 class="plan-name"><?= htmlspecialchars($plan['nom']) ?></h3>
            <div class="plan-price"><?= formatPrice($plan['prix']) ?><span>/<?= $plan['duree'] === 'annee' ? 'an' : 'mois' ?></span></div>
            <div class="plan-duration"><?= $plan['duree'] === 'annee' ? 'Facturation annuelle' : 'Facturation mensuelle' ?></div>
            <p class="plan-description"><?= htmlspecialchars($plan['description'] ?? 'Plan d\'abonnement RDOC') ?></p>
            
            <div class="plan-features">
              <div class="plan-feature"><?= $plan['nombre_produits'] ?: 'Illimité' ?> produits</div>
              <div class="plan-feature"><?= $plan['nombre_categories'] ?: 'Illimité' ?> catégories</div>
            </div>
            
            <a href="commander.php?plan=<?= $plan['id'] ?>" class="plan-btn">Souscrire maintenant</a>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- ====== POURQUOI NOUS FAIRE CONFIANCE ====== -->
    <section class="py-[50px] sm:py-[60px] lg:py-[70px] bg-black">
      <div class="container mx-auto px-4 sm:px-6 md:px-16 lg:px-24">
         <h2 class="text-center font-orbitron text-[24px] sm:text-[32px] text-primary font-bold mb-[40px] sm:mb-[80px] uppercase tracking-wide">Pourquoi nous faire confiance ?</h2>
         <div class="trust-grid">
            <div class="trust-item">
               <img src="assets/images/categories/secure.png" alt="Sécurisé">
               <h3>Sécurisé</h3>
               <p>Toutes vos données sont cryptées et protégées contre tout accès non autorisé</p>
            </div>
            <div class="trust-item">
               <img src="assets/images/categories/protect.png" alt="Protection">
               <h3>Protection</h3>
               <p>Conçu pour un usage sûr, avec des mesures de sécurité intégrées pour les enfants</p>
            </div>
            <div class="trust-item">
               <img src="assets/images/categories/support.png" alt="Support">
               <h3>Support 24/7</h3>
               <p>Une équipe d'assistance disponible à tout moment pour vous aider</p>
            </div>
            <div class="trust-item">
               <img src="assets/images/categories/fast.png" alt="Rapide">
               <h3>Livraison rapide</h3>
               <p>Recevez votre robot rapidement avec notre service de livraison express</p>
            </div>
         </div>
      </div>
    </section>

    <!-- ====== COMMANDER CTA ====== -->
    <section class="py-[40px] sm:py-[50px] bg-black text-center" id="commander">
       <div class="container mx-auto px-4 sm:px-6">
          <a href="commander.php" class="inline-block bg-primary text-black font-orbitron font-bold text-[18px] sm:text-[20px] px-[60px] py-[20px] rounded-[40px] hover:shadow-[0_10px_40px_rgba(92,196,229,0.4)] hover:translate-y-[-2px] transition-all">Commander</a>
       </div>
    </section>

    <!-- ====== FOOTER ====== -->
    
    <!-- Mobile bottom padding for fixed bottom nav -->
    <div class="lg:hidden" style="height: 60px"></div>

    <!-- Add padding top for fixed navbar on desktop -->
    <div class="hidden lg:block" style="height: 40px"></div>

    <script src="assets/js/main.js"></script>
  <?php include 'includes/footer.php'; ?>
<script src="assets/js/nav.js"></script>
</body>
</html>