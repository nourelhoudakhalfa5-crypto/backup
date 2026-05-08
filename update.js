const fs = require('fs');

const cssBlock = `<style>
    @keyframes fadeSlideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeSlideUp { from { opacity: 1; transform: translateY(0); } to { opacity: 0; transform: translateY(-10px); } }
    .dropdown-enter { animation: fadeSlideDown 0.3s ease-out forwards; }
    .dropdown-exit { animation: fadeSlideUp 0.3s ease-out forwards; }
    .favicon-logo { width: 40px; height: 40px; border-radius: 50%; overflow: hidden; flex-shrink: 0; }
    .favicon-logo img { width: 100%; height: 100%; object-fit: cover; transform: rotate(0deg); transition: transform 0.2s ease-out; }
    .favicon-logo:hover img { animation: spin 0.5s linear infinite; cursor: pointer; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    .menu-item { transition: all 0.2s ease; position: relative; }
    .menu-item:hover { color: #5CC4E5; font-weight: bold; }
    .menu-item::after { content: ''; position: absolute; bottom: -4px; left: 50%; width: 0; height: 2px; background: #5CC4E5; transform: translateX(-50%); transition: width 0.2s ease; }
    .menu-item:hover::after { width: 60%; }
</style>`;

const menuJs = `
        // ========== MENU TOGGLE ==========
        const menuBtn = document.getElementById('menu-toggle');
        const dropdown = document.getElementById('dropdown');
        const line1 = document.getElementById('line1');
        const line2 = document.getElementById('line2');

        if (menuBtn && dropdown) {
            menuBtn.addEventListener('click', () => {
                const isHidden = dropdown.classList.contains('hidden');
                
                if (isHidden) {
                    dropdown.classList.remove('hidden');
                    dropdown.classList.add('dropdown-enter');
                    dropdown.classList.remove('dropdown-exit');
                    
                    if(line1) line1.style.transform = 'translateY(4px) rotate(45deg)';
                    if(line2) line2.style.transform = 'translateY(-4px) rotate(-45deg)';
                } else {
                    dropdown.classList.add('dropdown-exit');
                    dropdown.classList.remove('dropdown-enter');
                    
                    if(line1) line1.style.transform = 'none';
                    if(line2) line2.style.transform = 'none';
                    
                    setTimeout(() => {
                        dropdown.classList.add('hidden');
                    }, 300);
                }
            });
        }
`;

const gsapTags = `    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>`;

const newNav = `   <nav class="fixed top-2 left-1/2 -translate-x-1/2 z-50 w-[30%] max-w-3xl" id="navbar">
    <div class="bg-white rounded-2xl px-7 py-2 flex items-center gap-3 transition-all duration-300" id="navbar-inner">
        <a href="#" class="flex items-center">
            <div class="favicon-logo">
                <img src="assets/favicon.png" alt="Favicon">
            </div>
        </a>

        <button id="menu-toggle" class="ml-auto flex flex-col justify-center items-center w-8 h-8 gap-1.5 cursor-pointer p-1">
            <span id="line1" class="block w-6 h-0.5 bg-black transition-all duration-300"></span>
            <span id="line2" class="block w-6 h-0.5 bg-black transition-all duration-300"></span>
        </button>

      
    </div>

<!-- FIXED DROPDOWN -->
    <div id="dropdown" class="absolute top-full left-0 mt-2 bg-white rounded-2xl p-6 hidden w-full">

        <div class="grid grid-cols-3 gap-4 mb-6">
            <a href="index.html" class="menu-item text-black text-center text-sm">Accueil</a>
            <a href="apropos.html" class="menu-item text-black text-center text-sm">A propos</a>
            <a href="categorie.html" class="menu-item text-black text-center text-sm">Categories</a>
            <a href="produit.html" class="menu-item text-black text-center text-sm">Produits</a>
            <a href="blog.html" class="menu-item text-black text-center text-sm">Blog</a>
            <a href="contact.html" class="menu-item text-black text-center text-sm">Contact</a>
        </div>

        <div class="flex justify-center">
            <a href="register.html" id="open-auth-btn" class="bg-[#5CC4E5] text-black font-bold px-8 py-3 rounded-full text-sm transition-transform duration-200 hover:scale-105 text-center">
                S'inscrire
            </a>
        </div>

    </div>

</nav>`;

const newFooter = `<footer class="site-footer">
        <div class="footer-container">
            <div class="footer-brand">
                <img src="assets/logo complete.png" alt="Logo" class="footer-logo">
                <div class="social-icons">
                    <a href="#" class="social-icon" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                    </a>
                    <a href="#" class="social-icon" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                    </a>
                    <a href="#" class="social-icon" aria-label="LinkedIn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                    </a>
                    <a href="mailto:contact@nvrdoc.com" class="social-icon" aria-label="Email">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </a>
                </div>
            </div>
            <div class="footer-newsletter">
                <h4 class="footer-heading">Suivez nos actualités</h4>
                <p class="newsletter-desc">Recevez nos dernières actualités, offres et conseils directement dans votre boîte mail.</p>
                <form class="newsletter-form">
                    <input type="email" class="newsletter-input" placeholder="Adresse email">
                    <button type="submit" class="newsletter-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    </button>
                </form>
            </div>
            <div class="footer-links">
                <ul class="footer-list">
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                    <li><a href="#">Termes et conditions</a></li>
                    <li><a href="#">Livraison et retours</a></li>
                    <li><a href="#">Contactez nous</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 RDOC. All rights reserved.</p>
        </div>
    </footer>`;

const gsapScrollJs = `
        // ========== HERO TEXT ANIMATIONS ==========
        (function() {
            if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;
            gsap.registerPlugin(ScrollTrigger);

            const subtitle = document.querySelector('.hero-animate-subtitle');
            const title = document.querySelector('.hero-animate-title');
            const description = document.querySelector('.hero-animate-description');
            const cta = document.querySelector('.hero-animate-cta');

            if(title) {
                gsap.set([subtitle, title, description, cta], { opacity: 0, y: (i) => i === 1 ? 0 : 20 + (i * 10) });
                gsap.set([title, cta], { scale: (i) => i === 0 ? 1 : 0.95 });

                const tl = gsap.timeline({ defaults: { ease: 'power3.out' } });

                tl.to(subtitle, { opacity: 1, y: 0, duration: 1, ease: 'power2.out' }, 0.3)
                  .to(title, { opacity: 1, scale: 1, duration: 1.2, ease: 'power3.out' }, 0.6)
                  .to(description, { opacity: 1, y: 0, duration: 1, ease: 'power2.out' }, 1.1)
                  .to(cta, { opacity: 1, scale: 1, duration: 0.8, ease: 'back.out(1.5)' }, 1.5);
            }

            // ========== SECOND SECTION ANIMATIONS (FEATURE 1) ==========
            const feature1 = document.querySelector('section:nth-of-type(2)');
            if (feature1) {
                const featureImage = feature1.querySelector('img');
                const featureTitle = feature1.querySelector('h2');
                const featureText = feature1.querySelector('p');
                const featureButton = feature1.querySelector('a');

                gsap.set([featureImage, featureTitle, featureText, featureButton], { opacity: 0, y: 40 });

                gsap.to([featureImage], {
                    scrollTrigger: { trigger: feature1, start: 'top 80%' },
                    opacity: 1, y: 0, scale: 1, duration: 1, ease: 'power2.out'
                });

                gsap.to([featureTitle, featureText, featureButton], {
                    scrollTrigger: { trigger: feature1, start: 'top 75%' },
                    opacity: 1, y: 0, duration: 0.8, stagger: 0.15, ease: 'power2.out'
                });
            }

            // ========== FEATURE 2 (POINTERS) ANIMATIONS ==========
            const feature2 = document.querySelector('section:nth-of-type(3)');
            if (feature2) {
                const pointers = feature2.querySelectorAll('.flex.items-center');
                const centerImage = feature2.querySelectorAll('div:nth-child(2) img');
                const rightTitle = feature2.querySelector('div:last-child h2');
                const rightText = feature2.querySelector('div:last-child p');
                const rightButton = feature2.querySelector('div:last-child a');

                gsap.set([...pointers, ...centerImage, rightTitle, rightText, rightButton], { opacity: 0, y: 30 });

                gsap.to(pointers, {
                    scrollTrigger: { trigger: feature2, start: 'top 80%' },
                    opacity: 1, y: 0, duration: 0.6, stagger: 0.1, ease: 'power2.out'
                });

                gsap.to(centerImage, {
                    scrollTrigger: { trigger: feature2, start: 'top 75%' },
                    opacity: 1, scale: 1, duration: 1, ease: 'power2.out', delay: 0.2
                });

                gsap.to([rightTitle, rightText, rightButton], {
                    scrollTrigger: { trigger: feature2, start: 'top 70%' },
                    opacity: 1, y: 0, duration: 0.7, stagger: 0.12, ease: 'power2.out', delay: 0.3
                });
            }
        })();
`;

function processFile(file) {
    let content = fs.readFileSync(file, 'utf8');

    // 1. Remove old duplicated nav blocks
    content = content.replace(/<div class="menu">[\s\S]*?<\/svg>\s*<\/div>/g, '');
    content = content.replace(/<nav\s+class="fixed top-2[\s\S]*?id="navbar">[\s\S]*?<\/nav>/g, '');
    
    // 2. Insert new nav right after body
    content = content.replace(/<body[^>]*>/, match => match + '\n' + newNav + '\n');
    
    // 3. Insert CSS if not present
    if (!content.includes('.dropdown-enter')) {
        content = content.replace('</head>', cssBlock + '\n</head>');
    }
    
    // 4. Replace footer
    content = content.replace(/<footer class="site-footer">[\s\S]*?<\/footer>/, newFooter);
    
    // 5. Add GSAP tags if not present
    if (!content.includes('gsap.min.js')) {
        content = content.replace('</head>', gsapTags + '\n</head>');
    }

    // 6. Remove existing GSAP animation blocks so we don't duplicate
    content = content.replace(/\/\/\s*========== HERO TEXT ANIMATIONS ==========[\s\S]*?\)\(\);/g, '');
    
    // 7. Add JS to script
    let newScriptContent = '\n<script>\n' + menuJs + '\n' + gsapScrollJs + '\n</script>\n';
    
    content = content.replace(/<script>\s*\/\/ ========== MENU TOGGLE ==========[\s\S]*?<\/script>/g, '');

    content = content.replace(/<\/body>/, newScriptContent + '</body>');

    fs.writeFileSync(file, content);
}

processFile('produit-aisar.html');
processFile('produit-aihrus.html');
processFile('produit-aivish.html');

console.log('Files processed successfully.');
