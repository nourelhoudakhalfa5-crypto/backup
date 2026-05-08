<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'includes/pdo.php';

function redirect_products(): void
{
    header('Location: dashboard.php?page=rubick-side-menu-product-list');
    exit;
}

function upload_product_image(?array $file, ?string $currentPath = null): ?string
{
    if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return $currentPath;
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Image upload failed.');
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];
    $mime = mime_content_type($file['tmp_name']);

    if (!isset($allowed[$mime])) {
        throw new RuntimeException('Invalid image type.');
    }

    $dir = __DIR__ . '/assets/images/uploads';
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }

    $filename = 'robot-' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
    $target = $dir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $target)) {
        throw new RuntimeException('Unable to store image.');
    }

    return 'assets/images/uploads/' . $filename;
}

$action = $_POST['action'] ?? $_GET['action'] ?? 'save';

try {
    if ($action === 'delete') {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            redirect_products();
        }

        $stmt = $pdo->prepare('DELETE FROM produits WHERE id = :id');
        $stmt->execute(['id' => $id]);
        redirect_products();
    }

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: null;
    $name = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float) str_replace(',', '.', $_POST['prix'] ?? '0');
    $categoryId = filter_input(INPUT_POST, 'categorie_id', FILTER_VALIDATE_INT) ?: null;
    $stock = filter_input(INPUT_POST, 'stock', FILTER_VALIDATE_INT);
    $stock = $stock === false || $stock === null ? 0 : $stock;
    $status = ($_POST['statut'] ?? 'inactif') === 'actif' ? 'actif' : 'inactif';

    if ($name === '') {
        throw new RuntimeException('Product name is required.');
    }

    $currentImage = null;
    if ($id) {
        $stmt = $pdo->prepare('SELECT image_url FROM produits WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $currentImage = $stmt->fetchColumn() ?: null;
    }

    $imagePath = upload_product_image($_FILES['image'] ?? null, $currentImage);

    if ($id) {
        $stmt = $pdo->prepare(
            'UPDATE produits
             SET nom = :nom, description = :description, prix = :prix, image_url = :image_url,
                 categorie_id = :categorie_id, stock = :stock, statut = :statut
             WHERE id = :id'
        );
        $stmt->execute([
            'nom' => $name,
            'description' => $description,
            'prix' => $price,
            'image_url' => $imagePath,
            'categorie_id' => $categoryId,
            'stock' => $stock,
            'statut' => $status,
            'id' => $id,
        ]);
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO produits (nom, description, prix, image_url, categorie_id, stock, statut)
             VALUES (:nom, :description, :prix, :image_url, :categorie_id, :stock, :statut)'
        );
        $stmt->execute([
            'nom' => $name,
            'description' => $description,
            'prix' => $price,
            'image_url' => $imagePath,
            'categorie_id' => $categoryId,
            'stock' => $stock,
            'statut' => $status,
        ]);
    }

    redirect_products();
} catch (Throwable $e) {
    header('Location: dashboard.php?page=rubick-side-menu-add-product&error=1');
    exit;
}
?>
