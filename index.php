<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RDOC</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400&family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/layout-refresh.css">
    <link rel="stylesheet" href="assets/css/index.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lenis@1.1.13/dist/lenis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    </head>
<body>
<?php include 'includes/nav.php'; ?>





<section class="relative w-full min-h-screen pt-20">
        <div class="absolute inset-0 overflow-hidden">
            <video class="absolute inset-0 w-full h-full object-cover" autoplay loop muted playsinline>
                <source src="assets/home.mp4" type="video/mp4">
            </video>
        </div>
    </section>

    <section id="about-pinned" class="min-h-screen flex flex-col md:flex-row items-center justify-center gap-2 md:gap-4 px-4 md:px-8">
        <div class="flex items-center gap-4 md:w-[44%]">
            <div class="flex items-stretch h-42 md:h-54">
                <div class="w-1 bg-[#5CC4E5]"></div>
                <h2 class="text-[#5CC4E5] text-5xl md:text-7xl lg:text-8xl font-bold leading-tight ml-4 whitespace-nowrap">
                    À propos<br>de nous
                </h2>
            </div>
        </div>
        
        <div class="about-content md:w-1/2 max-w-sm md:-ml-2">
            <div class="about-step" data-step="0">
                <h3 class="text-[#5CC4E5] text-xl md:text-2xl lg:text-3xl font-bold mb-2 text-left">
                    Innovation & Robotique
                </h3>
                <p class="text-gray-300 text-lg md:text-xl lg:text-2xl font-normal leading-relaxed text-left" style="font-family: 'Inter', sans-serif;">
                    Rdoc conçoit et commercialise des robots intelligents dédiés à l'éducation et aux solutions technologiques avancées.
                </p>
            </div>
            
            <div class="about-step hidden" data-step="1">
                <h3 class="text-[#5CC4E5] text-xl md:text-2xl lg:text-3xl font-bold mb-2 text-left">
                    Analyse & Localisation
                </h3>
                <p class="text-gray-300 text-lg md:text-xl lg:text-2xl font-normal leading-relaxed text-left" style="font-family: 'Inter', sans-serif;">
                    Nous développons des systèmes de localisation, d'analyse et de traitement de données pour améliorer la prise de décision et l'efficacité opérationnelle.
                </p>
            </div>
            
            <div class="about-step hidden" data-step="2">
                <h3 class="text-[#5CC4E5] text-xl md:text-2xl lg:text-3xl font-bold mb-2 text-left">
                    Formation & Digitalisation
                </h3>
                <p class="text-gray-300 text-lg md:text-xl lg:text-2xl font-normal leading-relaxed text-left" style="font-family: 'Inter', sans-serif;">
                    En tant qu'académie digitale, nous formons aux compétences numériques, à la programmation et à la gestion des technologies modernes.
                </p>
            </div>
        </div>
    </section>

    <section class="flex flex-col md:flex-row items-center md:items-center justify-center gap-8 md:gap-16 px-8 md:px-16 pb-8">
        <div class="flex items-center gap-6">
            <div class="flex items-stretch h-70">
                <div class="w-1 bg-[#5CC4E5]"></div>
                <h2 class="text-[#5CC4E5] text-5xl md:text-7xl font-bold leading-none ml-6" style="line-height: 1;">
                    Pourquoi<br>nous<br>choisir ?
                </h2>
            </div>
        </div>

        <div class="max-w-lg md:ml-24">
            <p class="text-gray-300 text-lg md:text-xl font-normal leading-relaxed text-left" style="font-family: 'Inter', sans-serif;">
                Rdoc propose des solutions innovantes en robotique, analyse et digitalisation. Nous allions technologie et expertise pour offrir des outils fiables, efficaces et adaptés aux besoins modernes.
            </p>
        </div>
    </section>

   

    <section class="horizontal-carousel-section" id="horizontal-carousel">
        <div class="carousel-viewport">
            <div class="carousel-track" id="carousel-track-inner">
                <div class="career-card" data-index="0">
                    <span class="career-number">01</span>
                    <span class="career-title">Solutions Robotiques Performantes</span>
                </div>
                <div class="career-card" data-index="1">
                    <span class="career-number">02</span>
                    <span class="career-title">Innovation Et Expertise Technologique</span>
                </div>
                <div class="career-card" data-index="2">
                    <span class="career-number">03</span>
                    <span class="career-title">Outils Innovants</span>
                </div>
                <div class="career-card" data-index="3">
                    <span class="career-number">04</span>
                    <span class="career-title">Accompagnement Et Support</span>
                </div>
                <div class="career-card" data-index="4">
                    <span class="career-number">05</span>
                    <span class="career-title">Transformation Digitale</span>
                </div>
            </div>
        </div>
    </section>


    <section class="flex items-center justify-center py-0">
        <h2 class="text-[#5CC4E5] text-6xl md:text-8xl lg:text-[100px] font-bold text-center" style="font-family: 'Orbitron', sans-serif;">
            Performance Réelle
        </h2>
    </section>

    <section class="carousel-performance-section">
        <div class="carousel-performance-viewport" id="performance-viewport">
            <div class="carousel-performance-track" id="performance-track">
                <div class="performance-card" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5)), url('assets/image 1.png'); background-size: cover; background-position: center;">
                    <h3 class="performance-title" style="font-family: 'Inter', sans-serif;">Autonomie avancée</h3>
                    <p class="performance-text" style="font-family: 'Inter', sans-serif;">Nos robots sont conçus pour fonctionner dans des environnements réels, avec une autonomie avancée et sans dépendre d'un contrôle humain constant.</p>
                </div>
                <div class="performance-card performance-card-glass">
                    <h3 class="performance-title" style="font-family: 'Inter', sans-serif;">Sécurité fiable</h3>
                    <p class="performance-text" style="font-family: 'Inter', sans-serif;">Grâce à une structure stable et une conception intelligente, ils garantissent une utilisation sûre et fiable.</p>
                </div>
                <div class="performance-card" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5)), url('assets/image 2.png'); background-size: cover; background-position: center;">
                    <h3 class="performance-title" style="font-family: 'Inter', sans-serif;">Adaptabilité intelligente</h3>
                    <p class="performance-text" style="font-family: 'Inter', sans-serif;">Nos solutions s'adaptent facilement à différents besoins et environnements d'utilisation.</p>
                </div>
                <div class="performance-card" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5)), url('assets/image\ 3.png'); background-size: cover; background-position: center;">
                    <h3 class="performance-title" style="font-family: 'Inter', sans-serif;">Design moderne et pratique</h3>
                    <p class="performance-text" style="font-family: 'Inter', sans-serif;">Un design ergonomique, simple et élégant, pensé pour une utilisation quotidienne.</p>
                </div>
                <div class="performance-card performance-card-featured" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5)), url('assets/image\ 4.png'); background-size: cover; background-position: center;">
                    <h3 class="performance-title" style="font-family: 'Inter', sans-serif;">Facile à utiliser et à entretenir</h3>
                    <p class="performance-text" style="font-family: 'Inter', sans-serif;">Nos robots sont intuitifs, faciles à manipuler et simples à nettoyer.</p>
                </div>
            </div>
        </div>
    </section>

<section class="how-section" id="how-section">
        <div class="how-sticky-container">
            <div class="how-title-wrapper" id="how-title-wrapper">
                <div class="how-title-row">
                    <span class="how-title-left">Comment</span>
                    <span class="how-title-right">ça<br>marche</span>
                </div>
            </div>
            <div class="how-etapes-container" id="how-etapes">
                <span class="how-etapes-text">Étapes</span>
            </div>
        </div>
    </section>

    <!-- ============================================
         STACKING PANELS — Cinematic Scroll Section
         ============================================ -->
    <section class="stack-section" id="stack-section">

        <!-- Progress indicator (fixed, right side) -->
        <nav class="stack-progress" id="stack-progress" aria-label="Panel navigation">
            <button class="stack-progress-dot" data-panel="0" aria-label="Panel 1"></button>
            <button class="stack-progress-dot" data-panel="1" aria-label="Panel 2"></button>
            <button class="stack-progress-dot" data-panel="2" aria-label="Panel 3"></button>
            <button class="stack-progress-dot" data-panel="3" aria-label="Panel 4"></button>
            <button class="stack-progress-dot" data-panel="4" aria-label="Panel 5"></button>
            <button class="stack-progress-dot" data-panel="5" aria-label="Panel 6"></button>
            <button class="stack-progress-dot" data-panel="6" aria-label="Panel 7"></button>
        </nav>

        <!-- Panel 1 -->
        <div class="stack-panel" style="z-index: 1;">
            <div class="stack-panel-bg" style="background: linear-gradient(180deg, #ffffff 0%, #f8fbfd 100%);"></div>
            <div class="stack-panel-overlay"></div>
            <div class="stack-panel-content">
                <div class="stack-panel-number">01</div>
                <div class="stack-panel-accent"></div>
                <h2 class="stack-panel-title">Allumez le robot</h2>
                <p class="stack-panel-desc">Appuyez sur le bouton d'alimentation pour démarrer votre robot. Le système s'initialise automatiquement et vous guide à travers les premières étapes.</p>
            </div>
        </div>

        <!-- Panel 2 -->
        <div class="stack-panel" style="z-index: 2;">
            <div class="stack-panel-bg" style="background: linear-gradient(180deg, #f8fbfd 0%, #f0f7fa 100%);"></div>
            <div class="stack-panel-overlay"></div>
            <div class="stack-panel-content">
                <div class="stack-panel-number">02</div>
                <div class="stack-panel-accent"></div>
                <h2 class="stack-panel-title">Connectez le système</h2>
                <p class="stack-panel-desc">Reliez votre robot au réseau Wi-Fi ou Bluetooth pour permettre la communication avec l'application mobile et les services cloud.</p>
            </div>
        </div>

        <!-- Panel 3 -->
        <div class="stack-panel" style="z-index: 3;">
            <div class="stack-panel-bg" style="background: linear-gradient(180deg, #f0f7fa 0%, #e8f4f8 100%);"></div>
            <div class="stack-panel-overlay"></div>
            <div class="stack-panel-content">
                <div class="stack-panel-number">03</div>
                <div class="stack-panel-accent"></div>
                <h2 class="stack-panel-title">Ouvrez l'application</h2>
                <p class="stack-panel-desc">Lancez l'application RDOC sur votre smartphone ou tablette. L'interface intuitive vous permet de contrôler et surveiller votre robot en temps réel.</p>
            </div>
        </div>

        <!-- Panel 4 -->
        <div class="stack-panel" style="z-index: 4;">
            <div class="stack-panel-bg" style="background: linear-gradient(180deg, #e8f4f8 0%, #f0f7fa 100%);"></div>
            <div class="stack-panel-overlay"></div>
            <div class="stack-panel-content">
                <div class="stack-panel-number">04</div>
                <div class="stack-panel-accent"></div>
                <h2 class="stack-panel-title">Configurez votre profil</h2>
                <p class="stack-panel-desc">Personnalisez les paramètres selon vos besoins : préférences d'apprentissage, niveau de difficulté, et objectifs éducatifs pour une expérience sur mesure.</p>
            </div>
        </div>

        <!-- Panel 5 -->
        <div class="stack-panel" style="z-index: 5;">
            <div class="stack-panel-bg" style="background: linear-gradient(180deg, #f0f7fa 0%, #f8fbfd 100%);"></div>
            <div class="stack-panel-overlay"></div>
            <div class="stack-panel-content">
                <div class="stack-panel-number">05</div>
                <div class="stack-panel-accent"></div>
                <h2 class="stack-panel-title">Choisissez la langue et la voix</h2>
                <p class="stack-panel-desc">Sélectionnez parmi plusieurs langues et voix disponibles pour adapter l'interaction vocale du robot à votre environnement et vos préférences.</p>
            </div>
        </div>

        <!-- Panel 6 -->
        <div class="stack-panel" style="z-index: 6;">
            <div class="stack-panel-bg" style="background: linear-gradient(180deg, #f8fbfd 0%, #f0f7fa 100%);"></div>
            <div class="stack-panel-overlay"></div>
            <div class="stack-panel-content">
                <div class="stack-panel-number">06</div>
                <div class="stack-panel-accent"></div>
                <h2 class="stack-panel-title">Interagissez avec le robot</h2>
                <p class="stack-panel-desc">Posez des questions, lancez des exercices ou explorez les fonctionnalités intelligentes. Le robot s'adapte à votre rythme et répond en temps réel.</p>
            </div>
        </div>

        <!-- Panel 7 (final — includes CTA) -->
        <div class="stack-panel" style="z-index: 7;">
            <div class="stack-panel-bg" style="background: linear-gradient(180deg, #f0f7fa 0%, #ffffff 100%);"></div>
            <div class="stack-panel-overlay"></div>
            <div class="stack-panel-content">
                <div class="stack-panel-number">07</div>
                <div class="stack-panel-accent"></div>
                <h2 class="stack-panel-title">Recevez les résultats</h2>
                <p class="stack-panel-desc">Consultez les rapports détaillés, suivez votre progression et recevez des recommandations personnalisées pour optimiser votre parcours d'apprentissage.</p>
                <div class="stack-cta-wrapper">
                    <a href="#" class="stack-cta">Télécharger</a>
                </div>
            </div>
        </div>

    </section>

<section class="zoom-transition-section" id="zoom-section">
        <div class="zoom-sticky-container">
            <div class="dual-carousel-container">
                <div class="carousel-row carousel-row-top">
                    <div class="carousel-track-wrapper">
                        <div class="carousel-track-inner">
                            <img src="assets/carousel 1.png" alt="Carousel 1">
                            <img src="assets/carousel 2.png" alt="Carousel 2">
                            <img src="assets/carousel 1.png" alt="Carousel 1">
                            <img src="assets/carousel 2.png" alt="Carousel 2">
                            <img src="assets/carousel 1.png" alt="Carousel 1">
                            <img src="assets/carousel 2.png" alt="Carousel 2">
                            <img src="assets/carousel 1.png" alt="Carousel 1">
                            <img src="assets/carousel 2.png" alt="Carousel 2">
                        </div>
                    </div>
                </div>
                <div class="carousel-row carousel-row-bottom">
                    <div class="carousel-track-wrapper">
                        <div class="carousel-track-inner">
                            <img src="assets/carousel 1.png" alt="Carousel 1">
                            <img src="assets/carousel 2.png" alt="Carousel 2">
                            <img src="assets/carousel 1.png" alt="Carousel 1">
                            <img src="assets/carousel 2.png" alt="Carousel 2">
                            <img src="assets/carousel 1.png" alt="Carousel 1">
                            <img src="assets/carousel 2.png" alt="Carousel 2">
                            <img src="assets/carousel 1.png" alt="Carousel 1">
                            <img src="assets/carousel 2.png" alt="Carousel 2">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <script src="assets/js/index.js"></script>
    <script src="assets/js/main.js"></script>


    
<?php include 'includes/footer.php'; ?>
</body>
</html>