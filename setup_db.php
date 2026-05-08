<!DOCTYPE html>
<html>
<head>
    <title>Setup Database</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #000; color: #fff; }
        .success { color: #4ade80; }
        .error { color: #f87171; }
        pre { background: #1a1a1a; padding: 15px; border-radius: 8px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Setting up Database...</h1>
    <pre>
<?php
$conn = new mysqli('localhost', 'root', '', 'backup');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create subscription_plans table
$sql1 = "CREATE TABLE IF NOT EXISTS `subscription_plans` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(100) NOT NULL,
    `description` TEXT,
    `prix` DECIMAL(10,2) NOT NULL,
    `duree` ENUM('mois', 'annee') NOT NULL DEFAULT 'mois',
    `nombre_produits` INT DEFAULT 0,
    `nombre_categories` INT DEFAULT 0,
    `statut` ENUM('actif', 'inactif') NOT NULL DEFAULT 'actif',
    `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql1)) { echo "✅ subscription_plans table created\n"; } 
else { echo "❌ Error: " . $conn->error . "\n"; }

// Create blog_categories table
$sql2 = "CREATE TABLE IF NOT EXISTS `blog_categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(150) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `statut` ENUM('actif', 'inactif') NOT NULL DEFAULT 'actif',
    `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql2)) { echo "✅ blog_categories table created\n"; } 
else { echo "❌ Error: " . $conn->error . "\n"; }

// Create blog_posts table
$sql3 = "CREATE TABLE IF NOT EXISTS `blog_posts` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `titre` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `extrait` TEXT,
    `contenu` LONGTEXT,
    `image` VARCHAR(255) DEFAULT NULL,
    `categorie_id` INT DEFAULT NULL,
    `auteur_id` INT DEFAULT NULL,
    `statut` ENUM('publie', 'brouillon') NOT NULL DEFAULT 'brouillon',
    `date_publication` DATETIME DEFAULT NULL,
    `date_creation` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`categorie_id`) REFERENCES `blog_categories`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (`auteur_id`) REFERENCES `utilisateurs`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql3)) { echo "✅ blog_posts table created\n"; } 
else { echo "❌ Error: " . $conn->error . "\n"; }

// Create client_subscriptions table
$sql4 = "CREATE TABLE IF NOT EXISTS `client_subscriptions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `utilisateur_id` INT NOT NULL,
    `plan_id` INT NOT NULL,
    `date_debut` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `date_fin` DATETIME,
    `statut` ENUM('actif', 'expire', 'annule') NOT NULL DEFAULT 'actif',
    FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`plan_id`) REFERENCES `subscription_plans`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if ($conn->query($sql4)) { echo "✅ client_subscriptions table created\n"; } 
else { echo "❌ Error: " . $conn->error . "\n"; }

// Insert sample data
$result = $conn->query("SELECT COUNT(*) as cnt FROM subscription_plans");
$row = $result->fetch_assoc();
if ($row['cnt'] == 0) {
    $conn->query("INSERT INTO subscription_plans (nom, description, prix, duree, nombre_produits, nombre_categories, statut) VALUES 
        ('Basique', 'Accès basique aux produits et catégories', 99.00, 'mois', 10, 3, 'actif'),
        ('Premium', 'Accès premium avec plus de produits et catégories', 199.00, 'mois', 50, 10, 'actif'),
        ('Entreprise', 'Accès illimité pour les grandes entreprises', 499.00, 'annee', 999999, 999999, 'actif')");
    echo "✅ Sample subscription plans added\n";
}

$result = $conn->query("SELECT COUNT(*) as cnt FROM blog_categories");
$row = $result->fetch_assoc();
if ($row['cnt'] == 0) {
    $conn->query("INSERT INTO blog_categories (nom, description, statut) VALUES 
        ('Actualités', 'Les dernières nouvelles et mises à jour', 'actif'),
        ('Produits', 'Articles sur les nouveaux produits', 'actif'),
        ('Guides', 'Tutoriels et guides d\\'utilisation', 'actif')");
    echo "✅ Sample blog categories added\n";
}

echo "\n✅ Setup complete!";

$conn->close();
?>
    </pre>
    <p><a href="admin/login.php" style="color: #5CC4E5;">Go to Admin Panel</a></p>
</body>
</html>