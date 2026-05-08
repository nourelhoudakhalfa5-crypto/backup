<?php
// includes/db.php
// 1- connexion au serveur
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rdoc_db";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to utf8
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Une erreur est survenue lors de la connexion à la base de données.");
}
?>
