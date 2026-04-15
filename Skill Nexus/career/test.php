<?php
include __DIR__ . '/../config.php';

// Generate or retrieve session ID for tracking test responses
if (!isset($_SESSION['test_session_id'])) {
  $_SESSION['test_session_id'] = uniqid('test_', true);
  $_SESSION['test_answers'] = [];
}

// Fetch questions from database with error handling
$questions = [];
$error_message = '';

try {
  $questions = getQuestions($pdo);

  // Check if questions were retrieved
  if (empty($questions)) {
    $error_message = "No assessment questions found. Please contact support.";
  }
} catch (Exception $ex) {
  $error_message = "Error loading questions: " . $ex->getMessage();
  error_log("Test.php error: " . $ex->getMessage());
}

// Handle form submission for test completion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['answers'])) {
  $_SESSION['test_answers'] = $_POST['answers'];

  // Save test result to database
  $session_id = $_SESSION['test_session_id'];
  $answers = json_encode($_SESSION['test_answers']);

  try {
    $stmt = $pdo->prepare("
      INSERT INTO test_results (session_id, career_recommendations, created_at)
      VALUES (:session_id, :answers, NOW())
      ON DUPLICATE KEY UPDATE career_recommendations = :answers
    ");
    $stmt->execute([
      ':session_id' => $session_id,
      ':answers' => $answers
    ]);

    // Redirect to results page
    header('Location: ' . BASE_URL . '/career/results.php?session=' . urlencode($session_id));
    exit;
  } catch (Exception $ex) {
    error_log("Error saving test results: " . $ex->getMessage());
    $error_message = "Error saving your assessment. Please try again.";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Career Assessment - Skill NEXUS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
      min-height: 100vh;
      padding: 20px 0;
    }

    .test-container {
      max-width: 800px;
      margin: 0 auto;
    }

    .card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
      animation: slideUp 0.5s ease-out;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .progress-section {
      background: white;
      padding: 30px;
      border-radius: 20px;
      margin-bottom: 30px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .progress-bar {
      height: 8px;
      border-radius: 10px;
      background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
    }

    .progress-text {
      font-size: 0.95rem;
      color: #667eea;
      font-weight: 600;
      margin-top: 10px;
    }

    .test-card {
      background: white;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .question-number {
      display: inline-block;
      background: linear-gradient(135deg, #667eea, #764ba2);
      color: white;
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 700;
      margin-bottom: 15px;
    }

    .question-text {
      font-size: 1.5rem;
      font-weight: 700;
      color: #333;
      margin-bottom: 30px;
      line-height: 1.6;
    }

    .option-label {
      display: block;
      padding: 15px 20px;
      margin-bottom: 12px;
      border: 2px solid #e9ecef;
      border-radius: 12px;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 1rem;
      color: #333;
      font-weight: 500;
    }

    .option-label:hover {
      border-color: #667eea;
      background: rgba(102, 126, 234, 0.05);
      transform: translateX(5px);
    }

    input[type="radio"] {
      cursor: pointer;
    }

    input[type="radio"]:checked+.option-label {
      background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
      border-color: #667eea;
      font-weight: 600;
    }

    input[type="radio"]:checked+.option-label::before {
      content: '✓ ';
      color: #667eea;
      font-weight: bold;
    }

    .form-check {
      margin-bottom: 0;
    }

    .form-check input {
      margin-top: 5px;
    }

    .btn-next {
      width: 100%;
      padding: 15px;
      font-size: 1.1rem;
      font-weight: 600;
      background: linear-gradient(135deg, #667eea, #764ba2);
      border: none;
      border-radius: 12px;
      color: white;
      transition: all 0.3s ease;
      margin-top: 30px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    .btn-next:hover:not(:disabled) {
      transform: translateY(-2px);
      box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
      color: white;
    }

    .btn-next:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    .btn-back {
      background: white;
      color: #667eea;
      border: 2px solid #667eea;
      margin-right: 10px;
    }

    .btn-back:hover {
      background: #667eea;
      color: white;
    }

    .controls {
      display: flex;
      gap: 10px;
      margin-top: 30px;
    }

    .question-indicator {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(40px, 1fr));
      gap: 5px;
      margin-bottom: 20px;
    }

    .indicator-dot {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: #e9ecef;
      border: 2px solid transparent;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 0.8rem;
      font-weight: 600;
      transition: all 0.3s ease;
      color: #999;
    }

    .indicator-dot.answered {
      background: #667eea;
      color: white;
      border-color: #667eea;
    }

    .indicator-dot.current {
      border-color: #667eea;
      background: rgba(102, 126, 234, 0.1);
      color: #667eea;
      font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .test-card {
        padding: 25px;
      }

      .question-text {
        font-size: 1.2rem;
      }

      .option-label {
        padding: 12px 15px;
        font-size: 0.95rem;
      }
    }
  </style>
</head>

<body>
  <?php renderNav(); ?>

  <div class="test-container py-4">
    <!-- Progress Bar -->
    <div class="progress-section">
      <div class="d-flex justify-content-between mb-2">
        <span style="color: #667eea; font-weight: 600;">Career Assessment</span>
        <span id="progress-text" class="progress-text">Question 1 of <?= count($questions) ?></span>
      </div>
      <div class="progress" style="height: 10px; border-radius: 10px; overflow: hidden;">
        <div id="progress-bar" class="progress-bar" role="progressbar"
          style="width: 5%; background: linear-gradient(90deg, #667eea, #764ba2);"></div>
      </div>
    </div>

    <!-- Test Form -->
    <form method="POST" id="test-form">
      <div class="test-card">
        <!-- Question Indicators -->
        <div class="question-indicator">
          <?php for ($i = 1; $i <= count($questions); $i++): ?>
            <button type="button" class="indicator-dot" data-question="<?= $i ?>" onclick="goToQuestion(<?= $i ?>)">
              <?= $i ?>
            </button>
          <?php endfor; ?>
        </div>

        <!-- Questions Display -->
        <?php foreach ($questions as $index => $question):
          $questionNum = $index + 1;
          $options = getQuestionOptions($pdo, $question['id']);
          ?>
          <div class="question-container" data-question="<?= $questionNum ?>"
            style="display: <?= $questionNum === 1 ? 'block' : 'none' ?>;">
            <span class="question-number">Question <?= $questionNum ?> of <?= count($questions) ?></span>
            <h3 class="question-text"><?= e($question['question_text']) ?></h3>

            <div id="options-<?= $question['id'] ?>">
              <?php foreach ($options as $option): ?>
                <div class="form-check">
                  <input type="radio" name="answers[<?= $question['id'] ?>]" value="<?= $option['option_value'] ?>"
                    id="option-<?= $option['id'] ?>" class="form-check-input" onchange="updateIndicators()">
                  <label class="option-label" for="option-<?= $option['id'] ?>">
                    <?= e($option['option_text']) ?>
                  </label>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>

        <!-- Navigation -->
        <div class="controls">
          <button type="button" class="btn btn-back" id="btn-prev" onclick="previousQuestion()" style="display: none;">
            <i class="fas fa-chevron-left"></i> Previous
          </button>
          <button type="button" class="btn btn-next" id="btn-next" onclick="nextQuestion()">
            Next <i class="fas fa-chevron-right"></i>
          </button>
          <button type="submit" class="btn btn-next d-none" id="btn-submit">
            <i class="fas fa-check"></i> Submit
          </button>
        </div>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const totalQuestions = <?= count($questions) ?>;
    let currentQuestion = 1;
    const answers = {};

    function updateProgressBar() {
      const progress = (currentQuestion / totalQuestions) * 100;
      document.getElementById('progress-bar').style.width = progress + '%';
      document.getElementById('progress-text').textContent = `Question ${currentQuestion} of ${totalQuestions}`;
    }

    function showQuestion(num) {
      document.querySelectorAll('.question-container').forEach(el => {
        el.style.display = 'none';
      });
      document.querySelector(`.question-container[data-question="${num}"]`).style.display = 'block';

      updateProgressBar();
      updateIndicators();

      // Update button visibility
      document.getElementById('btn-prev').style.display = num === 1 ? 'none' : 'inline-block';
      document.getElementById('btn-next').classList.toggle('d-none', num === totalQuestions);
      document.getElementById('btn-submit').classList.toggle('d-none', num !== totalQuestions);

      // Smooth scroll
      document.querySelector('.test-card').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function nextQuestion() {
      if (currentQuestion < totalQuestions) {
        currentQuestion++;
        showQuestion(currentQuestion);
      }
    }

    function previousQuestion() {
      if (currentQuestion > 1) {
        currentQuestion--;
        showQuestion(currentQuestion);
      }
    }

    function goToQuestion(num) {
      currentQuestion = num;
      showQuestion(num);
    }

    function updateIndicators() {
      document.querySelectorAll('.indicator-dot').forEach((dot, index) => {
        const questionNum = index + 1;
        const questionContainer = document.querySelector(`[data-question="${questionNum}"]`);
        const selectedInput = questionContainer.querySelector('input[type="radio"]:checked');

        dot.classList.remove('answered', 'current');
        if (questionNum === currentQuestion) {
          dot.classList.add('current');
        } else if (selectedInput) {
          dot.classList.add('answered');
        }
      });
    }

    // Initialize
    updateProgressBar();
    updateIndicators();

    // Prevent form submission on button click
    document.getElementById('btn-next').addEventListener('click', (e) => {
      e.preventDefault();
      nextQuestion();
    });

    document.getElementById('btn-prev').addEventListener('click', (e) => {
      e.preventDefault();
      previousQuestion();
    });

    // Show first question
    showQuestion(1);
  </script>
</body>

</html>