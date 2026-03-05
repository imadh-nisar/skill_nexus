<?php
require_once __DIR__ . '../config.php'; // centralized config with DB connection, base path, etc.
require_once __DIR__ . '../helpers.php'; // any shared functions

// Ensure user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: {$basePath}/login.php");
  exit;
}

// Fetch user answers
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT question_id, answer_id FROM user_answers WHERE user_id = ?");
$stmt->execute([$userId]);
$userAnswers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate career matches
// Example: join careers table with answers mapping
$query = "
    SELECT c.id, c.name, c.description, d.name AS degree
    FROM careers c
    JOIN career_answer_map cam ON c.id = cam.career_id
    JOIN degrees d ON c.degree_id = d.id
    WHERE cam.answer_id IN (
        SELECT answer_id FROM user_answers WHERE user_id = ?
    )
    GROUP BY c.id
    ORDER BY COUNT(cam.answer_id) DESC
";
$stmt = $pdo->prepare($query);
$stmt->execute([$userId]);
$careers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Results</title>
  <link rel="stylesheet" href="<?= $basePath ?>/assets/css/bootstrap.min.css">
</head>

<body>
  

  <div class="container mt-5">
    <h1 class="mb-4">Your Career Guidance Results</h1>

    <?php if (empty($careers)): ?>
      <div class="alert alert-info">
        No matching careers found. Try retaking the test or adjusting your answers.
      </div>
    <?php else: ?>
      <div class="row">
        <?php foreach ($careers as $career): ?>
          <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($career['name']) ?></h5>
                <p class="card-text"><?= nl2br(htmlspecialchars($career['description'])) ?></p>
                <p><strong>Suggested Degree:</strong> <?= htmlspecialchars($career['degree']) ?></p>
              </div>
              <div class="card-footer text-end">
                <a href="<?= $basePath ?>/advice.php?career_id=<?= $career['id'] ?>" class="btn btn-primary">
                  Get Advice
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <script src="<?= $basePath ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>