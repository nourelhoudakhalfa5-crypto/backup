<?php
require_once __DIR__ . '/includes/auth.php';
adminLogout();
session_destroy();
header('Location: login.php');
exit;