<?php
require_once 'includes/db.php';
$email = "admin@gmail.com";
$password = "nour1234";

$stmt = $conn->prepare("SELECT id, mot_de_passe FROM administrateurs WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
    $admin = $res->fetch_assoc();
    if (password_verify($password, $admin['mot_de_passe'])) {
        echo "Login SUCCESS for admin";
    } else {
        echo "Login FAILED: Wrong password";
    }
} else {
    echo "Login FAILED: Admin not found";
}
?>
