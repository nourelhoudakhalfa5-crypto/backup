<?php
// 1- connexion au serveur
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'backup';

// 3- Préparation de la requete
$conn = new mysqli($host, $user, $pass, $dbname);

// 5- Vérification
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
