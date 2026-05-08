/* ============================================================
   RDOC AI Chatbot - Intelligence & Fonctionnalité
   ============================================================ */
document.addEventListener('DOMContentLoaded', () => {
    // Éléments DOM
    const chatMessages = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const modeText = document.getElementById('modeText');
    const modeVoice = document.getElementById('modeVoice');
    const suggestionsContainer = document.getElementById('suggestionsContainer');

    // État
    let currentMode = 'text';
    let isListening = false;
    let recognition = null;
    let synthesis = window.speechSynthesis;
    let conversationHistory = [];

    console.log('RDOC Chatbot: Initialisé.');

    // ============================================================
    // MOTEUR DE RÉPONSE IA (Logique locale fluide)
    // ============================================================
    const aiResponses = {
        greeting: [
            "Aslama! Bonjour! Je suis l'assistant officiel de RDOC. Comment puis-je vous aider à explorer notre écosystème aujourd'hui ?",
            "Bienvenue chez RDOC ! Je suis là pour vous faire découvrir nos robots (AISAR, AIVISH, AIHRUS) et notre Academy digitale. Que voulez-vous savoir ?",
            "Salut ! Prêt à entrer dans l'univers de la robotique intelligente ? Je peux vous parler de nos solutions éducatives, informatives ou de gestion."
        ],
        aisar: ["**AISAR** est notre robot éducatif. Il est petit, amical et conçu pour l'apprentissage interactif et la localisation simple en milieu scolaire."],
        aivish: ["**AIVISH** est un robot polyvalent pour les professionnels. Il gère l'information, la localisation avancée et l'analyse de données. Achat via meeting obligatoire."],
        aihrus: ["**AIHRUS** est notre expert en gestion intelligente et analyse de données business. C'est la solution la plus avancée pour les systèmes d'information."],
        academy: ["**RDOC Academy** propose des bootcamps intensifs, des formations en coding, IA, Data Analytics et des ateliers de robotique pratiques. C'est le moment de booster votre carrière !"],
        prix: ["Chez RDOC, nous proposons des solutions sur mesure. Pour les modèles professionnels, le prix est défini après un meeting obligatoire pour analyser vos besoins."],
        thanks: ["C'est un plaisir de vous accompagner !", "De rien ! N'hésitez pas si vous avez d'autres questions."],
        default: [
            "En tant qu'assistant RDOC, je peux vous renseigner sur nos robots, nos formations Academy ou nos services data. Que souhaitez-vous découvrir ?",
            "Je me spécialise dans l'univers RDOC. Voulez-vous en savoir plus sur nos solutions pour l'éducation ou pour les entreprises ?"
        ]
    };

    function getAIResponse(userMessage) {
        const msg = userMessage.toLowerCase();
        if (msg.includes('bonjour') || msg.includes('salut') || msg.includes('aslama')) return randomFrom(aiResponses.greeting);
        if (msg.includes('aisar')) return randomFrom(aiResponses.aisar);
        if (msg.includes('aivish')) return randomFrom(aiResponses.aivish);
        if (msg.includes('aihrus')) return randomFrom(aiResponses.aihrus);
        if (msg.includes('academy') || msg.includes('formation')) return randomFrom(aiResponses.academy);
        if (msg.includes('prix') || msg.includes('acheter')) return randomFrom(aiResponses.prix);
        if (msg.includes('merci')) return randomFrom(aiResponses.thanks);
        return randomFrom(aiResponses.default);
    }

    function randomFrom(arr) {
        return arr[Math.floor(Math.random() * arr.length)];
    }

    // ============================================================
    // RENDU DES MESSAGES
    // ============================================================
    function formatTime() {
        return new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    }

    function addMessage(text, type = 'bot') {
        // Masquer les suggestions au premier message
        if (suggestionsContainer) suggestionsContainer.style.display = 'none';

        const msgDiv = document.createElement('div');
        msgDiv.className = `message ${type} animate-fade-in`;
        
        const avatar = type === 'bot' 
            ? '<i class="fas fa-robot"></i>' 
            : '<i class="fas fa-user"></i>';

        msgDiv.innerHTML = `
            <div class="message-avatar" style="background: ${type==='bot'?'linear-gradient(135deg, #5cc4e5, #3a8fb5)':'rgba(255,255,255,0.1)'}">${avatar}</div>
            <div style="max-width: 80%">
                <div class="message-bubble">${text.replace(/\n/g, '<br>')}</div>
                <div class="message-time" style="font-size: 10px; color: rgba(255,255,255,0.3); margin-top: 4px; text-align: ${type==='user'?'right':'left'}">${formatTime()}</div>
            </div>
        `;
        
        chatMessages.appendChild(msgDiv);
        scrollToBottom();

        if (type === 'bot' && currentMode === 'voice') speakText(text);
    }

    function scrollToBottom() {
        chatMessages.scrollTo({ top: chatMessages.scrollHeight, behavior: 'smooth' });
    }

    function addTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.className = 'message bot animate-fade-in';
        typingDiv.id = 'typingIndicator';
        typingDiv.innerHTML = `
            <div class="message-avatar" style="background: linear-gradient(135deg, #5cc4e5, #3a8fb5)"><i class="fas fa-robot"></i></div>
            <div class="message-bubble" style="font-style: italic; color: rgba(255,255,255,0.6)">
                RDOC IA est en train d'écrire...
            </div>
        `;
        chatMessages.appendChild(typingDiv);
        scrollToBottom();
        return typingDiv;
    }

    function removeTypingIndicator() {
        const el = document.getElementById('typingIndicator');
        if (el) el.remove();
    }

    // ============================================================
    // ENVOI DE MESSAGE
    // ============================================================
    window.sendMessage = async function() {
        const text = chatInput.value.trim();
        if (!text) return;

        addMessage(text, 'user');
        chatInput.value = '';
        
        // Indicateur de frappe
        addTypingIndicator();

        // Simulation de délai IA pour le réalisme
        setTimeout(() => {
            removeTypingIndicator();
            const response = getAIResponse(text);
            addMessage(response, 'bot');
        }, 1500);
    }

    window.sendSuggestion = function(text) {
        chatInput.value = text;
        window.sendMessage();
    }

    // ============================================================
    // RECONNAISSANCE VOCALE
    // ============================================================
    window.startVoice = function() {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (!SpeechRecognition) {
            alert("La reconnaissance vocale n'est pas supportée par votre navigateur.");
            return;
        }

        if (isListening) {
            recognition.stop();
            return;
        }

        recognition = new SpeechRecognition();
        recognition.lang = 'fr-FR';
        recognition.interimResults = true;

        recognition.onstart = () => {
            isListening = true;
            chatInput.placeholder = "Je vous écoute...";
            document.querySelector('.fa-microphone').parentElement.classList.add('listening-pulse');
        };

        recognition.onresult = (event) => {
            const transcript = Array.from(event.results)
                .map(result => result[0].transcript)
                .join('');
            chatInput.value = transcript;
        };

        recognition.onend = () => {
            isListening = false;
            chatInput.placeholder = "Tapez votre message...";
            document.querySelector('.fa-microphone').parentElement.classList.remove('listening-pulse');
            if (chatInput.value.trim()) window.sendMessage();
        };

        recognition.start();
    }

    // ============================================================
    // SYNTHÈSE VOCALE
    // ============================================================
    function speakText(text) {
        if (!synthesis) return;
        synthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'fr-FR';
        synthesis.speak(utterance);
    }

    // ============================================================
    // MODES & NAVIGATION
    // ============================================================
    window.setMode = function(mode) {
        currentMode = mode;
        if (mode === 'text') {
            modeText.classList.add('active');
            modeVoice.classList.remove('active');
        } else {
            modeVoice.classList.add('active');
            modeText.classList.remove('active');
        }
    }

    // Focus automatique
    chatInput.focus();
});
