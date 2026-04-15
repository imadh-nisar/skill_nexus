<?php
include __DIR__ . '/../config.php';

// Clear session
$_SESSION = [];
session_destroy();

header('Location: ' . BASE_URL . '/index.php');
exit;
