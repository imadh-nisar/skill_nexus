<?php
require_once __DIR__ . '/../config.php';   // fixed path
require_once __DIR__ . '/../helpers.php';  // fixed path

// Ensure user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: ../auth/login.php");
  exit;
}

$userId = (int) $_SESSION['user_id']; // enforce integer

// Fetch user answers (optional if not used later)
$stmt = $pdo->prepare("SELECT question_id, answer_id FROM user_answers WHERE user_id = ?");
$stmt->execute([$userId]);
$userAnswers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Optimized query: join directly with user_answers
$query = "
    SELECT c.id, c.name, c.description, d.name AS degree, COUNT(cam.answer_id) AS match_count
    FROM careers c
    JOIN career_answer_map cam ON c.id = cam.career_id
    JOIN degrees d ON c.degree_id = d.id
    JOIN user_answers ua ON cam.answer_id = ua.answer_id AND ua.user_id = ?
    GROUP BY c.id
    ORDER BY match_count DESC
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
    <h1 class="mb-4">
      Your Career Guidance
      Results<?= isset($_SESSION['username']) ? ', ' . htmlspecialchars($_SESSION['username']) : '' ?>
    </h1>

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
                <p><strong>Match Score:</strong> <?= (int) $career['match_count'] ?> answers matched</p>
              </div>
              <div class="card-footer d-flex justify-content-between">
                <a href="<?= $basePath ?>/advice.php?career_id=<?= $career['id'] ?>" class="btn btn-primary">
                  Get Advice
                </a>
                <a href="<?= $basePath ?>/degree.php?id=<?= $career['id'] ?>" class="btn btn-outline-secondary">
                  View Degree Path
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