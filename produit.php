<?php
require_once 'includes/pdo.php';

$categorie_id = isset($_GET['categorie']) ? (int)$_GET['categorie'] : null;

if ($categorie_id) {
    $stmt = $pdo->prepare("
        SELECT id, nom, image_url
        FROM produits
        WHERE statut = 'actif' AND categorie_id = ?
        ORDER BY nom
    ");
    $stmt->execute([$categorie_id]);
} else {
    $stmt = $pdo->query("
        SELECT id, nom, image_url
        FROM produits
        WHERE statut = 'actif'
        ORDER BY nom
    ");
}
$catalogProducts = $stmt->fetchAll();

// Get active categories for filtering
$categories = [];
$catStmt = $pdo->query("SELECT id, nom FROM categories WHERE statut = 'actif' ORDER BY nom");
$categories = $catStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produit</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400&family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/layout-refresh.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lenis@1.1.13/dist/lenis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Orbitron', sans-serif;
            font-size: 18px;
            background-color: #000000;
            min-height: 100vh;
        }

        @keyframes fadeSlideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeSlideUp {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(-10px); }
        }

        .dropdown-enter { animation: fadeSlideDown 0.3s ease-out forwards; }
        .dropdown-exit { animation: fadeSlideUp 0.3s ease-out forwards; }

        .favicon-logo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
        }

        .favicon-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transform: rotate(0deg);
            transition: transform 0.2s ease-out;
        }
        
        .favicon-logo:hover img {
            animation: spin 0.5s linear infinite;
            cursor: pointer;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .site-footer {
            padding: 80px 0 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
        }

        .footer-brand {
            display: flex;
            flex-direction: column;
        }

        .footer-logo {
            width: 150px;
            margin-bottom: 20px;
        }

        .social-icons {
            display: flex;
            gap: 15px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background: #5CC4E5;
            border-color: #5CC4E5;
            color: #000000;
        }

        .footer-heading {
            color: #5CC4E5;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .newsletter-desc {
            font-family: 'Inter', sans-serif;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .newsletter-form {
            display: flex;
            gap: 10px;
        }

        .newsletter-input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            background: transparent;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
        }

        .newsletter-input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .newsletter-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #5CC4E5;
            border: none;
            color: #000000;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer-list {
            list-style: none;
        }

        .footer-list li {
            margin-bottom: 10px;
        }

        .footer-list a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .footer-list a:hover {
            color: #5CC4E5;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.5);
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
        }

        .menu-item {
            padding: 10px;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .menu-item:hover {
            background: rgba(0, 0, 0, 0.05);
        }

        .hero-video-section {
            width: 100%;
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .hero-video-section video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-content {
            position: absolute;
            top: 50%;
            left: 50px;
            transform: translateY(-50%);
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .hero-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 48px;
            color: #5CC4E5;
            max-width: 600px;
            line-height: 1.2;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
        }

        .hero-btn {
            padding: 15px 35px;
            border-radius: 30px;
            font-family: 'Orbitron', sans-serif;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .hero-btn:hover {
            transform: scale(1.05);
        }

        .hero-btn-black {
            background: #000000;
            color: #ffffff;
            border: 2px solid #000000;
        }

        .hero-btn-white {
            background: #ffffff;
            color: #000000;
            border: 2px solid #ffffff;
        }

        .stats-section {
            padding: 120px 20px;
            background: #000000;
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 120px;
        }

        .stat-item {
            text-align: center;
            opacity: 0;
            transform: translateY(40px);
        }

        .stat-number {
            font-family: 'Orbitron', sans-serif;
            font-size: 72px;
            font-weight: 700;
            color: #5CC4E5;
            line-height: 1;
            margin-bottom: 15px;
            display: inline-block;
        }

        .stat-title {
            font-family: 'Inter', sans-serif;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.85);
            letter-spacing: 0.5px;
        }

        @media (max-width: 768px) {
            .stats-container {
                flex-direction: column;
                gap: 60px;
            }
            .stat-number {
                font-size: 56px;
            }
            .stats-section {
                padding: 80px 20px;
            }
        }

        /* Robots Hero Carousel */
        .robots-section {
            padding: 100px 20px 120px;
            background: #000000;
            overflow: hidden;
        }

        .robots-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 40px;
            color: #5CC4E5;
            text-align: center;
            margin-bottom: 15px;
        }

        .robots-subtitle {
            font-family: 'Inter', sans-serif;
            font-size: 20px;
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
            max-width: 700px;
            margin: 0 auto 50px;
            line-height: 1.6;
        }

        .hero-carousel-wrapper {
            position: relative;
            max-width: 600px;
            margin: 0 auto;
            height: 400px;
        }

        .hero-robot {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            
        }

        .hero-robot.active {
            opacity: 1;
            transform: scale(1);
            pointer-events: auto;
        }

        .hero-robot img {
            width: 350px;
            height: 350px;
            object-fit: contain;
            filter: drop-shadow(0 0 40px rgba(92, 196, 229, 0.3));
            transition: filter 0.4s ease;
        }

        .hero-robot.active img {
            filter: drop-shadow(0 0 60px rgba(92, 196, 229, 0.5));
        }

        .hero-robot-name {
            font-family: 'Orbitron', sans-serif;
            font-size: 36px;
            color: #5CC4E5;
            margin-top: 20px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.4s ease 0.2s;
        }

        .hero-robot.active .hero-robot-name {
            opacity: 1;
            transform: translateY(0);
        }

        .carousel-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 60px;
            height: 60px;
            background: rgba(92, 196, 229, 0.1);
            border: 2px solid #5CC4E5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 20;
            color: #5CC4E5;
            font-size: 28px;
        }

        .carousel-arrow:hover {
            background: #5CC4E5;
            color: #000000;
            transform: translateY(-50%) scale(1.15);
            box-shadow: 0 0 30px rgba(92, 196, 229, 0.6);
        }

        .carousel-arrow.left {
            left: 20px;
        }

        .carousel-arrow.right {
            right: 20px;
        }

        .carousel-dots {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 40px;
        }

        .carousel-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .carousel-dot.active {
            background: #5CC4E5;
            box-shadow: 0 0 15px rgba(92, 196, 229, 0.6);
        }

        /* Robots Scroll Section */
        #robots-scroll-section {
            position: relative;
            width: 100%;
            min-height: 100vh;
            overflow: hidden;
            background: #000000;
        }

        .robots-intro-section {
            background: #000000;
            padding: 80px 20px 40px;
            text-align: center;
        }

        .robots-intro-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 40px;
            color: #5CC4E5;
            margin-bottom: 15px;
        }

        .robots-intro-subtitle {
            font-family: 'Inter', sans-serif;
            font-size: 20px;
            color: rgba(255, 255, 255, 0.7);
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .robots-scroll-wrapper {
            width: 100%;
            height: 100%;
        }

        .robot-scroll-item {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: scale(0.8);
            pointer-events: none;
        }

        .robot-scroll-item[data-active="true"] {
            pointer-events: auto;
        }

        .robot-scroll-btn {
            display: inline-block;
            margin-top: 30px;
            padding: 14px 40px;
            font-family: 'Orbitron', sans-serif;
            font-size: 14px;
            color: #000000;
            background: linear-gradient(135deg, #5CC4E5 0%, #7DD3F0 100%);
            border-radius: 30px;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 600;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(15px);
            position: relative;
            z-index: 100;
        }

        .robot-scroll-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 0 40px rgba(92, 196, 229, 0.6);
        }

        .robot-scroll-item:hover img {
            filter: drop-shadow(0 0 80px rgba(92, 196, 229, 0.4));
        }

        .robot-scroll-item:hover .robot-scroll-name {
            text-shadow: 0 0 30px rgba(92, 196, 229, 0.6);
        }

        .robot-scroll-item img {
            width: 400px;
            height: 400px;
            object-fit: contain;
            filter: drop-shadow(0 0 50px rgba(92, 196, 229, 0.2));
        }

        .robot-scroll-name {
            font-family: 'Orbitron', sans-serif;
            font-size: 32px;
            color: #5CC4E5;
            margin-top: 30px;
        }

        .robot-scroll-desc {
            font-family: 'Inter', sans-serif;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
            max-width: 500px;
            margin-top: 15px;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .robot-scroll-item img {
                width: 250px;
                height: 250px;
            }
            .robot-scroll-name {
                font-size: 26px;
            }
            .robot-scroll-desc {
                font-size: 15px;
                padding: 0 20px;
            }
        }

/* Categories Interactive Section */
        .categories-section {
            padding: 80px 0;
            background: #000000;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .categories-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 42px;
            color: #5CC4E5;
            text-align: center;
            margin-bottom: 50px;
            position: relative;
            z-index: 1;
        }

        .categories-container {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            width: 100%;
            max-width: 1400px;
            padding: 0 60px;
            flex: 1;
            align-items: stretch;
        }

        .category-panel {
            display: flex;
            position: relative;
            flex-shrink: 0;
            height: 85vh;
            max-height: 700px;
        }

        .category-toggle {
            width: 80px;
            height: 100%;
            background: linear-gradient(180deg, rgba(12, 12, 22, 0.98) 0%, rgba(3, 3, 10, 0.99) 100%);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
            transition: border-color 0.4s ease, box-shadow 0.4s ease;
        }

        .category-toggle::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, rgba(92, 196, 229, 0.12) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .category-toggle:hover {
            border-color: rgba(92, 196, 229, 0.5);
            box-shadow: 0 0 40px rgba(92, 196, 229, 0.15);
        }

        .category-toggle:hover::before {
            opacity: 1;
        }

        .category-panel.active .category-toggle {
            border-color: #5CC4E5;
            box-shadow: 0 0 50px rgba(92, 196, 229, 0.3);
        }

        .category-toggle-name {
            font-family: 'Orbitron', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #5CC4E5;
            writing-mode: vertical-rl;
            text-orientation: mixed;
            transform: rotate(180deg);
            letter-spacing: 4px;
            text-transform: uppercase;
            text-shadow: 0 0 20px rgba(92, 196, 229, 0.6);
            white-space: nowrap;
        }

        .category-content {
            width: 0;
            overflow: hidden;
            opacity: 0;
            background: linear-gradient(135deg, rgba(15, 15, 35, 0.99) 0%, rgba(5, 5, 15, 0.995) 100%);
            border: 2px solid rgba(92, 196, 229, 0.35);
            border-left: none;
            border-radius: 0 16px 16px 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .category-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(ellipse at left, rgba(92, 196, 229, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .category-panel.active .category-content {
            width: 550px;
            opacity: 1;
            padding: 60px 50px;
        }

        .panel-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 38px;
            color: #5CC4E5;
            margin-bottom: 15px;
            text-shadow: 0 0 30px rgba(92, 196, 229, 0.5);
            letter-spacing: 2px;
        }

        .panel-subtitle {
            font-family: 'Orbitron', sans-serif;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.65);
            margin-bottom: 35px;
            letter-spacing: 1px;
        }

        .panel-text {
            font-family: 'Rajdhani', sans-serif;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.9;
            margin-bottom: 40px;
            max-width: 480px;
        }

        .panel-btn {
            font-family: 'Orbitron', sans-serif;
            font-size: 15px;
            color: #000000;
            background: linear-gradient(135deg, #5CC4E5 0%, #7DD3F0 100%);
            padding: 16px 50px;
            border-radius: 35px;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 3px;
            transition: all 0.3s ease;
            align-self: flex-start;
            font-weight: 600;
        }

        .panel-btn:hover {
            transform: scale(1.05) translateX(5px);
            box-shadow: 0 0 40px rgba(92, 196, 229, 0.6);
        }

        @media (max-width: 1200px) {
            .categories-container {
                justify-content: center;
            }
            .category-panel.active .category-content {
                width: 450px;
            }
        }

        @media (max-width: 900px) {
            .categories-section {
                min-height: auto;
                padding: 60px 0;
            }
            .categories-container {
                flex-direction: column;
                align-items: center;
                padding: 0 20px;
                gap: 20px;
            }
            .category-panel {
                width: 100%;
                max-width: 450px;
                height: auto;
            }
            .category-toggle {
                width: 100%;
                height: 80px;
                padding: 20px;
            }
            .category-toggle-name {
                writing-mode: horizontal-tb;
                transform: rotate(0deg);
            }
            .category-panel.active .category-content {
                width: 100%;
                padding: 40px 30px;
            }
            .panel-title {
                font-size: 28px;
            }
            .panel-subtitle {
                font-size: 16px;
            }
            .panel-text {
                font-size: 16px;
            }
        }

        @media (max-width: 600px) {
            .categories-title {
                font-size: 28px;
            }
            .category-panel.active .category-content {
                padding: 30px 25px;
            }
            .panel-title {
                font-size: 24px;
            }
            .panel-btn {
                padding: 14px 35px;
                font-size: 14px;
            }
        }

        .categories-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 42px;
            color: #5CC4E5;
            text-align: center;
            margin-bottom: 50px;
            position: relative;
            z-index: 1;
        }

        .categories-container {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            width: 100%;
            max-width: 1600px;
            padding: 0 60px;
            flex: 1;
            align-items: stretch;
        }

        .category-panel {
            display: flex;
            position: relative;
            flex-shrink: 0;
            height: 85vh;
            max-height: 700px;
        }

        .category-toggle {
            width: 80px;
            height: 100%;
            background: linear-gradient(180deg, rgba(12, 12, 22, 0.98) 0%, rgba(3, 3, 10, 0.99) 100%);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
            transition: border-color 0.4s ease, box-shadow 0.4s ease;
        }

        .category-toggle::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(180deg, rgba(92, 196, 229, 0.12) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .category-toggle:hover {
            border-color: rgba(92, 196, 229, 0.5);
            box-shadow: 0 0 40px rgba(92, 196, 229, 0.15);
        }

        .category-toggle:hover::before {
            opacity: 1;
        }

        .category-panel.active .category-toggle {
            border-color: #5CC4E5;
            box-shadow: 0 0 50px rgba(92, 196, 229, 0.3);
        }

        .category-toggle-name {
            font-family: 'Orbitron', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #5CC4E5;
            writing-mode: vertical-rl;
            text-orientation: mixed;
            transform: rotate(180deg);
            letter-spacing: 4px;
            text-transform: uppercase;
            text-shadow: 0 0 20px rgba(92, 196, 229, 0.6);
            white-space: nowrap;
        }

        .category-content {
            width: 0;
            overflow: hidden;
            opacity: 0;
            background: linear-gradient(135deg, rgba(15, 15, 35, 0.99) 0%, rgba(5, 5, 15, 0.995) 100%);
            border: 2px solid rgba(92, 196, 229, 0.35);
            border-left: none;
            border-radius: 0 16px 16px 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .category-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(ellipse at left, rgba(92, 196, 229, 0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .category-panel.active .category-content {
            width: 600px;
            opacity: 1;
            padding: 60px 50px;
        }

        .panel-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 38px;
            color: #5CC4E5;
            margin-bottom: 15px;
            text-shadow: 0 0 30px rgba(92, 196, 229, 0.5);
            letter-spacing: 2px;
        }

        .panel-subtitle {
            font-family: 'Orbitron', sans-serif;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.65);
            margin-bottom: 35px;
            letter-spacing: 1px;
        }

        .panel-text {
            font-family: 'Rajdhani', sans-serif;
            font-size: 18px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.9;
            margin-bottom: 40px;
            max-width: 500px;
        }

        .panel-btn {
            font-family: 'Orbitron', sans-serif;
            font-size: 15px;
            color: #000000;
            background: linear-gradient(135deg, #5CC4E5 0%, #7DD3F0 100%);
            padding: 16px 50px;
            border-radius: 35px;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 3px;
            transition: all 0.3s ease;
            align-self: flex-start;
            font-weight: 600;
        }

        .panel-btn:hover {
            transform: scale(1.05) translateX(5px);
            box-shadow: 0 0 40px rgba(92, 196, 229, 0.6);
        }

        @media (max-width: 1400px) {
            .category-panel.active .category-content {
                width: 500px;
            }
        }

        @media (max-width: 1200px) {
            .categories-container {
                justify-content: center;
            }
            .category-panel.active .category-content {
                width: 450px;
            }
        }

        @media (max-width: 900px) {
            .categories-section {
                min-height: auto;
                padding: 60px 0;
            }
            .categories-container {
                flex-direction: column;
                align-items: center;
                padding: 0 20px;
                gap: 20px;
            }
            .category-panel {
                width: 100%;
                max-width: 450px;
                height: auto;
                max-height: none;
            }
            .category-toggle {
                width: 100%;
                height: 80px;
                padding: 20px;
            }
            .category-toggle-name {
                writing-mode: horizontal-tb;
                transform: rotate(0deg);
            }
            .category-panel.active .category-content {
                width: 100%;
                padding: 40px 30px;
            }
            .panel-title {
                font-size: 28px;
            }
            .panel-subtitle {
                font-size: 16px;
            }
            .panel-text {
                font-size: 16px;
            }
        }

        @media (max-width: 600px) {
            .categories-title {
                font-size: 28px;
            }
            .category-panel.active .category-content {
                padding: 30px 25px;
            }
            .panel-title {
                font-size: 24px;
            }
            .panel-btn {
                padding: 14px 35px;
                font-size: 13px;
            }
        }

        /* Reviews Section */
        .reviews-section {
            padding: 100px 40px;
            background: linear-gradient(180deg, #000000 0%, #0a0a15 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .reviews-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 42px;
            color: #5CC4E5;
            text-align: center;
            margin-bottom: 60px;
            text-shadow: 0 0 30px rgba(92, 196, 229, 0.4);
            letter-spacing: 2px;
        }

        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            max-width: 1200px;
            width: 100%;
        }

        .review-card {
            background: linear-gradient(135deg, rgba(20, 20, 35, 0.9) 0%, rgba(10, 10, 20, 0.95) 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .review-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(ellipse at top, rgba(92, 196, 229, 0.06) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .review-card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: rgba(92, 196, 229, 0.4);
            box-shadow: 0 20px 60px rgba(92, 196, 229, 0.15);
        }

        .review-card:hover::before {
            opacity: 1;
        }

        .review-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #5CC4E5 0%, #7DD3F0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-family: 'Orbitron', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: #000000;
            box-shadow: 0 0 30px rgba(92, 196, 229, 0.3);
        }

        .review-stars {
            display: flex;
            gap: 5px;
            margin-bottom: 20px;
        }

        .review-star {
            width: 20px;
            height: 20px;
            fill: #5CC4E5;
        }

        .review-name {
            font-family: 'Orbitron', sans-serif;
            font-size: 18px;
            color: #5CC4E5;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }

        .review-text {
            font-family: 'Rajdhani', sans-serif;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.8;
            margin-bottom: 0;
        }

        .reviews-read-more-btn {
            display: inline-block;
            margin-top: 50px;
            font-family: 'Orbitron', sans-serif;
            font-size: 15px;
            color: #000000;
            background: linear-gradient(135deg, #5CC4E5 0%, #7DD3F0 100%);
            padding: 16px 50px;
            border-radius: 35px;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .reviews-read-more-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 40px rgba(92, 196, 229, 0.6);
        }

        @media (max-width: 1000px) {
            .reviews-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 700px) {
            .reviews-grid {
                grid-template-columns: 1fr;
            }
            .reviews-title {
                font-size: 32px;
            }
            .review-card {
                padding: 30px 25px;
            }
        }
    </style>
  <link rel="stylesheet" href="assets/css/nav-footer.css" />
</head>
<body>
<?php include 'includes/nav.php'; ?>


   <section class="hero-video-section">
        <video autoplay loop muted playsinline>
            <source src="assets/produit.mp4" type="video/mp4">
        </video>
        <div class="hero-content">
            <h1 class="hero-title">Des robots intelligents pour chaque besoin</h1>
            <div class="hero-buttons">
                <a href="commander.php" class="hero-btn hero-btn-white">Commander</a>
            </div>
        </div>
    </section>

    <section class="stats-section">
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-number">10+</div>
                <div class="stat-title">Années d'innovation</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">123+</div>
                <div class="stat-title">Logiciels</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">8K+</div>
                <div class="stat-title">Utilisateurs</div>
            </div>
        </div>
</section>

    <section class="robots-intro-section">
        <h2 class="robots-intro-title">Découvrez nos robots interactifs</h2>
        <p class="robots-intro-subtitle">Sélectionnez un robot et explorez ses fonctionnalités, son rôle et ses usages uniques.</p>
    </section>

    <section id="robots-scroll-section">
        <div class="robots-scroll-wrapper">
            <?php foreach ($catalogProducts as $index => $product): ?>
            <div class="robot-scroll-item" data-index="<?= (int) $index ?>">
                <img src="<?= htmlspecialchars($product['image_url'] ?: 'assets/images/robot.png', ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($product['nom'], ENT_QUOTES, 'UTF-8') ?>">
                <h3 class="robot-scroll-name"><?= htmlspecialchars($product['nom'], ENT_QUOTES, 'UTF-8') ?></h3>
                <a href="product-detail.php?id=<?= (int) $product['id'] ?>" class="robot-scroll-btn">Accéder</a>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="categories-section">
        <h2 class="categories-title">Découvrez toutes nos catégories de robots</h2>
        <div class="categories-container">
            <div class="category-panel" data-category="educative">
                <div class="category-toggle">
                    <span class="category-toggle-name">Educative</span>
                </div>
                <div class="category-content">
                    <h3 class="panel-title">Educative</h3>
                    
                    <p class="panel-text">Nos robots éducatifs sont conçus pour accompagner les élèves et les enseignants à chaque étape de l'apprentissage. Ils captent le son, projettent des supports pédagogiques interactifs, transforment les cours en expériences ludiques et personnalisées, et offrent un accès facile à des ressources éducatives variées, tout en stimulant la curiosité et la motivation des apprenants.</p>
                    <a href="#" class="panel-btn">Accéder</a>
                </div>
            </div>
            <div class="category-panel" data-category="informative">
                <div class="category-toggle">
                    <span class="category-toggle-name">Informative</span>
                </div>
                <div class="category-content">
                    <h3 class="panel-title">Informative</h3>
                    
                    <p class="panel-text">Nos robots informatifs permettent de fournir des réponses rapides et précises en temps réel. Ils analysent les demandes des utilisateurs, affichent des données claires et simplifiées, et facilitent la compréhension des sujets complexes grâce à des interfaces interactives adaptées à tous les niveaux.</p>
                    <a href="#" class="panel-btn">Accéder</a>
                </div>
            </div>
            <div class="category-panel" data-category="localisative">
                <div class="category-toggle">
                    <span class="category-toggle-name">Localisative</span>
                </div>
                <div class="category-content">
                    <h3 class="panel-title">Localisative</h3>
                    
                    <p class="panel-text">Ces robots aident à la localisation et à la navigation dans différents environnements. Ils utilisent des capteurs avancés pour identifier les positions, guider les utilisateurs efficacement et optimiser les déplacements dans les espaces éducatifs ou industriels.</p>
                    <a href="#" class="panel-btn">Accéder</a>
                </div>
            </div>
            <div class="category-panel" data-category="analyse">
                <div class="category-toggle">
                    <span class="category-toggle-name">Analyse et Gestion</span>
                </div>
                <div class="category-content">
                    <h3 class="panel-title">Analyse et Gestion</h3>
                    
                    <p class="panel-text">Ces robots sont spécialisés dans l'analyse de données et la gestion intelligente des systèmes. Ils traitent les informations en temps réel, génèrent des rapports précis et aident à optimiser les performances des environnements éducatifs et professionnels.</p>
                    <a href="#" class="panel-btn">Accéder</a>
                </div>
            </div>
        </div>
    </section>

    <section class="reviews-section">
        <h2 class="reviews-title">Avis de nos clients</h2>
        <div class="reviews-grid">
            <div class="review-card">
                <div class="review-avatar">ABS</div>
                <div class="review-stars">
                    <svg class="review-star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg class="review-star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg class="review-star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg class="review-star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg class="review-star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <h3 class="review-name">Ahmed Ben Salah</h3>
                <p class="review-text">Les robots sont très utiles pour l'éducation, excellent travail de l'équipe RDOC. Mes élèves adorent apprendre avec Aisar.</p>
            </div>
            <div class="review-card">
                <div class="review-avatar">YT</div>
                <div class="review-stars">
                    <svg class="review-star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg class="review-star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg class="review-star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg class="review-star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg class="review-star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <h3 class="review-name">Youssef Trabelsi</h3>
                <p class="review-text">Interface simple et technologie impressionnante. Aivish nous aide greatly dans la gestion quotidienne de notre établissement.</p>
            </div>
            <div class="review-card">
                <div class="review-avatar">MBY</div>
                <div class="review-stars">
                    <svg class="review-star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg class="review-star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg class="review-star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg class="review-star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    <svg class="review-star" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                </div>
                <h3 class="review-name">Mariem Ben Yedder</h3>
                <p class="review-text">Très bon produit, je recommande fortement pour les écoles. Le robot Aihrus a transformé notre façon de travailler.</p>
            </div>
        </div>
        <a href="avis.php" class="reviews-read-more-btn">Lire plus</a>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        // Stats section animation
        const statItems = document.querySelectorAll('.stat-item');
        
        gsap.fromTo(statItems, 
            { opacity: 0, y: 50, scale: 0.8 },
            {
                opacity: 1,
                y: 0,
                scale: 1,
                duration: 0.8,
                ease: "back.out(1.7)",
                stagger: 0.2,
                scrollTrigger: {
                    trigger: ".stats-section",
                    start: "top 80%",
                    toggleActions: "play none none reverse"
                }
            }
        );

        // Robots Scroll Animation - Apple-style pinned scroll
        const robotsSection = document.getElementById('robots-scroll-section');
if (robotsSection) {
            const robotItems = document.querySelectorAll('.robot-scroll-item');
            const totalItems = robotItems.length;
            if (totalItems === 0) {
                robotsSection.style.display = 'none';
            } else {

            robotItems.forEach((item, i) => {
                const btn = item.querySelector('.robot-scroll-btn');
                gsap.set(item, { opacity: 0, scale: 0.8 });
                gsap.set(btn, { opacity: 0, y: 15 });
            });

            gsap.set(robotItems[0], { opacity: 1, scale: 1 });
            gsap.set(robotItems[0].querySelector('.robot-scroll-btn'), { opacity: 1, y: 0 });

            ScrollTrigger.create({
                trigger: robotsSection,
                start: "top top",
                end: "+=" + (totalItems * 100) + "%",
                pin: true,
                scrub: 1,
                anticipatePin: 1,
                onUpdate: (self) => {
                    const progress = self.progress;
                    const activeIndex = Math.min(Math.floor(progress * totalItems), totalItems - 1);

                    robotItems.forEach((item, i) => {
                        const btn = item.querySelector('.robot-scroll-btn');
                        const isActive = i === activeIndex;

                        gsap.to(item, {
                            opacity: isActive ? 1 : 0,
                            scale: isActive ? 1 : 0.8,
                            duration: 0.4,
                            ease: "power2.out",
                            pointerEvents: isActive ? "auto" : "none"
                        });

                        gsap.to(btn, {
                            opacity: isActive ? 1 : 0,
                            y: isActive ? 0 : 15,
                            duration: 0.4,
                            ease: "power2.out",
                            delay: isActive ? 0.15 : 0
                        });

                        item.setAttribute('data-active', isActive);
                    });
                }
            });
            }
        }
            ;
        

        // Hero Robot Carousel
        let currentRobot = 0;
        const robots = document.querySelectorAll('.hero-robot');
        const dots = document.querySelectorAll('.carousel-dot');
        const totalRobots = robots.length;

        function changeRobot(direction) {
            robots[currentRobot].classList.remove('active');
            dots[currentRobot].classList.remove('active');
            
            currentRobot = (currentRobot + direction + totalRobots) % totalRobots;
            
            robots[currentRobot].classList.add('active');
            dots[currentRobot].classList.add('active');
        }

        function goToRobot(index) {
            robots[currentRobot].classList.remove('active');
            dots[currentRobot].classList.remove('active');
            
            currentRobot = index;
            
            robots[currentRobot].classList.add('active');
            dots[currentRobot].classList.add('active');
        }

        const menuToggle = document.getElementById('menu-toggle');
        const dropdown = document.getElementById('dropdown');
        const line1 = document.getElementById('line1');
        const line2 = document.getElementById('line2');

        menuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                dropdown.classList.remove('dropdown-exit');
                dropdown.classList.add('dropdown-enter');
                line1.style.transform = 'rotate(45deg) translate(5px, 6px)';
                line2.style.transform = 'rotate(-45deg) translate(5px, -6px)';
            } else {
                dropdown.classList.add('dropdown-exit');
                dropdown.classList.remove('dropdown-enter');
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 300);
                line1.style.transform = 'none';
                line2.style.transform = 'none';
            }
        });

        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target) && !menuToggle.contains(e.target)) {
                dropdown.classList.add('hidden');
                dropdown.classList.remove('dropdown-enter');
                dropdown.classList.add('dropdown-exit');
                line1.style.transform = 'none';
                line2.style.transform = 'none';
            }
        });

        const categoryPanels = document.querySelectorAll('.category-panel');
        let activePanel = null;

        categoryPanels.forEach(panel => {
            const toggle = panel.querySelector('.category-toggle');
            const content = panel.querySelector('.category-content');

            gsap.set(content, { width: 0, opacity: 0, padding: 0 });

            toggle.addEventListener('click', () => {
                const isActive = panel.classList.contains('active');

                if (activePanel && activePanel !== panel) {
                    const prevContent = activePanel.querySelector('.category-content');
                    gsap.to(prevContent, {
                        width: 0,
                        opacity: 0,
                        padding: 0,
                        duration: 0.5,
                        ease: "power2.inOut"
                    });
                    activePanel.classList.remove('active');
                }

                if (!isActive) {
                    panel.classList.add('active');
                    gsap.to(content, {
                        width: 550,
                        opacity: 1,
                        padding: '60px 50px',
                        duration: 0.6,
                        ease: "power3.out"
                    });
                    activePanel = panel;
                } else {
                    gsap.to(content, {
                        width: 0,
                        opacity: 0,
                        padding: 0,
                        duration: 0.5,
                        ease: "power2.inOut"
                    });
                    panel.classList.remove('active');
                    activePanel = null;
                }
            });
        });
    </script>
    <script src="assets/js/main.js"></script>
<?php include 'includes/footer.php'; ?>
<script src="assets/js/nav.js"></script>
</body>
</html>
