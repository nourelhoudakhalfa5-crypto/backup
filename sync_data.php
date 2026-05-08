<?php
require_once 'includes/db.php';

function normalize_text(string $value): string
{
    $value = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $value = preg_replace('/\s+/', ' ', $value);
    return trim($value);
}

function normalize_category_name(string $value): string
{
    $value = normalize_text($value);
    $names = [
        'EDUCATIVE' => 'Éducative',
        'ÉDUCATIVE' => 'Éducative',
        'INFORMATIVE' => 'Informative',
        'LOCALISATIVE' => 'Localisative',
        'ANALYTIQUE ET GESTION' => 'Analytique et Gestion',
    ];

    return $names[$value] ?? ucwords(strtolower($value));
}

function extract_site_categories(): array
{
    $categoryPage = __DIR__ . '/categorie.php';
    $html = is_file($categoryPage) ? file_get_contents($categoryPage) : '';
    $imagesByPage = [
        'educative.php' => 'assets/images/educative/imageheroeducative.png',
        'informative.php' => 'assets/images/informative/Gemini_Generated_Image_eb0tv6eb0tv6eb0t 3.png',
        'localisative.php' => 'assets/images/localisative/image hero.png',
        'gestion.php' => 'assets/images/gestion/imagehero.png',
    ];

    preg_match_all(
        '/<div class="cat-card[^"]*">.*?<h2>(.*?)<\/h2>\s*<p>(.*?)<\/p>\s*<a href="([^"]+)"/s',
        $html,
        $matches,
        PREG_SET_ORDER
    );

    $categories = [];
    foreach ($matches as $match) {
        $page = trim($match[3]);
        $categories[] = [
            'nom' => normalize_category_name($match[1]),
            'description' => normalize_text(str_replace('<br>', ' ', $match[2])),
            'image' => $imagesByPage[$page] ?? 'assets/images/categories/banner.png',
        ];
    }

    return $categories;
}

$categories = extract_site_categories();

if (!$categories) {
    throw new RuntimeException('No categories found in categorie.php.');
}

function scanned_category_name(array $categories, array $needles): string
{
    foreach ($categories as $category) {
        $name = strtolower($category['nom']);
        foreach ($needles as $needle) {
            if (strpos($name, $needle) !== false) {
                return $category['nom'];
            }
        }
    }

    return $categories[0]['nom'];
}

function scanned_category_id(array $categoryIds, string $needle): ?int
{
    foreach ($categoryIds as $name => $id) {
        if (strpos(strtolower($name), $needle) !== false) {
            return $id;
        }
    }

    return null;
}

$products = [
    [
        'nom' => 'Aisar',
        'description' => 'Robot educatif intelligent qui rend l apprentissage interactif, ludique et accessible partout.',
        'prix' => 0.00,
        'image_url' => 'assets/images/product/Aisar.png',
        'categorie_key' => 'ducative',
        'stock' => 0,
    ],
    [
        'nom' => 'Aivish',
        'description' => 'Robot intelligent concu pour simplifier l apprentissage, stimuler la creativite et rendre chaque interaction plus ludique.',
        'prix' => 0.00,
        'image_url' => 'assets/images/product/Aivish.png',
        'categorie_key' => 'gestion',
        'stock' => 0,
    ],
    [
        'nom' => 'Aihrus',
        'description' => 'Robot intelligent pour accompagner les usages RDOC avec interaction, assistance et autonomie.',
        'prix' => 0.00,
        'image_url' => 'assets/images/product/Aihrus.png',
        'categorie_key' => 'localisative',
        'stock' => 0,
    ],
];

function ensureUniqueKey(mysqli $conn, string $table, string $keyName, string $column): void
{
    $stmt = $conn->prepare(
        "SELECT COUNT(*) AS total
         FROM information_schema.statistics
         WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ?"
    );
    $stmt->bind_param('ss', $table, $keyName);
    $stmt->execute();
    $exists = (int) $stmt->get_result()->fetch_assoc()['total'];
    $stmt->close();

    if ($exists === 0) {
        $conn->query("ALTER TABLE `$table` ADD UNIQUE KEY `$keyName` (`$column`)");
    }
}

function placeholders(array $items): string
{
    return implode(',', array_fill(0, count($items), '?'));
}

$categoryNames = array_column($categories, 'nom');
$productNames = array_column($products, 'nom');

$conn->begin_transaction();

try {
    $conn->query("
        DELETE c1 FROM categories c1
        INNER JOIN categories c2
            ON c1.nom = c2.nom AND c1.id > c2.id
    ");
    $conn->query("
        DELETE p1 FROM produits p1
        INNER JOIN produits p2
            ON p1.nom = p2.nom AND p1.id > p2.id
    ");

    ensureUniqueKey($conn, 'categories', 'uniq_categories_nom', 'nom');
    ensureUniqueKey($conn, 'produits', 'uniq_produits_nom', 'nom');

    $stmt = $conn->prepare("
        INSERT INTO categories (nom, description, image, statut)
        VALUES (?, ?, ?, 'actif')
        ON DUPLICATE KEY UPDATE
            description = VALUES(description),
            image = VALUES(image),
            statut = 'actif'
    ");
    foreach ($categories as $category) {
        $stmt->bind_param('sss', $category['nom'], $category['description'], $category['image']);
        $stmt->execute();
    }
    $stmt->close();

    $categoryIds = [];
    $result = $conn->query('SELECT id, nom FROM categories');
    while ($row = $result->fetch_assoc()) {
        $categoryIds[$row['nom']] = (int) $row['id'];
    }

    $stmt = $conn->prepare("
        INSERT INTO produits (nom, description, prix, image_url, categorie_id, stock, statut)
        VALUES (?, ?, ?, ?, ?, ?, 'actif')
        ON DUPLICATE KEY UPDATE
            description = VALUES(description),
            prix = VALUES(prix),
            image_url = VALUES(image_url),
            categorie_id = VALUES(categorie_id),
            stock = VALUES(stock),
            statut = 'actif'
    ");
    foreach ($products as $product) {
        $categoryId = scanned_category_id($categoryIds, $product['categorie_key']);
        $stmt->bind_param(
            'ssdsii',
            $product['nom'],
            $product['description'],
            $product['prix'],
            $product['image_url'],
            $categoryId,
            $product['stock']
        );
        $stmt->execute();
    }
    $stmt->close();

    $productPlaceholders = placeholders($productNames);
    $types = str_repeat('s', count($productNames));
    $stmt = $conn->prepare("DELETE FROM produits WHERE nom NOT IN ($productPlaceholders)");
    $stmt->bind_param($types, ...$productNames);
    $stmt->execute();
    $stmt->close();

    $categoryPlaceholders = placeholders($categoryNames);
    $types = str_repeat('s', count($categoryNames));
    $stmt = $conn->prepare("DELETE FROM categories WHERE nom NOT IN ($categoryPlaceholders)");
    $stmt->bind_param($types, ...$categoryNames);
    $stmt->execute();
    $stmt->close();

    $conn->commit();
    echo "Sync completed: " . count($categories) . " categories and " . count($products) . " products imported.\n";
} catch (Throwable $e) {
    $conn->rollback();
    http_response_code(500);
    echo "Sync failed: " . $e->getMessage() . "\n";
}
?>
