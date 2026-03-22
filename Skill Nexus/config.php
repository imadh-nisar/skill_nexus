<?php
// Configuration

// Start session early so helper functions relying on session state work consistently
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base file system path for includes / requires
define('BASE_PATH', __DIR__);

// Base URL used for generating links; the folder name includes a space, so use URL encoding.
define('BASE_URL', '/skill_nexus/Skill%20Nexus');

// Database connection
try {
    $dsn = "mysql:host=localhost;dbname=career_guidance;charset=utf8mb4";
    $username = "root";
    $password = "";

    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // In production, avoid exposing implementation details.
    die("Database connection failed.");
}

// Utility helpers
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function isLoggedIn(): bool
{
    return !empty($_SESSION['user_id']);
}

function requireLogin(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['user_id'])) {
        $redirect = $_SERVER['REQUEST_URI'] ?? '/';
        $_SESSION['redirect_url'] = $redirect;
        header('Location: ' . BASE_URL . '/auth/login.php');
        exit;
    }
}

function currentUserName(): ?string
{
    return $_SESSION['user_name'] ?? null;
}

function renderNav(): void
{
    ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= BASE_URL ?>/index_.php">Skill NEXUS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/career/career_test.php">Career Test</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/results/careers.php">Careers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/results/degrees.php">Degrees</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/community.php">Community</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            More
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/partners & blog/blog.php">Blog</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/partners & blog/partners.php">Partners</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex">
                    <?php if (isLoggedIn()): ?>
                        <span class="navbar-text me-2">Hello, <?= e(currentUserName() ?? ''); ?></span>
                        <a class="btn btn-outline-secondary" href="<?= BASE_URL ?>/auth/logout.php">Logout</a>
                    <?php else: ?>
                        <a class="btn btn-outline-success me-2" href="<?= BASE_URL ?>/auth/login.php">Login</a>
                        <a class="btn btn-outline-primary" href="<?= BASE_URL ?>/auth/registration.php">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <?php
}

