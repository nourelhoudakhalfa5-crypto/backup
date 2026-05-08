<?php
require_once 'includes/db.php';
$stmt = $conn->prepare("SELECT id, email, mot_de_passe FROM administrateurs");
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    echo "ID: " . $row['id'] . ", Email: " . $row['email'] . ", Hash: " . $row['mot_de_passe'] . "\n";
}
?>
