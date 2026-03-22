<?php
// tets.php
$questions = [
  "Do you enjoy working with numbers and data?",
  "Do you prefer logical problem-solving over creative design?",
  "Do you prefer working alone rather than in a team?",
  "Would you enjoy a career that involves frequent travel?",
  "Do you like explaining concepts to others?",
  "Are you comfortable with coding and technology?",
  "Do you prefer stability over risk-taking in your career?",
  "Do you enjoy researching and writing?",
  "Would you like to manage people and projects?",
  "Do you prefer practical hands-on work over theoretical analysis?",
  "Are you motivated by high salary rather than personal satisfaction?",
  "Do you enjoy solving puzzles and complex problems?",
  "Would you like to work in healthcare or education?",
  "Do you prefer working indoors rather than outdoors?",
  "Are you interested in entrepreneurship and starting your own business?"
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Career Test</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .question-step {
      display: none;
    }

    .question-step.active {
      display: block;
    }
  </style>
</head>

<body class="bg-light">
  <div class="container py-5">
    <h1 class="mb-4 text-primary">Career Guidance Test</h1>

    <div class="mb-4">
      <div class="progress">
        <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
      </div>
      <div class="text-end mt-1"><small id="progressText"></small></div>
    </div>

    <form id="careerTestForm" action="../results/result.php" method="post">
      <?php foreach ($questions as $index => $question): ?>
        <div class="question-step" data-step="<?= $index ?>">
          <div class="mb-3">
            <label class="form-label"><strong>Q<?= $index + 1 ?>:</strong> <?= htmlspecialchars($question) ?></label>
            <select name="answers[<?= $index ?>]" class="form-select" required>
              <option value="">Select an option</option>
              <option value="yes">Yes</option>
              <option value="no">No</option>
              <option value="maybe">Maybe</option>
            </select>
            <div class="invalid-feedback">Please select an answer to proceed.</div>
          </div>
        </div>
      <?php endforeach; ?>

      <div class="d-flex justify-content-between">
        <button type="button" id="prevBtn" class="btn btn-outline-secondary" disabled>Previous</button>
        <div>
          <button type="button" id="nextBtn" class="btn btn-primary">Next</button>
          <button type="submit" id="submitBtn" class="btn btn-success" style="display: none;">Submit</button>
        </div>
      </div>
    </form>
  </div>

  <script>
    const steps = document.querySelectorAll('.question-step');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');

    let currentStep = 0;

    function showStep(index) {
      steps.forEach((step, i) => step.classList.toggle('active', i === index));
      prevBtn.disabled = index === 0;

      const progress = Math.round(((index + 1) / steps.length) * 100);
      progressBar.style.width = `${progress}%`;
      progressBar.textContent = `${progress}%`;
      progressText.textContent = `Question ${index + 1} of ${steps.length}`;

      if (index === steps.length - 1) {
        nextBtn.style.display = 'none';
        submitBtn.style.display = 'inline-block';
      } else {
        nextBtn.style.display = 'inline-block';
        submitBtn.style.display = 'none';
      }
    }

    function validateStep(index) {
      const select = steps[index].querySelector('select');
      if (!select.value) {
        select.classList.add('is-invalid');
        return false;
      }
      select.classList.remove('is-invalid');
      return true;
    }

    prevBtn.addEventListener('click', () => {
      if (currentStep > 0) {
        currentStep -= 1;
        showStep(currentStep);
      }
    });

    nextBtn.addEventListener('click', () => {
      if (!validateStep(currentStep)) return;
      if (currentStep < steps.length - 1) {
        currentStep += 1;
        showStep(currentStep);
      }
    });

    showStep(currentStep);
  </script>
</body>

</html>
