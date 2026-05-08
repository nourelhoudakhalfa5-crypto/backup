<?php
require_once 'includes/db.php';
$hash = '$2y$10$pMdHv8jQzRcD/pYFzn2qBO2Dd5tzZAweIt3yfKs1qbrZEXtjWBDkK';
$email = 'admin@gmail.com';
$stmt = $conn->prepare("UPDATE administrateurs SET mot_de_passe = ? WHERE email = ?");
$stmt->bind_param("ss", $hash, $email);
$stmt->execute();
echo "Admin password updated to hash for nour1234\n";
?>
