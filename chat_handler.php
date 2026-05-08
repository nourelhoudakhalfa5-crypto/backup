<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);
error_reporting(0);

// 1- configuration
// Remplacez par votre clé API réelle si nécessaire
$apiKey = "YOUR_GEMINI_API_KEY"; 
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

// 2- Récupération des données
$inputData = json_decode(file_get_contents('php://input'), true);
$userMessage = $inputData['message'] ?? '';

if (empty(trim($userMessage))) {
    echo json_encode(['reply' => "Veuillez taper un message pour que je puisse vous aider."]);
    exit;
}

// --- Base de connaissances locale (Fallback) ---
$knowledgeBase = [
    [
        'keywords' => ['qui es-tu', 'rdoc', 'robotics digital operating center', 'plateforme', 'ecosysteme'],
        'answer' => "Je suis l'assistant officiel de RDOC (Robotics Digital Operating Center). RDOC est un écosystème innovant alliant robotique intelligente et digitalisation à travers 4 catégories : Éducative, Informative, Localisative et Gestion de données."
    ],
    [
        'keywords' => ['robot', 'aisar', 'aivish', 'aihrus'],
        'answer' => "RDOC propose 3 robots : \n1. **AISAR** (Petit) : Éducatif et interactif.\n2. **AIVISH** (Grand) : Informations, gestion et analyse pro.\n3. **AIHRUS** (Grand) : Expert en analyse de données business.\nNote : Pour AIVISH et AIHRUS, un meeting professionnel est obligatoire avant tout achat."
    ],
    [
        'keywords' => ['academy', 'formation', 'bootcamp', 'cours', 'coding', 'ia', 'data'],
        'answer' => "RDOC Academy est notre académie digitale proposant des bootcamps intensifs, des formations professionnelles en coding, IA, Data Analytics et des ateliers de robotique pratique."
    ],
    [
        'keywords' => ['prix', 'acheter', 'cost', 'buy'],
        'answer' => "Le prix dépend de votre besoin spécifique. Pour nos robots pro (AIVISH, AIHRUS), nous fonctionnons par formulaire de contact et rendez-vous obligatoire pour définir la solution adaptée."
    ],
    [
        'keywords' => ['bonjour', 'salut', 'aslama', 'hello'],
        'answer' => "Aslama! Bonjour! Je suis l'assistant RDOC. Comment puis-je vous aider à explorer notre univers de robotique et de digitalisation aujourd'hui ?"
    ],
];

/**
 * Recherche dans la base de connaissances locale.
 */
function searchKnowledgeBase($message, $knowledgeBase)
{
    $message = strtolower(trim($message));
    $bestMatch = null;
    $bestScore = 0;

    foreach ($knowledgeBase as $entry) {
        $score = 0;
        foreach ($entry['keywords'] as $keyword) {
            if (strpos($message, strtolower($keyword)) !== false) {
                $score++;
            }
        }
        if ($score > $bestScore) {
            $bestScore = $score;
            $bestMatch = $entry['answer'];
        }
    }

    return $bestMatch;
}

// 3- Instructions système (Persona RDOC Final)
$systemInstruction = "Tu es l’assistant IA officiel de RDOC (Robotics Digital Operating Center).
🎯 MISSION : Aider les utilisateurs à comprendre, explorer et utiliser l’écosystème RDOC.
🌍 LANGUES : Français, Anglais, Arabe tunisien (darija). Réponds toujours dans la langue de l'utilisateur.

🤖 IDENTITÉ RDOC : Robotique intelligente et digitalisation. 4 catégories : Éducative, Informative, Localisative, Gestion & Analyse des données.

🤖 ROBOTS :
1) AISAR (Petit) : Éducation, apprentissage interactif, localisation simple.
2) AIVISH (Grand) : Infos, localisation avancée, analyse/gestion de données. (Achat via formulaire + meeting obligatoire).
3) AIHRUS (Grand) : Expert Analyse de données, gestion intelligente, BI. (Contact direct + meeting obligatoire).

🏫 RDOC ACADEMY : Bootcamps intensifs, formations (Coding, IA, Data Analytics), Ateliers robotique pratiques.

🧠 COMPORTEMENT : Pro, friendly, motivant. Guide l'utilisateur étape par étape. Ne jamais inventer de prix. Focus uniquement sur RDOC.";

$data = [
    "contents" => [
        ["role" => "user", "parts" => [["text" => "System Instruction:\n" . $systemInstruction . "\n\nUser Message: " . $userMessage]]]
    ]
];

$options = [
    'http' => [
        'header' => "Content-type: application/json\r\n",
        'method' => 'POST',
        'content' => json_encode($data),
        'timeout' => 10,
        'ignore_errors' => true
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ],
];

// 4- Exécution
$context = stream_context_create($options);
$result = @file_get_contents($url, false, $context);

$botReply = null;

// Parser la réponse API
if ($result !== false) {
    $response = json_decode($result, true);
    if (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
        $botReply = $response['candidates'][0]['content']['parts'][0]['text'];
    }
}

// Fallback sur la base de connaissances
if ($botReply === null) {
    $botReply = searchKnowledgeBase($userMessage, $knowledgeBase);
}

// Fallback final
if ($botReply === null) {
    $botReply = "Bienvenue chez RDOC ! Je peux vous aider à choisir un robot (AISAR, AIVISH, AIHRUS) ou vous parler de notre Academy digitale. Que souhaitez-vous découvrir ?";
}

echo json_encode(['reply' => $botReply]);
?>
