<?php
// Admin Authentication Functions
// This file handles all admin authentication and session management

session_start();

define('ADMIN_SESSION_KEY', 'admin_user');

function adminIsLoggedIn(): bool {
    return isset($_SESSION[ADMIN_SESSION_KEY]);
}

function adminId(): ?int {
    return $_SESSION[ADMIN_SESSION_KEY]['id'] ?? null;
}

function adminName(): string {
    return $_SESSION[ADMIN_SESSION_KEY]['nom'] ?? 'Administrateur';
}

function adminEmail(): string {
    return $_SESSION[ADMIN_SESSION_KEY]['email'] ?? '';
}

function requireAdminLogin(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'client') {
        header('Location: ../login.php?error=admin_only');
        exit;
    }
    if (!adminIsLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function adminLogin(int $id, string $nom, string $email): void {
    $_SESSION[ADMIN_SESSION_KEY] = [
        'id' => $id,
        'nom' => $nom,
        'email' => $email
    ];
    $_SESSION[ADMIN_SESSION_KEY]['csrf_token'] = bin2hex(random_bytes(32));
}

function adminLogout(): void {
    unset($_SESSION[ADMIN_SESSION_KEY]);
}

function adminCsrfToken(): string {
    return $_SESSION[ADMIN_SESSION_KEY]['csrf_token'] ?? '';
}

function verifyAdminCsrfToken(string $token): bool {
    return isset($_SESSION[ADMIN_SESSION_KEY]['csrf_token']) 
        && hash_equals($_SESSION[ADMIN_SESSION_KEY]['csrf_token'], $token);
}

function getPDO(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $host = 'localhost';
        $dbname = 'backup';
        $user = 'root';
        $pass = '';
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
    return $pdo;
}

function getMySQLi(): mysqli {
    static $conn = null;
    if ($conn === null) {
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $dbname = 'backup';
        $conn = new mysqli($host, $user, $pass, $dbname);
        if ($conn->connect_error) {
            die('Database connection failed: ' . $conn->connect_error);
        }
        $conn->set_charset('utf8mb4');
    }
    return $conn;
}

function redirectTo($path) {
    header("Location: $path");
    exit;
}

function escape($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function formatPrice($price) {
    return number_format((float) $price, 2, ',', ' ') . ' TND';
}

function formatDate($date, $format = 'd/m/Y H:i') {
    if (!$date) return '-';
    return date($format, strtotime($date));
}

function getStatusBadge($status) {
    $active = $status === 'actif' || $status === 'publie';
    $classes = $active 
        ? 'bg-green-500/20 text-green-400' 
        : 'bg-red-500/20 text-red-400';
    $labels = [
        'actif' => 'Actif',
        'inactif' => 'Inactif',
        'publie' => 'Publié',
        'brouillon' => 'Brouillon',
        'en_attente' => 'En attente',
        'confirmee' => 'Confirmée',
        'expediee' => 'Expédiée',
        'livree' => 'Livrée',
        'annulee' => 'Annulée'
    ];
    return '<span class="px-2 py-1 text-xs rounded-full ' . $classes . '">' . ($labels[$status] ?? $status) . '</span>';
}