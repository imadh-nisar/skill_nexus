<?php
// tets.php
$questions = [
  "Do you enjoy working with numbers and data?",
  "Are you more interested in creative design or logical problem-solving?",
  "Do you prefer working alone or in a team?",
  "Would you enjoy a career that involves frequent travel?",
  "Do you like explaining concepts to others?",
  "Are you comfortable with coding and technology?",
  "Do you prefer stability or risk-taking in your career?",
  "Do you enjoy researching and writing?",
  "Would you like to manage people and projects?",
  "Do you prefer practical hands-on work or theoretical analysis?",
  "Are you motivated by high salary or personal satisfaction?",
  "Do you enjoy solving puzzles and complex problems?",
  "Would you like to work in healthcare or education?",
  "Do you prefer working indoors or outdoors?",
  "Are you interested in entrepreneurship and starting your own business?"
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Career Test</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container py-5">
    <h1 class="mb-4 text-primary">Career Guidance Test</h1>
    <form action="../results/result.php" method="post">
      <?php foreach ($questions as $index => $question): ?>
        <div class="mb-3">
          <label class="form-label"><strong>Q<?= $index + 1 ?>:</strong> <?= $question; ?></label>
          <select name="answers[<?= $index ?>]" class="form-select" required>
            <option value="">Select an option</option>
            <option value="yes">Yes</option>
            <option value="no">No</option>
            <option value="maybe">Maybe</option>
          </select>
        </div>
      <?php endforeach; ?>
      <button type="submit" class="btn btn-success">Submit</button>
    </form>
  </div>
</body>

</html>