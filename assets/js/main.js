


document.addEventListener("DOMContentLoaded", () => {
    const currentPage = normalizeCurrentPage();

    injectChatbot();

    applyStoredTheme();
    initThemeToggle();
    initCustomCursor();

    bindVideoModal();
    bindFaqAccordion();
    bindPourquoiTabs();
    bindNavigationInteractivity();
});

function normalizeCurrentPage() {
    const path = window.location.pathname.split("/").pop() || "index.html";
    if (path.startsWith("produit")) {
        return "produit.html";
    }
    return path;
}


function bindNavigationInteractivity() {
    const body = document.body;
    const menu = document.getElementById("rdocc-menu");
    const toggle = document.querySelector("[data-rdocc-toggle]");
    const close = document.querySelector("[data-rdocc-close]");
    const backdrop = document.querySelector("[data-rdocc-backdrop]");
    const links = document.querySelectorAll(".rdocc-menu__link");

    if (!body || !menu || !toggle) return;

    const setOpen = (isOpen) => {
        body.classList.toggle("rdocc-menu-open", isOpen);
        menu.classList.toggle("active", isOpen);
        toggle.setAttribute("aria-expanded", String(isOpen));
        menu.setAttribute("aria-hidden", String(!isOpen));
        body.style.overflow = isOpen ? "hidden" : "";
    };

    toggle.addEventListener("click", () => {
        setOpen(!body.classList.contains("rdocc-menu-open"));
    });

    close?.addEventListener("click", () => setOpen(false));
    backdrop?.addEventListener("click", () => setOpen(false));
    links.forEach((link) => link.addEventListener("click", () => setOpen(false)));

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            setOpen(false);
        }
    });
}

function applyStoredTheme() {
    const savedTheme = localStorage.getItem(RDOC_THEME_KEY);
    if (savedTheme === "light") {
        document.body.classList.add("rdocc-theme-light");
    }
}

function initThemeToggle() {
    const toggle = document.querySelector("[data-rdocc-theme-toggle]");
    if (!toggle) return;

    toggle.addEventListener("click", () => {
        const isLight = document.body.classList.toggle("rdocc-theme-light");
        localStorage.setItem(RDOC_THEME_KEY, isLight ? "light" : "dark");
    });
}

function initCustomCursor() {
    const existingCursor = document.querySelector(".rdocc-cursor");
    existingCursor?.remove();
    document.documentElement.style.cursor = "auto";
    document.body.style.cursor = "auto";
}


function bindVideoModal() {
    const videoTrigger = document.getElementById("videoTrigger");
    const videoModal = document.getElementById("videoModal");
    const closeModal = document.querySelector(".close-modal");
    const videoIframe = document.getElementById("videoIframe");

    if (!videoTrigger || !videoModal || !closeModal || !videoIframe) return;

    const closeVideo = () => {
        videoModal.style.display = "none";
        videoIframe.src = "";
        if (!document.body.classList.contains("rdocc-menu-open")) {
            document.body.style.overflow = "";
        }
    };

    videoTrigger.addEventListener("click", () => {
        videoIframe.src = "https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1";
        videoModal.style.display = "block";
        document.body.style.overflow = "hidden";
    });

    closeModal.addEventListener("click", closeVideo);
    videoModal.addEventListener("click", (event) => {
        if (event.target === videoModal) {
            closeVideo();
        }
    });
}

function bindFaqAccordion() {
    const faqItems = document.querySelectorAll(".faq-item");
    if (!faqItems.length) return;

    faqItems.forEach((item) => {
        item.addEventListener("click", () => {
            const isOpen = item.classList.contains("open");
            faqItems.forEach((currentItem) => currentItem.classList.remove("open"));
            if (!isOpen) {
                item.classList.add("open");
            }
        });
    });
}

function bindPourquoiTabs() {
    const pourquoiTabs = document.querySelectorAll(".pourquoi-tab");
    const pourquoiBox = document.querySelector(".pourquoi-box");
    if (!pourquoiTabs.length || !pourquoiBox) return;

    const titleElement = pourquoiBox.querySelector("h3");
    const textElement = pourquoiBox.querySelector("p");

    pourquoiTabs.forEach((tab) => {
        tab.addEventListener("click", () => {
            const { title, text } = tab.dataset;
            if (!titleElement || !textElement || !title || !text) return;

            pourquoiTabs.forEach((currentTab) => currentTab.classList.remove("active"));
            tab.classList.add("active");
            pourquoiBox.classList.add("updating");

            window.setTimeout(() => {
                titleElement.textContent = title;
                textElement.textContent = text;
                pourquoiBox.classList.remove("updating");
            }, 150);
        });
    });
}

function injectChatbot() {
    if (document.querySelector('.rdocc-chatbot-btn')) return;
    
    const chatbotHTML = `
        <a href="chatbot.php" class="rdocc-chatbot-btn" aria-label="Ouvrir le chatbot">
            <img src="assets/images/icon-chatbot.png" alt="Chatbot" />
        </a>
    `;
    
    document.body.insertAdjacentHTML('beforeend', chatbotHTML);
}
