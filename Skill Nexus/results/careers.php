<?php
require_once __DIR__ . '/../config.php';

// Load careers from the database
$careers = $pdo->query(
    'SELECT c.id, c.name, c.description, c.degree_id, d.name AS degree_name
     FROM careers c
     LEFT JOIN degrees d ON c.degree_id = d.id
     ORDER BY c.name'
)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Top Careers - Career Guidance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9fff0;
        }

        .card:hover {
            transform: translateY(-5px);
            transition: 0.3s;
        }

        .btn-career {
            background-color: #4caf50;
            color: white;
        }

        .btn-career:hover {
            background-color: #2e7d32;
        }
    </style>
</head>

<body>
    <?php renderNav(); ?>

    <header class="text-center py-5 bg-success text-white" style="margin-top: 50px;">
        <div class="container">
            <h1 class="fw-bold">Top Careers</h1>
            <p class="lead">Explore careers that match your interests and strengths.</p>
        </div>
    </header>

    <section class="py-5">
        <div class="container">
            <?php if (empty($careers)): ?>
                <div class="alert alert-info">No careers found in the database.</div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($careers as $career): ?>
                        <div class="col-md-4">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-success"><?= htmlspecialchars($career['name']) ?></h5>
                                    <p class="card-text"><?= nl2br(htmlspecialchars($career['description'])) ?></p>
                                    <?php if (!empty($career['degree_name'])): ?>
                                        <p class="mb-2"><strong>Suggested Degree:</strong>
                                            <?= htmlspecialchars($career['degree_name']) ?></p>
                                    <?php endif; ?>
                                    <a href="<?= BASE_URL ?>/results/degrees.php?degree_id=<?= (int) $career['degree_id'] ?>"
                                        class="btn btn-career">View Degree Path</a>
                                    <a href="<?= BASE_URL ?>/results/result.php" class="btn btn-outline-light">Explore
                                        Matches</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <footer class="bg-success text-white text-center py-3">
        <p>&copy; <?= date('Y') ?> Career Guidance. All rights reserved.</p>
    </footer>

</body>

</html>