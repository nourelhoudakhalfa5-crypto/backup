const lenis = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            orientation: 'vertical',
            smoothWheel: true,
        });

        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }

        requestAnimationFrame(raf);

        const menuToggle = document.getElementById('menu-toggle');
        const navbarInner = document.getElementById('navbar-inner');
        const dropdown = document.getElementById('dropdown');
        const backToLogin = document.getElementById('back-to-login');
        const line1 = document.getElementById('line1');
        const line2 = document.getElementById('line2');
        const careerCards = document.querySelectorAll('.career-card');
        const carouselTrack = document.getElementById('carousel-track-inner');
        const horizontalCarousel = document.getElementById('horizontal-carousel');

        let isMenuOpen = false;

        lenis.on('scroll', () => {
            if (horizontalCarousel && carouselTrack) {
                const rect = horizontalCarousel.getBoundingClientRect();
                const scrollY = window.scrollY;
                const viewportH = window.innerHeight;

                const sectionTop = rect.top;
                const sectionHeight = horizontalCarousel.offsetHeight;
                
                const scrollableDistance = sectionHeight - viewportH;
                
                if (sectionTop <= 0 && sectionTop > -scrollableDistance) {
                    const progress = Math.max(0, Math.min(1, -sectionTop / scrollableDistance));
                    
                    const cardWidth = 500 + 48;
                    const totalWidth = cardWidth * careerCards.length;
                    const viewportWidth = window.innerWidth;
                    const maxTranslate = totalWidth - viewportWidth + 200;
                    
                    const translateX = -progress * maxTranslate;
                    carouselTrack.style.transform = `translateX(${translateX}px)`;
                    
                    const cardCount = careerCards.length;
                    const segmentSize = 1 / cardCount;
                    const activeIndex = Math.min(cardCount - 1, Math.floor(progress / segmentSize));

                    careerCards.forEach((card, index) => {
                        if (index === activeIndex) {
                            card.classList.add('active');
                            card.classList.remove('dimmed');
                        } else if (index < activeIndex) {
                            card.classList.remove('active');
                            card.classList.add('dimmed');
                        } else {
                            card.classList.remove('active');
                            card.classList.remove('dimmed');
                        }
                    });
                } else if (sectionTop > 0) {
                    carouselTrack.style.transform = 'translateX(0)';
                    careerCards.forEach(card => {
                        card.classList.remove('active', 'dimmed');
                    });
                }
            }
        });

        const performanceCards = document.querySelectorAll('.performance-card');
        const performanceTrack = document.getElementById('performance-track');
        const performanceSection = document.querySelector('.carousel-performance-section');
        const performanceViewport = document.getElementById('performance-viewport');

        let isDragging = false;
        let startX = 0;
        let currentTranslate = 0;
        let prevTranslate = 0;

        performanceViewport.addEventListener('mousedown', (e) => {
            isDragging = true;
            startX = e.pageX;
            performanceViewport.classList.add('dragging');
        });

        performanceViewport.addEventListener('touchstart', (e) => {
            isDragging = true;
            startX = e.touches[0].pageX;
            performanceViewport.classList.add('dragging');
        }, { passive: true });

        performanceViewport.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            const currentX = e.pageX;
            const diff = currentX - startX;
            currentTranslate = prevTranslate + diff;
            performanceTrack.style.transform = `translateX(${currentTranslate}px)`;
        });

        performanceViewport.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            const currentX = e.touches[0].pageX;
            const diff = currentX - startX;
            currentTranslate = prevTranslate + diff;
            performanceTrack.style.transform = `translateX(${currentTranslate}px)`;
        }, { passive: true });

        const endDrag = () => {
            isDragging = false;
            prevTranslate = currentTranslate;
            performanceViewport.classList.remove('dragging');
        };

        performanceViewport.addEventListener('mouseup', endDrag);
        performanceViewport.addEventListener('mouseLeave', endDrag);
        performanceViewport.addEventListener('touchend', endDrag);

        lenis.on('scroll', () => {
            if (performanceSection && performanceTrack) {
                const rect = performanceSection.getBoundingClientRect();
                const sectionTop = rect.top;
                const sectionHeight = performanceSection.offsetHeight;
                const viewportH = window.innerHeight;

                const scrollableDistance = sectionHeight - viewportH;

                if (sectionTop <= 0 && sectionTop > -scrollableDistance) {
                    const progress = Math.max(0, Math.min(1, -sectionTop / scrollableDistance));

                    const cardWidth = 480 + 32;
                    const totalWidth = cardWidth * performanceCards.length;
                    const viewportWidth = window.innerWidth;
                    const maxTranslate = totalWidth - viewportWidth + 200;

                    const scrollTranslate = -progress * maxTranslate;

                    if (!isDragging) {
                        currentTranslate = scrollTranslate;
                        prevTranslate = scrollTranslate;
                        performanceTrack.style.transform = `translateX(${scrollTranslate}px)`;
                    }

                    const cardCount = performanceCards.length;
                    const segmentSize = 1 / cardCount;
                    const activeIndex = Math.min(cardCount - 1, Math.floor(progress / segmentSize));

                    performanceCards.forEach((card, index) => {
                        if (index === activeIndex) {
                            card.classList.add('active');
                        } else {
                            card.classList.remove('active');
                        }
                    });
                } else if (sectionTop > 0) {
                    if (!isDragging) {
                        currentTranslate = 0;
                        prevTranslate = 0;
                        performanceTrack.style.transform = 'translateX(0)';
                    }
                    performanceCards.forEach(card => {
                        card.classList.remove('active');
                    });
                }
            }
        });

        menuToggle.addEventListener('click', () => {
            isMenuOpen = !isMenuOpen;

            if (isMenuOpen) {
                navbarInner.classList.remove('bg-white');
                navbarInner.classList.add('bg-black');
                line1.classList.remove('bg-black', 'rotate-0', 'translate-y-0');
                line1.classList.add('bg-white', 'rotate-45', 'translate-y-2');
                line2.classList.remove('bg-black', 'rotate-0', 'translate-y-0');
                line2.classList.add('bg-white', '-rotate-45', '-translate-y-2');
                dropdown.classList.remove('hidden', 'dropdown-exit');
                dropdown.classList.add('dropdown-enter');
            } else {
                closeMenu();
            }
        });

        // Dark mode toggle
        const toggle = document.querySelector("[data-rdocc-theme-toggle]");
        
        // Check for saved theme preference
        const savedTheme = localStorage.getItem('rdocc-theme');
        if (savedTheme === 'light') {
            document.body.classList.add('rdocc-theme-light');
        }

        if (toggle) {
            toggle.addEventListener('click', () => {
                const isLight = document.body.classList.toggle('rdocc-theme-light');
                localStorage.setItem('rdocc-theme', isLight ? 'light' : 'dark');
            });
        }

        function closeMenu() {
            navbarInner.classList.remove('bg-black');
            navbarInner.classList.add('bg-white');
            line1.classList.remove('bg-white', 'rotate-45', 'translate-y-2');
            line1.classList.add('bg-black', 'rotate-0', 'translate-y-0');
            line2.classList.remove('bg-white', '-rotate-45', '-translate-y-2');
            line2.classList.add('bg-black', 'rotate-0', 'translate-y-0');
            dropdown.classList.remove('dropdown-enter');
            dropdown.classList.add('dropdown-exit');
            setTimeout(() => {
                if (!isMenuOpen) {
                    dropdown.classList.add('hidden');
                }
            }, 300);
        }

        document.addEventListener('click', (e) => {
            const navbar = document.getElementById('navbar');
            if (!navbar.contains(e.target) && isMenuOpen) {
                isMenuOpen = false;
                closeMenu();
            }
        });

        gsap.registerPlugin(ScrollTrigger);

        const aboutPinned = document.getElementById('about-pinned');
        const aboutSteps = document.querySelectorAll('.about-step');
        let currentAboutStep = 0;

        if (aboutPinned && aboutSteps.length) {
            gsap.set(aboutSteps, { opacity: 0, y: 20 });
            gsap.set(aboutSteps[0], { opacity: 1, y: 0 });
            aboutSteps[0].classList.remove('hidden');

            gsap.to({}, {
                scrollTrigger: {
                    trigger: aboutPinned,
                    start: 'top top',
                    end: '+=300%',
                    pin: true,
                    scrub: 1,
                    onUpdate: (self) => {
                        const progress = self.progress;
                        const stepIndex = Math.min(Math.floor(progress * aboutSteps.length), aboutSteps.length - 1);
                        
                        if (stepIndex !== currentAboutStep) {
                            aboutSteps[currentAboutStep].classList.add('hidden');
                            aboutSteps[stepIndex].classList.remove('hidden');
                            
                            gsap.fromTo(aboutSteps[stepIndex], 
                                { opacity: 0, y: 20 },
                                { opacity: 1, y: 0, duration: 0.3 }
                            );
                            
                            gsap.to(aboutSteps[currentAboutStep], 
                                { opacity: 0, y: -20, duration: 0.3 }
                            );
                            
                            currentAboutStep = stepIndex;
                        }
                    }
                }
            });
        }

        const howSection = document.getElementById('how-section');
        const howTitleWrapper = document.querySelector('.how-title-wrapper');
        const howEtapes = document.getElementById('how-etapes');

        if (howSection && howTitleWrapper && howEtapes) {
            gsap.set(howEtapes, { scale: 0.5, opacity: 0 });

            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: howSection,
                    start: 'top top',
                    end: 'bottom bottom',
                    scrub: 1,
                }
            });

            tl.to(howTitleWrapper, {
                scale: 12,
                opacity: 0,
                duration: 3,
                ease: 'power2.inOut'
            })
            .to(howEtapes, {
                scale: 1,
                opacity: 1,
                duration: 1,
                ease: 'power2.out'
            }, '-=0.5')
            .to(howEtapes, {
                scale: 30,
                duration: 4,
                ease: 'power1.inOut'
            }, '-=1')
            .to('#how-section .how-sticky-container', {
                backgroundColor: '#ffffff',
                duration: 4,
                ease: 'power1.inOut'
            }, '-=4')
            .to('.how-etapes-text', {
                opacity: 0,
                duration: 1,
                ease: 'power2.in'
            }, '-=2');
        }

/* ============================================
           STACKING PANELS — Vanilla JS Engine (Fixed)
           ============================================ */
        (function initStackPanels() {
            'use strict';

            const section  = document.getElementById('stack-section');
            const panels   = section ? Array.from(section.querySelectorAll('.stack-panel')) : [];
            const progress = document.getElementById('stack-progress');
            const dots     = progress ? Array.from(progress.querySelectorAll('.stack-progress-dot')) : [];
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (!section || panels.length === 0) return;

            let activeIndex = -1;
            let ticking     = false;

            /* ------ Cache panel offsets (recalculated on resize) ------ */
            let panelOffsets = [];
            function cachePanelOffsets() {
                const sectionTop = section.offsetTop;
                panelOffsets = panels.map(panel => panel.offsetTop);
            }
            cachePanelOffsets();
            window.addEventListener('resize', cachePanelOffsets);

            /* ------ Detect active panel using scroll offset ------ */
            function getActivePanelIndex() {
                const scrollY = window.scrollY || window.pageYOffset;
                const sectionTop = section.offsetTop;
                const scrollInSection = scrollY - sectionTop;
                const vh = window.innerHeight;

                // Before the section
                if (scrollInSection < 0) return 0;

                // Use each panel's offsetTop within the section
                let active = 0;
                for (let i = panels.length - 1; i >= 0; i--) {
                    // Panel i is active when we've scrolled past its offset
                    const panelStart = panelOffsets[i] - panelOffsets[0];
                    if (scrollInSection >= panelStart - vh * 0.3) {
                        active = i;
                        break;
                    }
                }
                return active;
            }

            /* ------ Calculate how far into a panel we've scrolled (0 to 1) ------ */
            function getPanelProgress(index) {
                const scrollY = window.scrollY || window.pageYOffset;
                const sectionTop = section.offsetTop;
                const scrollInSection = scrollY - sectionTop;
                const vh = window.innerHeight;

                const panelStart = panelOffsets[index] - panelOffsets[0];
                const segmentProgress = (scrollInSection - panelStart + vh * 0.3) / (vh * 0.8);
                return Math.max(0, Math.min(1, segmentProgress));
            }

            /* ------ Apply animations ------ */
            function updatePanels() {
                const newActive = getActivePanelIndex();

                panels.forEach((panel, i) => {
                    const content = panel.querySelector('.stack-panel-content');
                    const bg      = panel.querySelector('.stack-panel-bg');

                    if (i === newActive) {
                        panel.classList.add('is-active');

                        if (!prefersReducedMotion && content) {
                            const p    = getPanelProgress(i);
                            const ease = p < 0.5
                                ? 4 * p * p * p
                                : 1 - Math.pow(-2 * p + 2, 3) / 2;

                            const y       = 40 * (1 - ease);
                            const scale   = 0.97 + 0.03 * ease;
                            const blur    = 6 * (1 - ease);
                            const opacity = Math.max(0.15, ease);

                            content.style.transform = 'translateY(' + y + 'px) scale(' + scale + ')';
                            content.style.opacity   = opacity;
                            content.style.filter    = 'blur(' + blur + 'px)';
                        } else if (content) {
                            content.style.transform = 'translateY(0) scale(1)';
                            content.style.opacity   = '1';
                            content.style.filter    = 'none';
                        }

                        // Subtle parallax on background
                        if (!prefersReducedMotion && bg) {
                            const rect = panel.getBoundingClientRect();
                            const parallax = rect.top * 0.08;
                            bg.style.transform = 'translateY(' + parallax + 'px)';
                        }
                    } else {
                        panel.classList.remove('is-active');

                        if (content) {
                            if (i < newActive) {
                                // Scrolled past — dimmed
                                content.style.opacity   = '0.2';
                                content.style.transform = 'translateY(-20px) scale(0.98)';
                                content.style.filter    = 'blur(2px)';
                            } else {
                                // Not yet reached — waiting below
                                content.style.opacity   = '0';
                                content.style.transform = 'translateY(40px) scale(0.97)';
                                content.style.filter    = 'blur(6px)';
                            }
                        }

                        if (!prefersReducedMotion && bg) {
                            const rect = panel.getBoundingClientRect();
                            const parallax = rect.top * 0.08;
                            bg.style.transform = 'translateY(' + parallax + 'px)';
                        }
                    }
                });

                // Update progress dots
                if (newActive !== activeIndex) {
                    activeIndex = newActive;
                    dots.forEach((dot, i) => {
                        dot.classList.toggle('is-active', i === activeIndex);
                    });
                }

                ticking = false;
            }

            /* ------ Show/hide progress indicator ------ */
            const sectionObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (progress) {
                        progress.classList.toggle('is-visible', entry.isIntersecting);
                    }
                });
            }, { threshold: 0.05 });

            sectionObserver.observe(section);

            /* ------ Scroll handler with rAF throttle ------ */
            function onScroll() {
                if (!ticking) {
                    ticking = true;
                    requestAnimationFrame(updatePanels);
                }
            }

            window.addEventListener('scroll', onScroll, { passive: true });

            // Initial render
            updatePanels();

            /* ------ Dot click: scroll to panel ------ */
            dots.forEach(dot => {
                dot.addEventListener('click', () => {
                    const idx = parseInt(dot.dataset.panel, 10);
                    if (idx >= 0 && idx < panels.length) {
                        const sectionTop = section.offsetTop;
                        const panelStart = panelOffsets[idx] - panelOffsets[0];
                        window.scrollTo({ top: sectionTop + panelStart, behavior: 'smooth' });
                    }
                });
            });

        })();

        const zoomSection = document.getElementById('zoom-section');

        if (zoomSection) {
            ScrollTrigger.create({
                trigger: zoomSection,
                start: 'top bottom',
                end: 'bottom bottom',
                scrub: false
            });
        }