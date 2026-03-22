<?php
require_once __DIR__ . '/../config.php';

// Load degrees from the database (optionally filter by a specific degree)
$degreeId = isset($_GET['degree_id']) ? (int) $_GET['degree_id'] : null;

if ($degreeId) {
    $stmt = $pdo->prepare('SELECT id, name, description FROM degrees WHERE id = ?');
    $stmt->execute([$degreeId]);
    $degrees = $stmt->fetchAll();
} else {
    $degrees = $pdo->query('SELECT id, name, description FROM degrees ORDER BY name')->fetchAll();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Top Degrees - Career Guidance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
        }

        .card:hover {
            transform: translateY(-5px);
            transition: 0.3s;
        }

        .btn-degree {
            background-color: #1976d2;
            color: white;
        }

        .btn-degree:hover {
            background-color: #0d47a1;
        }
    </style>
</head>

<body>
    <?php renderNav(); ?>

    <header class="text-center py-5 bg-primary text-white" style="margin-top: 50px;">
        <div class="container">
            <h1 class="fw-bold"><?= $degreeId ? 'Degree Path' : 'Top Degrees' ?></h1>
            <p class="lead">
                <?= $degreeId ? 'Here is the degree you selected from a career path.' : 'Compare popular degrees and their focus areas.' ?>
            </p>
            <?php if ($degreeId): ?>
                <a href="<?= BASE_URL ?>/results/degrees.php" class="btn btn-light btn-sm">Show all degrees</a>
            <?php endif; ?>
        </div>
    </header>

    <section class="py-5">
        <div class="container">
            <?php if (empty($degrees)): ?>
                <div class="alert alert-info">No degrees found in the database.</div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($degrees as $degree): ?>
                        <div class="col-md-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-primary"><?= htmlspecialchars($degree['name']) ?></h5>
                                    <p class="card-text"><?= nl2br(htmlspecialchars($degree['description'])) ?></p>
                                    <a href="<?= BASE_URL ?>/results/careers.php" class="btn btn-degree">View Careers</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <footer class="bg-primary text-white text-center py-3">
        <p>&copy; <?= date('Y') ?> Career Guidance. All rights reserved.</p>
    </footer>

</body>

</html>