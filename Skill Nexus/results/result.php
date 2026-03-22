<?php
require_once __DIR__ . '/../config.php';   // fixed path

// Ensure user is logged in
requireLogin();

$userId = (int) $_SESSION['user_id']; // enforce integer

// Store submitted answers for the logged-in user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['answers']) && is_array($_POST['answers'])) {
  $answers = $_POST['answers'];

  // Replace any previously stored answers for this user
  $stmt = $pdo->prepare("DELETE FROM user_answers WHERE user_id = ?");
  $stmt->execute([$userId]);

  $insert = $pdo->prepare("INSERT INTO user_answers (user_id, question_id, answer_value) VALUES (?, ?, ?)");
  foreach ($answers as $index => $value) {
    $questionId = (int) $index + 1;
    $answerValue = trim((string) $value);
    if ($answerValue === '') {
      continue;
    }
    $insert->execute([$userId, $questionId, $answerValue]);
  }
}

// Fetch career matches based on stored answers
$query = "
    SELECT c.id, c.name, c.description, d.name AS degree, COUNT(*) AS match_count
    FROM careers c
    LEFT JOIN degrees d ON c.degree_id = d.id
    LEFT JOIN career_preferences cp ON c.id = cp.career_id
    LEFT JOIN user_answers ua ON cp.question_id = ua.question_id
        AND cp.preferred_answer = ua.answer_value
        AND ua.user_id = ?
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <?php renderNav(); ?>
  <div class="container mt-5">
    <h1 class="mb-4">
      Your Career Guidance Results<?= !empty($_SESSION['user_name']) ? ', ' . e($_SESSION['user_name']) : '' ?>
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
                <a href="<?= BASE_URL ?>/career/career_test.php" class="btn btn-primary">
                  Retake Test
                </a>
                <a href="<?= BASE_URL ?>/results/degrees.php" class="btn btn-outline-secondary">
                  View Degree Paths
                </a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>