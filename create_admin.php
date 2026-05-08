<?php
$conn = new mysqli('localhost', 'root', '', 'backup');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = 'admin@gmail.com';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$nom = 'Administrateur';

$stmt = $conn->prepare("INSERT INTO administrateurs (nom, email, mot_de_passe) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nom, $email, $password);

if ($stmt->execute()) {
    echo "Admin created successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>