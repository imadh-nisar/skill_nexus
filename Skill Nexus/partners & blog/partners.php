<?php
require_once __DIR__ . '/../config.php';

$partners = [
  ["name" => "Future Minds Academy", "description" => "Leading provider of career counseling and skill development", "logo" => "https://via.placeholder.com/150", "link" => "#"],
  ["name" => "SkillUp Training Center", "description" => "Professional training in emerging technologies and career skills", "logo" => "https://via.placeholder.com/150", "link" => "#"],
  ["name" => "Global Careers Hub", "description" => "Connecting students with international career opportunities", "logo" => "https://via.placeholder.com/150", "link" => "#"],
  ["name" => "NextGen IT Solutions", "description" => "Industry partner providing tech career pathways and internships", "logo" => "https://via.placeholder.com/150", "link" => "#"],
  ["name" => "BrightPath University", "description" => "Academic institution committed to student success and career development", "logo" => "https://via.placeholder.com/150", "link" => "#"]
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Our Partners - Skill NEXUS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      min-height: 100vh;
    }

    .partners-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 60px 20px;
      text-align: center;
      margin-top: 20px;
    }

    .partners-header h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 15px;
    }

    .partners-content {
      max-width: 1200px;
      margin: 40px auto;
      padding: 20px;
    }

    .partner-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: all 0.3s ease;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    .partner-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
    }

    .partner-card img {
      width: 100%;
      height: 200px;
      object-fit: cover;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .partner-card .card-body {
      padding: 25px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .partner-card .card-title {
      font-size: 1.3rem;
      font-weight: 700;
      color: #333;
      margin-bottom: 10px;
    }

    .partner-card .card-text {
      color: #666;
      font-size: 0.95rem;
      flex-grow: 1;
      margin-bottom: 15px;
    }

    .partner-card .btn {
      align-self: flex-start;
    }
  </style>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles.css">
</head>

<body>
  <?php renderNav(); ?>

  <div class="partners-header">
    <h1><i class="fas fa-handshake"></i> Our Partners</h1>
    <p class="lead">Organizations Committed to Student Success and Career Development</p>
  </div>

  <div class="partners-content">
    <div class="row g-4">
      <?php foreach ($partners as $partner): ?>
        <div class="col-md-6 col-lg-4 mb-4">
          <div class="partner-card">
            <img src="<?= e($partner['logo']); ?>" class="card-img-top" alt="<?= e($partner['name']); ?>">
            <div class="card-body">
              <h5 class="card-title"><?= e($partner['name']); ?></h5>
              <p class="card-text"><?= e($partner['description']); ?></p>
              <a href="<?= e($partner['link']); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-external-link-alt"></i> Learn More
              </a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <?php renderFooter(); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>