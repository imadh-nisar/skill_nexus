<?php
require_once __DIR__ . '/../config.php';   // fixed path

// Ensure user is logged in
requireLogin();

$userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;
if ($userId <= 0) {
  // Not logged in or invalid session
  header('Location: ' . BASE_URL . '/login.php');
  exit;
}

// Helper: escape output
function e_out($s)
{
  return htmlspecialchars((string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Store submitted answers for the logged-in user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['answers']) && is_array($_POST['answers'])) {
  $answers = $_POST['answers'];

  // Normalize: keys should be question IDs (integers), values strings
  $normalized = [];
  foreach ($answers as $qid => $val) {
    $qidInt = (int) $qid;
    if ($qidInt <= 0)
      continue;
    $answerValue = trim((string) $val);
    // Optionally ignore empty answers
    if ($answerValue === '')
      continue;
    $normalized[$qidInt] = $answerValue;
  }

  if (!empty($normalized)) {
    // Use transaction: delete old answers and insert new ones
    $pdo->beginTransaction();
    try {
      $del = $pdo->prepare("DELETE FROM user_answers WHERE user_id = ?");
      $del->execute([$userId]);

      $ins = $pdo->prepare("INSERT INTO user_answers (user_id, question_id, answer_value) VALUES (?, ?, ?)");
      foreach ($normalized as $qid => $val) {
        $ins->execute([$userId, $qid, $val]);
      }

      $pdo->commit();
    } catch (Exception $ex) {
      $pdo->rollBack();
      // Log error in real app
    }
  }
}

// Fetch total number of active questions and per-category counts
$qCountSql = "
    SELECT 
      COUNT(*) AS total_questions,
      SUM(CASE WHEN category = 'characteristics' THEN 1 ELSE 0 END) AS characteristics_count,
      SUM(CASE WHEN category = 'skills' THEN 1 ELSE 0 END) AS skills_count,
      SUM(CASE WHEN category = 'preferences' THEN 1 ELSE 0 END) AS preferences_count
    FROM questions
    WHERE active = 1
";
$qStmt = $pdo->query($qCountSql);
$qCounts = $qStmt->fetch(PDO::FETCH_ASSOC);
$totalQuestions = (int) ($qCounts['total_questions'] ?? 0);
$characteristicsCount = (int) ($qCounts['characteristics_count'] ?? 0);
$skillsCount = (int) ($qCounts['skills_count'] ?? 0);
$preferencesCount = (int) ($qCounts['preferences_count'] ?? 0);

// If there are no questions, show a message
if ($totalQuestions === 0) {
  // Render minimal page and exit
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
      <div class="alert alert-warning">No questions are configured yet. Please contact the administrator.</div>
    </div>
  </body>

  </html>
  <?php
  exit;
}

// Fetch career matches based on stored answers
// Strategy:
//  - Join career_preferences (cp) to user_answers (ua) on question_id and preferred_answer = answer_value
//  - Join questions (q) to get category for per-category counts
//  - Count matches per career overall and per category
//  - Compute percentage = matches / total_questions * 100
$query = "
    SELECT 
      c.id,
      c.name,
      c.description,
      d.name AS degree,
      COUNT(DISTINCT CASE WHEN ua.answer_value IS NOT NULL THEN cp.question_id END) AS match_count,
      SUM(CASE WHEN ua.answer_value IS NOT NULL AND q.category = 'characteristics' THEN 1 ELSE 0 END) AS match_characteristics,
      SUM(CASE WHEN ua.answer_value IS NOT NULL AND q.category = 'skills' THEN 1 ELSE 0 END) AS match_skills,
      SUM(CASE WHEN ua.answer_value IS NOT NULL AND q.category = 'preferences' THEN 1 ELSE 0 END) AS match_preferences
    FROM careers c
    LEFT JOIN degrees d ON c.degree_id = d.id
    LEFT JOIN career_preferences cp ON c.id = cp.career_id
    LEFT JOIN questions q ON cp.question_id = q.id
    LEFT JOIN user_answers ua ON cp.question_id = ua.question_id
        AND cp.preferred_answer = ua.answer_value
        AND ua.user_id = ?
    GROUP BY c.id
    ORDER BY match_count DESC, c.name ASC
";
$stmt = $pdo->prepare($query);
$stmt->execute([$userId]);
$careers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Compute percentage and prepare display values
$results = [];
foreach ($careers as $c) {
  $matchCount = (int) ($c['match_count'] ?? 0);
  $matchChar = (int) ($c['match_characteristics'] ?? 0);
  $matchSkills = (int) ($c['match_skills'] ?? 0);
  $matchPrefs = (int) ($c['match_preferences'] ?? 0);

  $percentage = $totalQuestions > 0 ? round(($matchCount / $totalQuestions) * 100, 1) : 0.0;

  $results[] = [
    'id' => (int) $c['id'],
    'name' => $c['name'],
    'description' => $c['description'],
    'degree' => $c['degree'],
    'match_count' => $matchCount,
    'match_percentage' => $percentage,
    'match_characteristics' => $matchChar,
    'match_skills' => $matchSkills,
    'match_preferences' => $matchPrefs,
  ];
}

// Optionally: limit to top N results for display (e.g., top 10)
$displayResults = array_slice($results, 0, 20);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Results</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    pre.code {
      background: #f8f9fa;
      padding: 10px;
      border-radius: 4px;
      font-family: monospace;
    }

    .progress-small {
      height: 14px;
    }
  </style>
</head>

<body>
  <?php renderNav(); ?>
  <div class="container mt-5">
    <h1 class="mb-4">
      Your Career Guidance Results<?= !empty($_SESSION['user_name']) ? ', ' . e_out($_SESSION['user_name']) : '' ?>
    </h1>

    <p class="text-muted">Based on <strong><?= $totalQuestions ?></strong> questions:
      <span class="badge bg-info">Characteristics: <?= $characteristicsCount ?></span>
      <span class="badge bg-success">Skills: <?= $skillsCount ?></span>
      <span class="badge bg-warning text-dark">Preferences: <?= $preferencesCount ?></span>
    </p>

    <?php if (empty($displayResults)): ?>
      <div class="alert alert-info">
        No matching careers found. Try retaking the test or adjusting your answers.
      </div>
    <?php else: ?>
      <div class="row">
        <?php foreach ($displayResults as $career): ?>
          <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
              <div class="card-body">
                <h5 class="card-title"><?= e_out($career['name']) ?></h5>
                <p class="card-text"><?= nl2br(e_out($career['description'])) ?></p>

                <p><strong>Suggested Degree:</strong> <?= e_out($career['degree'] ?? '—') ?></p>

                <p><strong>Match Score:</strong> <?= (int) $career['match_count'] ?> / <?= $totalQuestions ?>
                  (<?= e_out($career['match_percentage']) ?>%)</p>

                <div class="mb-2">
                  <div class="small text-muted">Characteristics: <?= (int) $career['match_characteristics'] ?> /
                    <?= $characteristicsCount ?></div>
                  <div class="progress progress-small mb-2">
                    <div class="progress-bar bg-info" role="progressbar"
                      style="width: <?= $characteristicsCount ? (int) round(($career['match_characteristics'] / $characteristicsCount) * 100) : 0 ?>%"
                      aria-valuenow="<?= $career['match_characteristics'] ?>" aria-valuemin="0"
                      aria-valuemax="<?= $characteristicsCount ?>"></div>
                  </div>

                  <div class="small text-muted">Skills: <?= (int) $career['match_skills'] ?> / <?= $skillsCount ?></div>
                  <div class="progress progress-small mb-2">
                    <div class="progress-bar bg-success" role="progressbar"
                      style="width: <?= $skillsCount ? (int) round(($career['match_skills'] / $skillsCount) * 100) : 0 ?>%"
                      aria-valuenow="<?= $career['match_skills'] ?>" aria-valuemin="0" aria-valuemax="<?= $skillsCount ?>">
                    </div>
                  </div>

                  <div class="small text-muted">Preferences: <?= (int) $career['match_preferences'] ?> /
                    <?= $preferencesCount ?></div>
                  <div class="progress progress-small">
                    <div class="progress-bar bg-warning" role="progressbar"
                      style="width: <?= $preferencesCount ? (int) round(($career['match_preferences'] / $preferencesCount) * 100) : 0 ?>%"
                      aria-valuenow="<?= $career['match_preferences'] ?>" aria-valuemin="0"
                      aria-valuemax="<?= $preferencesCount ?>"></div>
                  </div>
                </div>

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