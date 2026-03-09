<?php
// helpers.php

// Simple function to sanitize output
function e($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Redirect helper
function redirect($url)
{
    header("Location: $url");
    exit;
}

// Check if user is logged in
function requireLogin()
{
    if (!isset($_SESSION['user_id'])) {
        // Capture current page
        $currentUrl = $_SERVER['REQUEST_URI'];

        // Redirect to login with ?redirect=...
        redirect('/login.php?redirect=' . urlencode($currentUrl));
    }
}