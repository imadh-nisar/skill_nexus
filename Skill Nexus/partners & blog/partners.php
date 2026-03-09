<?php
require_once __DIR__ . '/../config.php';   // fixed path
// partners.php
$partners = [
  ["name" => "Future Minds Academy", "logo" => "https://via.placeholder.com/150", "link" => "#"],
  ["name" => "SkillUp Training Center", "logo" => "https://via.placeholder.com/150", "link" => "#"],
  ["name" => "Global Careers Hub", "logo" => "https://via.placeholder.com/150", "link" => "#"],
  ["name" => "NextGen IT Solutions", "logo" => "https://via.placeholder.com/150", "link" => "#"],
  ["name" => "BrightPath University", "logo" => "https://via.placeholder.com/150", "link" => "#"]
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Our Partners</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container py-5">
    <h1 class="mb-4 text-success">Our Partners</h1>
    <div class="row">
      <?php foreach ($partners as $partner): ?>
        <div class="col-md-4 mb-4">
          <div class="card h-100 text-center shadow-sm">
            <img src="<?= $partner['logo']; ?>" class="card-img-top p-3" alt="<?= $partner['name']; ?>">
            <div class="card-body">
              <h5 class="card-title"><?= $partner['name']; ?></h5>
              <a href="<?= $partner['link']; ?>" class="btn btn-outline-primary">Visit</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>

</html>