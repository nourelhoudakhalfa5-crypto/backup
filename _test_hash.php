<?php
$hash = '$2y$10$pMdHv8jQzRcD/pYFzn2qBO2Dd5tzZAweIt3yfKs1qbrZEXtjWBDkK';
$password = 'nour1234';
var_dump(password_verify($password, $hash));

// Let's generate a new hash to see
echo password_hash($password, PASSWORD_DEFAULT);
?>
