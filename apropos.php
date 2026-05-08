<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta
            name="description"
            content="RDOC - Solutions robotiques innovantes pour l'éducation, la gestion et l'assistance."
        />
        <title>RDOC - À Propos</title>
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
            /* Background is handled globally in `assets/css/style.css` for responsiveness. */
            .glass {
                background: rgba(255, 255, 255, 0.21);
                backdrop-filter: blur(29px);
                -webkit-backdrop-filter: blur(29px);
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
            
            
            
            
            .founder-name {
                font-family: "Orbitron", sans-serif;
                font-size: 40px;
                font-weight: 700;
                color: #5cc4e5;
                letter-spacing: -0.44px;
                margin-bottom: 20px;
            }
            .founder-desc {
                font-family: "Inter", sans-serif;
                font-size: 24px;
                line-height: 1.5;
                letter-spacing: -0.264px;
                color: #ffffff;
                margin-bottom: 40px;
            }
            .btn-more {
                display: inline-flex;
                padding: 15px 30px;
                border-radius: 40px;
                border: 1px solid #5cc4e5;
                color: #5cc4e5;
                font-family: "Orbitron", sans-serif;
                font-size: 20px;
                font-weight: 700;
                letter-spacing: 2.6px;
                background: transparent;
                transition: all 0.3s;
                cursor: pointer;
                box-shadow: 1px -1px 19.7px #5cc4e5;
                text-decoration: none;
            }
            .btn-more:hover {
                background: #5cc4e5;
                color: #000;
            }
            .founder-img {
                max-width: 100%;
                height: auto;
                object-fit: cover;
            }
            .stats-card {
                display: flex;
                justify-content: space-around;
                align-items: center;
                padding: 30px 50px;
                border-radius: 20px;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(29px);
            }
            .stat-label {
                font-family: "Inter", sans-serif;
                font-size: 20px;
                font-weight: 700;
                color: #ffffff;
            }
            .stat-value {
                font-family: "Orbitron", sans-serif;
                font-size: 24px;
                font-weight: 700;
                color: #ffffff;
                letter-spacing: 3.12px;
            }
            .stat-value.text-cyan {
                color: #5cc4e5;
            }
            .stat-divider {
                width: 2px;
                height: 60px;
                background-color: rgba(255, 255, 255, 0.1);
            }
            .stat-avatars {
                display: flex;
                align-items: center;
            }
            .stat-avatars img {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                border: 2px solid #000000;
                margin-left: -15px;
            }
            .stat-avatars img:first-child {
                margin-left: 0;
            }
            .service-card {
                position: absolute;
                border: 2px solid #ffffff;
                border-radius: 24px;
                padding: 40px;
                background: transparent;
                transition: all 0.3s;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
            }
            .service-card:hover {
                box-shadow: 0 10px 30px rgba(92, 196, 229, 0.1);
                border-color: #5cc4e5;
            }
            .service-card-1 {
                top: 0;
                left: 0;
                width: 420px;
                height: 343px;
            }
            .service-card-2 {
                top: 0;
                left: 428px;
                width: 816px;
                height: 219px;
            }
            .service-card-3 {
                top: 228px;
                left: 428px;
                width: 301px;
                height: 354px;
            }
            .service-card-4 {
                top: 228px;
                left: 735px;
                width: 505px;
                height: 354px;
            }
            .service-card-5 {
                top: 348px;
                left: 0;
                width: 420px;
                height: 234px;
            }
            .service-number-circle {
                position: absolute;
                top: -22px;
                left: 40px;
                width: 45px;
                height: 45px;
                background: #ffffff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #000000;
                font-family: "Orbitron", sans-serif;
                font-size: 16px;
                font-weight: 700;
            }
            .service-card h3 {
                font-family: "Orbitron", sans-serif;
                font-size: 24px;
                color: #5cc4e5;
                margin-bottom: 15px;
            }
            .service-card p {
                font-family: "Inter", sans-serif;
                font-size: 16px;
                line-height: 1.6;
                color: #ffffff;
            }
            .btn-choisir {
                display: inline-flex;
                align-items: center;
                gap: 10px;
                font-size: 20px;
                letter-spacing: 1.5px;
                border-radius: 40px;
                padding: 15px 40px;
                background: transparent;
                color: #ffffff;
                border: 1px solid rgba(255, 255, 255, 0.1);
                transition: all 0.3s ease;
                text-decoration: none;
            }
            .btn-choisir:hover {
                border-color: #5cc4e5;
                color: #5cc4e5;
            }
            .btn-contacter {
                display: inline-flex;
                padding: 15px 40px;
                border-radius: 40px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: #ffffff;
                font-family: "Orbitron", sans-serif;
                font-size: 20px;
                font-weight: 700;
                background: transparent;
                transition: all 0.3s;
                text-decoration: none;
                box-shadow: 1px -1px 19.7px #5cc4e5;
            }
            .btn-contacter:hover {
                border-color: #5cc4e5;
                color: #5cc4e5;
            }
            .produit-highlight-title {
                font-family: "Orbitron", sans-serif;
                font-size: 32px;
                font-weight: 700;
                color: #5cc4e5;
                margin: 40px 0;
                letter-spacing: 2px;
            }

            /* Coverflow Carousel */
            .coverflow-container {
                perspective: 1000px;
                width: 100%;
                height: auto;
                margin-top: 40px;
                overflow: hidden;
            }

            .coverflow-wrapper {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 40px;
                padding: 60px 20px 100px 20px;
                position: relative;
            }

            .coverflow-nav-btn {
                background: rgba(92, 196, 229, 0.1);
                border: 2px solid rgba(92, 196, 229, 0.3);
                color: #5cc4e5;
                width: 50px;
                height: 50px;
                border-radius: 50%;
                cursor: pointer;
                font-size: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                z-index: 10;
                flex-shrink: 0;
            }

            .coverflow-nav-btn:hover {
                background: rgba(92, 196, 229, 0.2);
                border-color: #5cc4e5;
                transform: scale(1.1);
                box-shadow: 0 0 20px rgba(92, 196, 229, 0.5);
            }

            .coverflow-nav-btn:active {
                transform: scale(0.95);
            }

            .coverflow-track {
                display: flex;
                gap: 30px;
                align-items: center;
                justify-content: center;
                flex: 1;
                position: relative;
                height: 500px;
                max-width: 1000px;
                margin: 0 auto;
            }

            .coverflow-item {
                position: absolute;
                width: 280px;
                height: 420px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                opacity: 0.4;
                transform: translateX(0) rotateY(45deg) scale(0.6);
                z-index: 1;
            }

            .coverflow-item.active {
                opacity: 1;
                transform: translateX(0) rotateY(0deg) scale(1);
                z-index: 10;
            }

            .coverflow-item.prev {
                opacity: 0.5;
                transform: translateX(-280px) rotateY(-35deg) scale(0.75);
                z-index: 5;
            }

            .coverflow-item.next {
                opacity: 0.5;
                transform: translateX(280px) rotateY(35deg) scale(0.75);
                z-index: 5;
            }

            .coverflow-item.prev-prev {
                opacity: 0.2;
                transform: translateX(-500px) rotateY(-60deg) scale(0.5);
                z-index: 2;
            }

            .coverflow-item.next-next {
                opacity: 0.2;
                transform: translateX(500px) rotateY(60deg) scale(0.5);
                z-index: 2;
            }

            .coverflow-item-content {
                position: relative;
                width: 100%;
                height: 100%;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(92, 196, 229, 0.2);
                border-radius: 20px;
                padding: 20px;
                backdrop-filter: blur(10px);
                transition: all 0.3s ease;
            }

            .coverflow-item.active .coverflow-item-content {
                background: rgba(92, 196, 229, 0.1);
                border: 2px solid rgba(92, 196, 229, 0.5);
                box-shadow:
                    0 0 40px rgba(92, 196, 229, 0.3),
                    inset 0 0 20px rgba(92, 196, 229, 0.1);
            }

            .coverflow-item-image {
                width: 100%;
                height: 70%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 10px;
                position: relative;
                overflow: hidden;
            }

            .coverflow-item-image img {
                max-width: 90%;
                max-height: 90%;
                object-fit: contain;
                transition: transform 0.3s ease;
            }

            .coverflow-item.active .coverflow-item-image img {
                transform: scale(1.05);
            }

            .coverflow-item-info {
                text-align: center;
                width: 100%;
            }

            .coverflow-item-title {
                font-family: "Orbitron", sans-serif;
                font-size: 18px;
                font-weight: 700;
                color: #5cc4e5;
                margin-bottom: 8px;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .coverflow-item.active .coverflow-item-title {
                font-size: 22px;
            }

            .coverflow-item-desc {
                font-family: "Inter", sans-serif;
                font-size: 12px;
                color: rgba(255, 255, 255, 0.7);
                line-height: 1.4;
                opacity: 0;
                max-height: 0;
                transition: all 0.3s ease;
            }

            .coverflow-item.active .coverflow-item-desc {
                opacity: 1;
                max-height: 60px;
            }

            .coverflow-indicators {
                display: flex;
                justify-content: center;
                gap: 8px;
                margin-top: 40px;
                flex-wrap: wrap;
            }

            .coverflow-dot {
                width: 10px;
                height: 10px;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.2);
                cursor: pointer;
                transition: all 0.3s ease;
                border: 2px solid transparent;
            }

            .coverflow-dot.active {
                width: 30px;
                border-radius: 5px;
                background: #5cc4e5;
                box-shadow: 0 0 15px rgba(92, 196, 229, 0.6);
            }

            .coverflow-dot:hover {
                background: rgba(92, 196, 229, 0.5);
            }

            @media (max-width: 1024px) {
                .coverflow-track {
                    height: 420px;
                }

                .coverflow-item {
                    width: 240px;
                    height: 360px;
                }

                .coverflow-item.prev {
                    transform: translateX(-220px) rotateY(-30deg) scale(0.7);
                }

                .coverflow-item.next {
                    transform: translateX(220px) rotateY(30deg) scale(0.7);
                }

                .coverflow-item-title {
                    font-size: 16px;
                }
            }

            @media (max-width: 768px) {
                .coverflow-wrapper {
                    gap: 20px;
                    padding: 40px 10px;
                }

                .coverflow-track {
                    height: 360px;
                }

                .coverflow-item {
                    width: 200px;
                    height: 300px;
                }

                .coverflow-item.prev {
                    transform: translateX(-180px) rotateY(-25deg) scale(0.65);
                }

                .coverflow-item.next {
                    transform: translateX(180px) rotateY(25deg) scale(0.65);
                }

                .coverflow-nav-btn {
                    width: 40px;
                    height: 40px;
                    font-size: 18px;
                }

                .coverflow-item-title {
                    font-size: 14px;
                }

                .coverflow-item-desc {
                    display: none;
                }
            }
            .pourquoi-layout {
                display: flex;
                gap: 60px;
                align-items: stretch;
            }
            .pourquoi-text-col {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            .pourquoi-text-col p {
                font-family: "Inter", sans-serif;
                font-size: 20px;
                line-height: 1.6;
                color: #ffffff;
                max-width: 500px;
            }
            .pourquoi-visual-col {
                flex: 1;
                display: flex;
                gap: 20px;
                height: 350px;
            }
            .pourquoi-box {
                width: 350px;
                height: 383px;
                border: 2px solid #ffffff;
                border-radius: 35px;
                padding: 40px;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
            }
            .pourquoi-box h3 {
                font-family: "Orbitron", sans-serif;
                font-size: 24px;
                color: #5cc4e5;
                margin-bottom: 20px;
            }
            .pourquoi-box p {
                font-family: "Inter", sans-serif;
                font-size: 16px;
                line-height: 1.5;
                color: #ffffff;
            }
            .pourquoi-tab {
                width: 73px;
                height: 383px;
                border: 2px solid #ffffff;
                border-radius: 35px;
                display: flex;
                justify-content: center;
                align-items: center;
                cursor: pointer;
            }
            .pourquoi-tab span {
                font-family: "Orbitron", sans-serif;
                font-size: 24px;
                font-weight: 700;
                color: #5cc4e5;
                writing-mode: vertical-rl;
                transform: rotate(180deg);
                letter-spacing: 2px;
            }
@media (max-width: 1240px) {
                .services-grid-exact {
                    position: static !important;
                    height: auto !important;
                }
            }

            .service-card {
                    position: static !important;
                    width: 100% !important;
                    height: auto !important;
                }
                .service-number-circle {
                    position: static !important;
                    margin-bottom: 20px;
                }
            
            @media (max-width: 1024px) {
                
                .pourquoi-layout {
                    flex-direction: column;
                }
                .pourquoi-visual-col {
                    height: auto;
                    flex-direction: column;
                }
                .pourquoi-tab {
                    padding: 20px;
                }
                .pourquoi-tab span {
                    writing-mode: horizontal-tb;
                    transform: rotate(0);
                }
                .stats-card {
                    flex-wrap: wrap;
                    gap: 20px;
                    padding: 20px;
                }
                .stat-divider {
                    display: none;
                }
                .apropos-hero-content {
                    flex-direction: column;
                    text-align: center;
                }
                .founder-name {
                    font-size: 32px;
                }
                .pourquoi-box,
                .pourquoi-tab {
                    width: 100%;
                    max-width: 420px;
                    height: auto;
                }
            }
            @media (max-width: 768px) {
                .apropos-text-split {
                    flex-direction: column;
                    gap: 20px;
                }
                .apropos-text-divider {
                    width: 100px;
                    height: 2px;
                }
                .services-grid-exact {
                    grid-template-columns: 1fr;
                }
                .produit-side {
                    flex-direction: column;
                    gap: 12px;
                    opacity: 1;
                }
                .produit-nav-label {
                    display: none;
                }
                .indicator-line {
                    width: 70px;
                }
                .pourquoi-box {
                    padding: 20px;
                }
            }
            @media (max-width: 480px) {
                .pourquoi-box,
                .pourquoi-tab {
                    max-width: 100%;
                }
            }
            .service-card {
                opacity: 0;
                transform: translateY(40px);
                transition: opacity 0.5s ease, transform 0.5s ease !important;
            }

            /* Storytelling Sticky Section */
            #services {
                height: 600vh; /* space for 5 cards + exit */
                position: relative;
            }

            #services .container {
                position: sticky;
                top: 0;
                height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                overflow: hidden;
            }

            #services .services-grid-exact {
                position: relative !important;
                height: 450px !important;
                width: 100%;
                max-width: 100% !important;
                margin: 0 auto !important;
            }

            #services .service-card {
                position: absolute !important;
                top: 50% !important;
                left: 50% !important;
                width: 100% !important;
                max-width: 100% !important;
                height: auto !important;
                transform: translate(-50%, -40%) !important;
                pointer-events: none;
                margin: 0 !important;
                z-index: 1;
            }

            #services .service-card.active {
                opacity: 1;
                transform: translate(-50%, -50%) !important;
                pointer-events: auto;
                z-index: 10;
            }

            /* Compact spacing for Services section */
            #services h2 {
                margin-bottom: 15px !important;
            }
            #services p {
                margin-bottom: 25px !important;
            }
            #services .services-grid-exact {
                margin-top: 0 !important;
            }

            html {
                scroll-behavior: smooth;
            }

            @media (max-width: 768px) {
                #services .service-card {
                    max-width: 90%;
                }
            }
        </style>
        <link rel="stylesheet" href="assets/css/responsive.css" />
      <link rel="stylesheet" href="assets/css/nav-footer.css" />
</head>
    <body class="apropos-page">
<?php include 'includes/nav.php'; ?>

       
        <!-- Mobile Logo -->
        <!-- Mobile Bottom Navigation -->
        <div
            class="fixed bottom-0 left-0 right-0 lg:hidden z-50 bg-black/95 backdrop-blur-md border-t border-[rgba(255,255,255,0.1)] px-4 py-3"
        >
            <div class="flex justify-around items-center text-white">
                <a
                    href="index.php"
                    class="flex flex-col items-center text-xs text-white/70"
                >
                    <svg
                        class="w-6 h-6 mb-1"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <title>Accueil</title>
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                        />
                    </svg>
                    Accueil
                </a>
                <a
                    href="apropos.php"
                    class="flex flex-col items-center text-xs text-primary"
                >
                    <svg
                        class="w-6 h-6 mb-1"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <title>À propos</title>
                        <path
                            fill-rule="evenodd"
                            d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    A propos
                </a>
                <a
                    href="categorie.php"
                    class="flex flex-col items-center text-xs text-white/70"
                >
                    <svg
                        class="w-6 h-6 mb-1"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <title>Catégorie</title>
                        <path
                            d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"
                        />
                    </svg>
                    Catégorie
                </a>
                <a
                    href="produit.php"
                    class="flex flex-col items-center text-xs text-white/70"
                >
                    <svg
                        class="w-6 h-6 mb-1"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    Produit
                </a>
                <a
                    href="blog.php"
                    class="flex flex-col items-center text-xs text-white/70"
                >
                    <svg
                        class="w-6 h-6 mb-1"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <title>Blog</title>
                        <path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        <path d="M7 7h6M7 10h6M7 13h4"/>
                    </svg>
                    Blog
                </a>
                <a
                    href="contact.php"
                    class="flex flex-col items-center text-xs text-white/70"
                >
                    <svg
                        class="w-6 h-6 mb-1"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <title>Contact</title>
                        <path
                            d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"
                        />
                        <path
                            d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"
                        />
                    </svg>
                    Contact
                </a>
            </div>
        </div>
        <!-- Add padding top for fixed navbar on desktop -->
        <div class="hidden lg:block" style="height: 80px"></div>
        <!-- Mobile top padding for fixed logo -->
        <div class="lg:hidden" style="height: 70px"></div>

        <!-- Mobile bottom padding for fixed bottom nav -->
        <div class="lg:hidden" style="height: 80px"></div>

        <!-- ====== HERO A PROPOS ====== -->
        <section
            class="relative pt-[40px] sm:pt-[50px] md:pt-[60px] lg:pt-[80px] pb-[60px] sm:pb-[70px] md:pb-[80px] lg:pb-[100px] flex flex-col items-center"
            id="accueil"
        >
            <div class="absolute inset-0 z-0">
                <div
                    class="absolute inset-0 bg-gradient-to-b from-black/20 via-transparent to-black/95"
                ></div>
            </div>

            <div
                class="relative z-10 container mx-auto px-6 md:px-16 lg:px-24 mt-[40px] sm:mt-[50px] lg:mt-[60px] flex flex-col lg:flex-row items-center justify-between gap-8 sm:gap-10 lg:gap-10"
            >
                <div class="flex-1 max-w-[600px] text-center lg:text-left">
                    <h1 class="font-orbitron font-bold text-[28px] sm:text-[32px] md:text-[36px] lg:text-[40px] text-primary mb-4 sm:mb-5 lg:mb-6">Nour Elhouda Khalfa</h1>
                    <p class="font-inter text-sm sm:text-base md:text-lg lg:text-xl text-white leading-relaxed mb-6 sm:mb-8 lg:mb-10">
                        Fondatrice passionnée par la technologie et
                        l'innovation, je transforme les idées en projets
                        concrets et inspirants.
                    </p>
                </div>
                <div class="flex-1 flex justify-center">
                    <img
                        src="assets/images/me.png"
                        alt="Nour Elhouda Khalfa"
                        class="founder-img max-w-full w-[280px] sm:w-[320px] md:w-[400px] lg:max-w-[500px]"
                    />
                </div>
            </div>
        </section>

        <!-- ====== STATS BAR ====== -->
        <section class="relative z-10 pb-[60px] sm:pb-[70px] md:pb-[80px]">
            <div class="container mx-auto px-6 md:px-16 lg:px-24 -mt-10">
                <div class="glass rounded-[16px] sm:rounded-[18px] lg:rounded-[20px] p-4 sm:p-5 md:p-[25px] lg:p-[30px]">
                    <div class="stats-card flex-col md:flex-row">
                        <div
                            class="stat-item flex flex-col items-center gap-2.5 mx-4"
                        >
                            <span class="stat-label">Robots</span>
                            <span class="stat-value">60+</span>
                        </div>
                        <div class="stat-divider hidden md:block"></div>
                        <div
                            class="stat-item flex flex-col items-center gap-2.5 mx-4"
                        >
                            <span class="stat-label">Logiciels</span>
                            <span class="stat-value text-cyan">123+</span>
                        </div>
                        <div class="stat-divider hidden md:block"></div>
                        <div
                            class="stat-item flex flex-col items-center gap-2.5 mx-4"
                        >
                            <span class="stat-label">Utilisateurs</span>
                            <span class="stat-value">8K+</span>
                        </div>
                        <div class="stat-divider hidden md:block"></div>
                        <div
                            class="stat-item flex flex-col items-center gap-2.5 mx-4"
                        >
                            <span class="stat-label">Avis certifiés</span>
                            <div class="stat-avatars">
                                <img
                                    src="assets/images/Gemini_Generated_Image_1wpa1d1wpa1d1wpa 1.png"
                                    alt="User avatar"
                                    title="User avatar"
                                />
                                <img
                                    src="assets/images/Gemini_Generated_Image_1wpa1d1wpa1d1wpa 1.png"
                                    alt="User avatar"
                                    title="User avatar"
                                />
                                <img
                                    src="assets/images/Gemini_Generated_Image_1wpa1d1wpa1d1wpa 1.png"
                                    alt="User avatar"
                                    title="User avatar"
                                />
                                <img
                                    src="assets/images/Gemini_Generated_Image_1wpa1d1wpa1d1wpa 1.png"
                                    alt="User avatar"
                                    title="User avatar"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ====== A PROPOS DE NOUS ====== -->
        <section class="py-[60px] sm:py-[70px] md:py-[80px] text-center">
            <div class="container mx-auto px-6 md:px-16 lg:px-24">
                <h2
                    class="font-orbitron font-bold text-[24px] sm:text-[28px] md:text-[32px] lg:text-[40px] text-primary uppercase mb-4 sm:mb-5"
                >
                    À PROPOS DE NOUS
                </h2>
                <p class="font-inter text-base sm:text-lg md:text-xl lg:text-2xl text-white mb-[40px] sm:mb-[50px] md:mb-[60px]">
                    Innovation, technologie et apprentissage au service de votre
                    réussite
                </p>
                <div
                    class="flex flex-col md:flex-row justify-center items-center gap-6 sm:gap-8 md:gap-10 max-w-[1000px] mx-auto"
                >
                    <div
                        class="flex-1 font-inter text-sm sm:text-base md:text-lg lg:text-xl text-white text-left leading-relaxed"
                    >
                        <p>
                            Chez RDOC, nous rapprochons technologie et éducation
                            en créant des robots intelligents et interactifs.
                            Nos solutions accompagnent étudiants et
                            professionnels, stimulent la curiosité et facilitent
                            l'apprentissage et la gestion des données.
                        </p>
                    </div>
                    <div
                        class="w-[2px] h-[100px] sm:h-[120px] md:h-[150px] bg-[rgba(255,255,255,0.1)]"
                    ></div>
                    <div
                        class="flex-1 font-inter text-sm sm:text-base md:text-lg lg:text-xl text-white text-left leading-relaxed"
                    >
                        <p>
                            Notre équipe de passionnés conçoit des solutions
                            adaptées aux écoles, entreprises et institutions,
                            alliant performance technique et facilité
                            d'utilisation pour faire de nos robots des alliés
                            fiables et innovants.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ====== POURQUOI RDOC ? ====== -->
        <section class="py-[60px] sm:py-[70px] md:py-[80px]" id="pourquoi">
            <div class="container mx-auto px-6 md:px-16 lg:px-24">
                <div class="pourquoi-layout flex-col lg:flex-row">
                    <div class="pourquoi-text-col mb-8 sm:mb-10 lg:mb-0 text-center lg:text-left">
                        <h2
                            class="font-orbitron font-bold text-[24px] sm:text-[28px] md:text-[32px] lg:text-[40px] text-primary uppercase mb-4 sm:mb-5"
                        >
                            POURQUOI RDOC ?
                        </h2>
                        <p class="font-inter text-sm sm:text-base md:text-lg lg:text-xl leading-relaxed">
                            Chez RDOC, nous allions innovation, expertise et
                            accompagnement pour offrir des robots et logiciels
                            fiables, performants et adaptés à chaque projet,
                            qu'il soit éducatif, professionnel ou privé.
                        </p>
                    </div>

                    <div class="pourquoi-visual-col flex-col lg:flex-row">
                        <div class="pourquoi-box mb-5 lg:mb-0 active" 
                             data-title="Qualité" 
                             data-text="Chaque robot et logiciel est conçu avec le plus haut niveau de qualité, garantissant fiabilité, performance et durabilité pour tous vos projets.">
                            <h3>Qualité</h3>
                            <p>
                                Chaque robot et logiciel est conçu avec le plus
                                haut niveau de qualité, garantissant fiabilité,
                                performance et durabilité pour tous vos projets.
                            </p>
                        </div>
                        <div class="pourquoi-tab mb-5 lg:mb-0" 
                             data-title="Innovation" 
                             data-text="Nous intégrons les dernières technologies de pointe pour créer des solutions robotiques futuristes qui redéfinissent les standards de demain.">
                            <span>Innovation</span>
                        </div>
                        <div class="pourquoi-tab mb-5 lg:mb-0" 
                             data-title="Expertise" 
                             data-text="Notre équipe d'experts passionnés possède une connaissance approfondie du secteur, garantissant des conseils techniques de premier ordre.">
                            <span>Expertise</span>
                        </div>
                        <div class="pourquoi-tab" 
                             data-title="Accompagnement" 
                             data-text="Nous vous suivons à chaque étape, de la conception à la maintenance, pour assurer le succès continu de vos projets technologiques.">
                            <span>Accompagnement</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ====== NOS SERVICES ====== -->
        <section class="py-[60px] sm:py-[70px] md:py-[80px]" id="services">
            <div class="container mx-auto px-6 md:px-16 lg:px-24">
                <h2
                    class="font-orbitron font-bold text-[24px] sm:text-[28px] md:text-[32px] lg:text-[40px] text-center text-primary uppercase mb-4 sm:mb-5"
                >
                    NOS SERVICES
                </h2>
                <p class="font-inter text-base sm:text-lg md:text-xl lg:text-2xl text-white text-center mb-[40px] sm:mb-[50px] md:mb-[60px]">
                    Des outils robotiques et digitaux conçus sur mesure pour
                    répondre à vos besoins spécifiques.
                </p>

                <div
                    class="relative h-[582px] mt-[40px] sm:mt-[50px] md:mt-[60px] mb-[40px] sm:mb-[50px] md:mb-[60px] services-grid-exact"
                >
                    <div class="service-card service-card-1">
                        <div class="service-number-circle">01</div>
                        <h3>Vente de robots personnalisés</h3>
                        <p>
                            Fourniture de robots adaptés aux besoins spécifiques
                            des clients : écoles, entreprises privées ou
                            institutions publiques.
                        </p>
                    </div>
                    <div class="service-card service-card-2">
                        <div class="service-number-circle">02</div>
                        <h3>Service après-vente et maintenance</h3>
                        <p>
                            Assistance technique, mises à jour et dépannage pour
                            assurer la durabilité et performance des robots.
                        </p>
                    </div>
                    <div class="service-card service-card-3">
                        <div class="service-number-circle">03</div>
                        <h3>Intégration et configuration logicielle</h3>
                        <p>
                            Installation et adaptation des logiciels selon le
                            robot et l'usage du client.
                        </p>
                    </div>
                    <div class="service-card service-card-4">
                        <div class="service-number-circle">04</div>
                        <h3>Support et accompagnement</h3>
                        <p>
                            Conseil et suivi pour une utilisation optimale des
                            robots.
                        </p>
                    </div>
                    <div class="service-card service-card-5">
                        <div class="service-number-circle">05</div>
                        <h3>Formations professionnelles</h3>
                        <p>
                            Formations en développement, codage, IA et IT pour
                            professionnels, avec ateliers éducatifs pour
                            enfants.
                        </p>
                    </div>
                </div>
                <div class="flex justify-center mt-12 mb-6">
                    <a href="contact.php" class="btn-outline px-10 py-3 text-lg font-orbitron hover:bg-primary hover:text-black transition-all duration-300">
                        REJOIGNEZ
                    </a>
                </div>
            </div>
        </section>

       

        <!-- ====== FOOTER ====== -->
        
                       

        <!-- Mobile bottom padding for fixed bottom nav -->
        <div class="lg:hidden" style="height: 80px"></div>
        <script src="assets/js/main.js"></script>

        <!-- Coverflow Carousel JavaScript -->
        <script>
            class CoverflowCarousel {
                constructor() {
                    this.currentIndex = 0;
                    this.items = document.querySelectorAll(".coverflow-item");
                    this.dots = document.querySelectorAll(".coverflow-dot");
                    this.totalItems = this.items.length;
                    this.prevBtn = document.getElementById("coverflow-prev");
                    this.nextBtn = document.getElementById("coverflow-next");
                    this.autoPlayInterval = null;

                    this.init();
                }

                init() {
                    // Event listeners
                    this.prevBtn.addEventListener("click", () =>
                        this.previous(),
                    );
                    this.nextBtn.addEventListener("click", () => this.next());

                    // Dot click listeners
                    this.dots.forEach((dot, index) => {
                        dot.addEventListener("click", () => this.goTo(index));
                    });

                    // Keyboard navigation
                    document.addEventListener("keydown", (e) => {
                        if (e.key === "ArrowLeft") this.previous();
                        if (e.key === "ArrowRight") this.next();
                    });

                    // Auto-play (optional, uncomment to enable)
                    // this.startAutoPlay();

                    // Initial render
                    this.updateCarousel();
                }

                next() {
                    this.currentIndex =
                        (this.currentIndex + 1) % this.totalItems;
                    this.updateCarousel();
                    this.resetAutoPlay();
                }

                previous() {
                    this.currentIndex =
                        (this.currentIndex - 1 + this.totalItems) %
                        this.totalItems;
                    this.updateCarousel();
                    this.resetAutoPlay();
                }

                goTo(index) {
                    this.currentIndex = index;
                    this.updateCarousel();
                    this.resetAutoPlay();
                }

                updateCarousel() {
                    // Update item positions
                    this.items.forEach((item, index) => {
                        item.classList.remove(
                            "active",
                            "prev",
                            "next",
                            "prev-prev",
                            "next-next",
                        );

                        const distance =
                            (index - this.currentIndex + this.totalItems) %
                            this.totalItems;

                        if (distance === 0) {
                            item.classList.add("active");
                        } else if (distance === 1) {
                            item.classList.add("next");
                        } else if (distance === 2) {
                            item.classList.add("next-next");
                        } else if (distance === this.totalItems - 1) {
                            item.classList.add("prev");
                        } else if (distance === this.totalItems - 2) {
                            item.classList.add("prev-prev");
                        }
                    });

                    // Update dots
                    this.dots.forEach((dot, index) => {
                        dot.classList.toggle(
                            "active",
                            index === this.currentIndex,
                        );
                    });
                }

                startAutoPlay() {
                    this.autoPlayInterval = setInterval(() => {
                        this.next();
                    }, 5000); // Change slide every 5 seconds
                }

                resetAutoPlay() {
                    if (this.autoPlayInterval) {
                        clearInterval(this.autoPlayInterval);
                        this.startAutoPlay();
                    }
                }
            }

            // Initialize carousel when DOM is ready
            document.addEventListener("DOMContentLoaded", () => {
                new CoverflowCarousel();
            });
        </script>
        <script>
            // Animation Storytelling au scroll pour "Nos Services"
            document.addEventListener("DOMContentLoaded", () => {
                const servicesSection = document.getElementById("services");
                const serviceCards = document.querySelectorAll(".service-card");
                
                if (!servicesSection || serviceCards.length === 0) return;

                const handleScroll = () => {
                    const rect = servicesSection.getBoundingClientRect();
                    const sectionHeight = servicesSection.offsetHeight;
                    const viewportHeight = window.innerHeight;

                    // On calcule la progression du scroll dans la section (de 0 à 1)
                    // scrolled est la distance entre le haut de la section et le haut du viewport
                    let scrolled = -rect.top;
                    let scrollable = sectionHeight - viewportHeight;
                    
                    if (scrolled < 0) {
                        // Avant d'arriver à la section : seule la première est active
                        updateActiveCard(0);
                    } else if (scrolled > scrollable) {
                        // Après la section : on garde la dernière active
                        updateActiveCard(serviceCards.length - 1);
                    } else {
                        // Pendant le scroll collé (sticky)
                        const progress = scrolled / scrollable;
                        const index = Math.min(
                            Math.floor(progress * serviceCards.length),
                            serviceCards.length - 1
                        );
                        updateActiveCard(index);
                    }
                };

                const updateActiveCard = (activeIndex) => {
                    serviceCards.forEach((card, i) => {
                        if (i === activeIndex) {
                            card.classList.add("active");
                        } else {
                            card.classList.remove("active");
                        }
                    });
                };

                // Initialiser au chargement
                handleScroll();
                
                // Ecouter le scroll
                window.addEventListener("scroll", handleScroll, { passive: true });
            });
        </script>
        <script>
            // Animation Interactive pour "Pourquoi RDOC"
            document.addEventListener("DOMContentLoaded", () => {
                const section = document.getElementById("pourquoi");
                const mainTitle = section.querySelector("h2");
                const tabs = section.querySelectorAll(".pourquoi-tab, .pourquoi-box");
                const box = section.querySelector(".pourquoi-box");
                const boxTitle = box.querySelector("h3");
                const boxText = box.querySelector("p");

                const updatePourquoi = (element) => {
                    const { title, text } = element.dataset;
                    if (!title || !text) return;

                    // Update UI state
                    tabs.forEach(t => t.classList.remove("active"));
                    element.classList.add("active");

                    // Animation fade out
                    box.style.opacity = "0";
                    box.style.transform = "translateY(10px)";
                    mainTitle.style.opacity = "0";

                    setTimeout(() => {
                        // Change content
                        boxTitle.textContent = title;
                        boxText.textContent = text;
                        mainTitle.textContent = title.toUpperCase();

                        // Animation fade in
                        box.style.opacity = "1";
                        box.style.transform = "translateY(0)";
                        mainTitle.style.opacity = "1";
                    }, 200);
                };

                // Clic sur les bandes
                tabs.forEach(tab => {
                    tab.addEventListener("click", () => updatePourquoi(tab));
                });

                // Scroll detection pour mobile (activation auto)
                if (window.innerWidth < 1024) {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                updatePourquoi(entry.target);
                            }
                        });
                    }, { threshold: 0.6 });

                    tabs.forEach(tab => observer.observe(tab));
                }
            });
        </script>
    <?php include 'includes/footer.php'; ?>
<script src="assets/js/nav.js"></script>
</body>
</html>
