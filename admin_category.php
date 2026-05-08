<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'includes/pdo.php';

function redirect_categories(): void
{
    header('Location: dashboard.php?page=rubick-side-menu-categories');
    exit;
}

function upload_category_image(?array $file, ?string $currentPath = null): ?string
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

    $filename = 'category-' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
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
            redirect_categories();
        }

        $stmt = $pdo->prepare('DELETE FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
        redirect_categories();
    }

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: null;
    $name = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = ($_POST['statut'] ?? 'actif') === 'inactif' ? 'inactif' : 'actif';

    if ($name === '') {
        throw new RuntimeException('Category name is required.');
    }

    $currentImage = null;
    if ($id) {
        $stmt = $pdo->prepare('SELECT image FROM categories WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $currentImage = $stmt->fetchColumn() ?: null;
    }

    $imagePath = upload_category_image($_FILES['image'] ?? null, $currentImage);

    if ($id) {
        $stmt = $pdo->prepare(
            'UPDATE categories
             SET nom = :nom, description = :description, image = :image, statut = :statut
             WHERE id = :id'
        );
        $stmt->execute([
            'nom' => $name,
            'description' => $description,
            'image' => $imagePath,
            'statut' => $status,
            'id' => $id,
        ]);
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO categories (nom, description, image, statut)
             VALUES (:nom, :description, :image, :statut)'
        );
        $stmt->execute([
            'nom' => $name,
            'description' => $description,
            'image' => $imagePath,
            'statut' => $status,
        ]);
    }

    redirect_categories();
} catch (Throwable $e) {
    header('Location: dashboard.php?page=rubick-side-menu-categories&error=1');
    exit;
}
?>
