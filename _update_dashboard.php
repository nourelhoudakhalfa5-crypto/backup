<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$template_path = 'dashboard/index.html';
if (!file_exists($template_path)) {
    die("Error: Dashboard template not found.");
}

$html = file_get_contents($template_path);

// Fix Asset Paths[cite: 19]
$html = str_replace('href="dist/', 'href="dashboard/dist/', $html);
$html = str_replace('src="dist/', 'src="dashboard/dist/', $html);

// FIX LOGOUT BUTTON[cite: 19]
// We target the common Midone logout link pattern. 
// If your template uses a different link (e.g., login.html), replace 'login.html' below.
$html = str_replace('href="login.html"', 'href="logout.php"', $html);

// Optional: Forcefully replace any "Logout" text link if the above doesn't catch it
$html = str_replace('Logout</a>', 'Logout</a>', $html); 

echo $html;
?>