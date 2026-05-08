<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="RDOC Informative - L'intelligence au service de vos besoins avec nos solutions robotiques.">
    <title>RDOC - Localistaive</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = { theme: { extend: { colors: { primary: '#5CC4E5' }, fontFamily: { 'orbitron': ['Orbitron', 'sans-serif'], 'inter': ['Inter', 'sans-serif'] } } } }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
      body { background-color: #000000; }
      .logo-img { height: 38px; }
      .auth-button { display: inline-flex; align-items: center; justify-content: center; padding: 6px 23px; height: 40px; border-radius: 18px; border: 1px solid transparent; background-image: linear-gradient(#000, #000), linear-gradient(135deg, #FFFFFF, #5CC4E5); background-origin: border-box; background-clip: padding-box, border-box; font-family: 'Inter', sans-serif; font-size: 16px; font-weight: 500; color: #FFFFFF; text-decoration: none; }
      
      
      
      
.informative-hero {
  position: relative;
  min-height: 500px;
  display: flex;
  align-items: center;
  justify-content: center;
}      .hero-bg-img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; filter: blur(7.6px); }
      .hero-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.95)); }
      .hero-title { font-family: 'Orbitron', sans-serif; font-size: 48px; font-weight: 700; color: #5CC4E5; margin-bottom: 20px; }
      .hero-subtitle { font-family: 'Inter', sans-serif; font-size: 24px; font-weight: 500; color: #FFFFFF; line-height: 1.6; margin-bottom: 40px; }
      .btn-primary { display: inline-flex; padding: 16px 50px; background: #5CC4E5; color: #000000; font-family: 'Orbitron', sans-serif; font-size: 18px; font-weight: 700; border-radius: 40px; border: none; text-decoration: none; }
      .section-badge { display: inline-block; padding: 8px 24px; background: rgba(92, 196, 229, 0.1); border: 1px solid #5CC4E5; border-radius: 50px; color: #5CC4E5; font-family: 'Orbitron', sans-serif; font-size: 14px; font-weight: 700; margin-bottom: 20px; }
      .section-main-title { font-family: 'Orbitron', sans-serif; font-size: 40px; font-weight: 700; color: #FFFFFF; margin-bottom: 60px; }
      .solution-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; max-width: 900px; margin: 0 auto 50px; }
      .solution-card { border: 1px solid rgba(255,255,255,0.2); border-radius: 20px; overflow: hidden; }
      .card-image { height: 250px; }
      .card-image img { width: 100%; height: 100%; object-fit: cover; }
      .card-info { padding: 25px; text-align: center; background: rgba(255,255,255,0.02); }
      .card-info h3 { font-family: 'Orbitron', sans-serif; font-size: 18px; color: #5CC4E5; margin-bottom: 10px; }
      .card-info p { font-family: 'Inter', sans-serif; font-size: 14px; color: #FFFFFF; }
      .btn-discover-more { display: inline-flex; padding: 14px 40px; background: transparent; border: 1px solid #5CC4E5; border-radius: 50px; color: #5CC4E5; font-family: 'Orbitron', sans-serif; font-size: 16px; font-weight: 700; text-decoration: none; }
      .video-wrapper { position: relative; border-radius: 20px; overflow: hidden; }
      .play-button { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 80px; height: 80px; background: rgba(92, 196, 229, 0.9); border-radius: 50%; display: flex; align-items: center; justify-content: center; }
      .sensors-title { font-family: 'Orbitron', sans-serif; font-size: 36px; font-weight: 700; color: #5CC4E5; margin-bottom: 10px; text-align: center; }
      .sensors-subtitle { font-family: 'Inter', sans-serif; font-size: 20px; color: #FFFFFF; margin-bottom: 60px; text-align: center; }
      .sensor-item { 
  display: flex;
  align-items: center;
  width: 100%;
  gap: 20px;
  margin-bottom: 30px;
}
      .sensor-text h4 { font-family: 'Orbitron', black; font-size: 25px; color: #5CC4E5; margin-bottom: 5px;text-align:center; }
      .sensor-text p { font-family: 'Inter', bold; font-size: 18px; color: #FFFFFF; text-align:center; }
      .sensor-center { display: flex; justify-content: center; }
      .sensor-robot { max-width: 400px; }
      .pricing-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 30px; max-width: 900px; margin: 0 auto; }
      .pricing-card { border: 1px solid rgba(255,255,255,0.2); border-radius: 20px; padding: 40px 30px; text-align: center; }
      .pricing-card.highlighted { border-color: #5CC4E5; background: rgba(92, 196, 229, 0.05); }
      .pricing-card h3 { font-family: 'Orbitron', sans-serif; font-size: 20px; color: #FFFFFF; margin-bottom: 15px; }
      .pricing-card .price { font-family: 'Orbitron', sans-serif; font-size: 20px; color: #5CC4E5; margin-bottom: 25px; }
      .pricing-card .features { list-style: none; margin-bottom: 30px; }
      .pricing-card .features li { font-family: 'Inter', sans-serif; font-size: 14px; color: #FFFFFF; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.1); }
      .btn-pack { display: inline-flex; padding: 14px 30px; border-radius: 50px; font-family: 'Orbitron', sans-serif; font-size: 14px; font-weight: 700; text-decoration: none; }
      .btn-outline { border: 1px solid #5CC4E5; color: #5CC4E5; background: transparent; }
      .btn-solid { background: #5CC4E5; color: #000000; border: 1px solid #5CC4E5; }
      .testimonials-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px; margin-bottom: 50px; }
      .testimonial-card { background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 30px; text-align: center; }
      .feedback-avatar { width: 80px; height: 80px; border-radius: 50%; margin: 0 auto 20px; object-fit: cover; }
      .testimonial-card h4 { font-family: 'Orbitron', sans-serif; font-size: 16px; color: #FFFFFF; margin-bottom: 10px; }
      .testimonial-card p { font-family: 'Inter', sans-serif; font-size: 14px; color: rgba(255,255,255,0.7); }
      .btn-more-feedback { display: inline-flex; padding: 14px 40px; background: transparent; border: 1px solid rgba(255,255,255,0.3); border-radius: 50px; color: #FFFFFF; font-family: 'Orbitron', sans-serif; font-size: 14px; font-weight: 700; text-decoration: none; }
      .btn-commander-large { display: inline-flex; padding: 20px 80px; background: #5CC4E5; color: #000000; font-family: 'Orbitron', sans-serif; font-size: 24px; font-weight: 700; border-radius: 50px; border: none; text-decoration: none; }
      .feedback-form-wrapper { max-width: 600px; margin: 0 auto; padding: 40px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; text-align: center; }
      .feedback-form-wrapper h3 { font-family: 'Orbitron', sans-serif; font-size: 24px; color: #5CC4E5; margin-bottom: 10px; }
      .feedback-form-wrapper p { font-family: 'Inter', sans-serif; font-size: 16px; color: #FFFFFF; margin-bottom: 20px; }
      .star-rating { margin-bottom: 30px; }
      .star-rating span { font-size: 32px; color: #5CC4E5; cursor: pointer; }
      .feedback-form { display: flex; flex-direction: column; gap: 15px; }
      .form-group { text-align: left; }
      .form-group label { display: block; font-family: 'Inter', sans-serif; font-size: 14px; color: #FFFFFF; margin-bottom: 8px; }
      .form-group input, .form-group textarea { width: 100%; padding: 14px 20px; background: transparent; border: 1px solid rgba(255,255,255,0.2); border-radius: 12px; color: #FFFFFF; font-family: 'Inter', sans-serif; font-size: 14px; outline: none; }
      .form-group input::placeholder, .form-group textarea::placeholder { color: rgba(255,255,255,0.5); }
      .form-group input:focus, .form-group textarea:focus { border-color: #5CC4E5; }
      .btn-submit { width: 100%; padding: 16px; background: #5CC4E5; border: none; border-radius: 30px; color: #000000; font-family: 'Orbitron', sans-serif; font-size: 16px; font-weight: 700; cursor: pointer; }
      @media (max-width: 1024px) {
        
      }
      @media (max-width: 768px) {
        .solution-grid,
        .pricing-grid,
        .testimonials-grid {
          grid-template-columns: 1fr;
        }
        .hero-title { font-size: 32px; }
        .hero-subtitle { font-size: 18px; }
      }
    </style>
    <link rel="stylesheet" href="assets/css/responsive.css" />
    <link rel="stylesheet" href="assets/css/layout-refresh.css" />
  <link rel="stylesheet" href="assets/css/nav-footer.css" />
</head>
<body class="informative-page">
<?php include 'includes/nav.php'; ?>

   
    <!-- Mobile Bottom Navigation -->
    </div>
    <div class="lg:hidden" style="height: 70px;"></div>

    <!-- Mobile bottom padding for fixed bottom nav -->
    <div class="lg:hidden" style="height: 80px"></div>

   <!-- HERO -->
<section class="categorie-hero relative overflow-hidden min-h-screen flex items-center">

    <!-- Background Video -->
    <video 
        autoplay 
        muted 
        loop 
        playsinline
        class="absolute inset-0 w-full h-full object-cover"
    >
        <source src="assets/images/localisative/videolocalisative.mp4" type="video/mp4">
    </video>

    <!-- Overlay (باش النص يبان واضح) -->
    <div class="absolute inset-0 bg-black/50"></div>

    <!-- Content -->
    <div class="relative z-10 w-full">
        <div class="container mx-auto px-4 sm:px-6 text-center">

            <div class="categorie-hero-text max-w-[900px] mx-auto">

                <h1 class="cat-hero-title">
                    L'intelligence au service de vos besoins
                </h1>

                <p class="cat-hero-subtitle">
                    Obtenez des informations en temps réel et partout où vous souhaitez par un robot grâce à son support interactif et sa vaste précision.
                </p>

                <div class="cat-hero-actions flex gap-4 sm:gap-[30px] justify-center mt-6 sm:mt-10">
                    <a href="commander.php" class="btn-black text-sm sm:text-base px-6 sm:px-8 py-3 sm:py-4">
                        Commander
                    </a>
                </div>

            </div>

        </div>
    </div>

</section>


    <!-- SOLUTION -->
    <section class="py-16 sm:py-20 md:py-24 lg:py-[100px]">
        <div class="container mx-auto px-6">
            <div class="text-center mb-8 sm:mb-10 md:mb-12 lg:mb-[60px]">
                <h2 class="section-main-title">Une solution localisative sans limites</h2>
            </div>
            <div class="solution-grid grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8">
                <div class="solution-card"><div class="card-image h-40 md:h-60 lg:h-[250px]"><img src="assets/images/informative/Gemini_Generated_Image_eb0tv6eb0tv6eb0t 3.png" alt="Robot"></div><div class="card-info p-4 md:p-6"><h3> LOCALISATION INTELLIGENTE</h3><p>Détecte et identifie les zones en temps réel</p></div></div>
                <div class="solution-card"><div class="card-image h-40 md:h-60 lg:h-[250px]"><img src="assets/images/localisative/locali.png" alt="Infos"></div><div class="card-info p-4 md:p-6"><h3>Infos utiles</h3><p>Informations claires selon votre position</p></div></div>
            </div>
            <div class="text-center"><a href="produit-aisar.php" class="btn-discover-more">Découvrir</a></div>
        </div>
    </section>

  

    <!-- CAPTEURS -->
   <section class="py-16 sm:py-20 md:py-24 lg:py-[100px]">
    <div class="container mx-auto px-6">

        <h2 class="sensors-title">CAPTEURS INTELLIGENTS</h2>
        <p class="sensors-subtitle">comprendre la voix et l'environnement d'apprentissage</p>

        <!-- GRID -->
        <div class="grid grid-cols-1 lg:grid-cols-3 items-center gap-10">

            <!-- LEFT -->
           <div class="flex flex-col gap-6 pl-36">
                
                <div class="sensor-item">
                    
                    <div class="sensor-text">
                        <h4>CAPTURE VOCALE</h4>
                        <p>Analyse des fréquences vocales</p>
                    </div>
                  
                </div>

                <div class="sensor-item">
                   
                    <div class="sensor-text">
                        <h4>INFO INTELLIGENTE</h4>
                        <p>Réponses claires et rapides</p>
                    </div>
                  
                </div>

                <div class="sensor-item">
                    
                    <div class="sensor-text">
                        <h4>COMMANDE EN LIGNE</h4>
                        <p>Actions instantanées</p>
                    </div>
                    
                </div>

            </div>

            <!-- CENTER (ROBOT) -->
            <div class="flex justify-center">
                <img 
                    src="assets/images/educative/aisarcapteur.png"
                    alt="Robot"
                    class="sensor-robot"
                >
            </div>

            <!-- RIGHT -->
            <div class="flex flex-col gap-6 items-end text-right pr-56">

                <div class="sensor-item flex-row-reverse">
                   
                    <div class="sensor-text" >
                        <h4>PROJECTION</h4>
                        <p>Projection interactive</p>
                    </div>
                   
                </div>

                <div class="sensor-item flex-row-reverse">
                   
                    <div class="sensor-text">
                        <h4>Q & R</h4>
                        <p>Réponses intelligentes</p>
                    </div>
                    
                </div>

                <div class="sensor-item flex-row-reverse">
                    
                    <div class="sensor-text">
                        <h4>ASSISTANT</h4>
                        <p>Disponible 24/7</p>
                    </div>
                    
                </div>

            </div>

        </div>
    </div>
</section>

    <!-- PACKS -->
    <section class="py-16 sm:py-20 md:py-24 lg:py-[100px] relative -mt-24 z-20">
        <div class="container mx-auto px-6">
            <h2 class="sensors-title">NOS PACKS</h2>
            <div class="pricing-grid grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-8">
                <div class="pricing-card p-4 md:p-8"><h3>ESSAI Gratuit</h3><p class="price">Venez tester notre robot</p><ul class="features text-left"><li>Vérifier le robot</li><li>Découvrir ses fonctionnalités</li><li>Expérience guidée</li><li>Poser vos questions</li><li>Tester l'écran interactif</li></ul><a href="contact.php" class="btn-pack btn-outline">Essai Gratuit</a></div>
                <div class="pricing-card highlighted p-4 md:p-8"><h3>ACHAT ROBOT</h3><p class="price">Obtenez un robot </p><ul class="features text-left"><li>Propriété de l'acheteur</li><li>Tout accés a vos besoins</li><li>24/7 de maintenance</li><li>Soutien d'experts dédié</li><li>Mises à jour de sécurité</li></ul><a href="commander.php" class="btn-pack btn-solid" >Achat Robot</a></div>
            </div>
        </div>
    </section>

    <!-- AVIS -->
    <section class="py-16 sm:py-20 md:py-24 lg:py-[100px] pb-8">
        <div class="container mx-auto px-6">
            <h2 class="sensors-title">AVIS CLIENTS</h2>
            <div class="testimonials-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 md:gap-8 mt-8 md:mt-12">
                <div class="testimonial-card p-4 md:p-6"><img src="assets/profil 1.png" alt="Amira" class="feedback-avatar w-16 h-16 md:w-20 md:h-20"><h4>Amira Ben Salah</h4><p>un robot très précieux aide énormément pour mieux vous servir.</p></div>
                <div class="testimonial-card p-4 md:p-6"><img src="assets/profil 2.png" alt="Lina" class="feedback-avatar w-16 h-16 md:w-20 md:h-20"><h4>Lina Mhiri</h4><p>très pratique et facile à utiliser, je recommande !</p></div>
                <div class="testimonial-card p-4 md:p-6"><img src="assets/profil 3.png" alt="Sofien" class="feedback-avatar w-16 h-16 md:w-20 md:h-20"><h4>Sofien Trabelsi</h4><p>service de haute qualité.</p></div>
            </div>
            <div class="text-center"><a href="avis.php" class="btn-more-feedback">Lire plus</a></div>
        </div>
    </section>

    

   
    <script src="assets/js/main.js"></script>
<?php include 'includes/footer.php'; ?>
<script src="assets/js/nav.js"></script>
</body>
</html>

