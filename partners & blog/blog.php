<?php
require_once __DIR__ . '/../config.php';

$posts = [
  [
    "title" => "How to Choose the Right Career Path",
    "author" => "Admin",
    "date" => "February 10, 2026",
    "content" => "Choosing a career can feel overwhelming. Start by identifying your interests, strengths, and long-term goals. Think about what makes you happy, what problems you enjoy solving, and what kind of work environment suits you best."
  ],
  [
    "title" => "Top Skills Employers Value in 2026",
    "author" => "Career Team",
    "date" => "February 5, 2026",
    "content" => "Employers are increasingly looking for adaptability, problem-solving, digital literacy, and soft skills like communication and teamwork. Invest in continuous learning and stay updated with industry trends."
  ],
  [
    "title" => "Balancing Studies and Personal Growth",
    "author" => "Guest Writer",
    "date" => "January 28, 2026",
    "content" => "University life is more than academics. Building relationships and exploring hobbies are equally important. Remember, your college years shape not just your career but your entire personality."
  ]
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Career Blog - Skill NEXUS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      min-height: 100vh;
    }

    .blog-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 60px 20px;
      text-align: center;
      margin-top: 20px;
    }

    .blog-header h1 {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 15px;
    }

    .blog-content {
      max-width: 900px;
      margin: 40px auto;
      padding: 20px;
    }

    .blog-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      margin-bottom: 30px;
      overflow: hidden;
      transition: all 0.3s ease;
      border-left: 5px solid #667eea;
    }

    .blog-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .blog-card .card-body {
      padding: 30px;
    }

    .blog-card .card-title {
      font-size: 1.5rem;
      font-weight: 700;
      color: #333;
      margin-bottom: 10px;
    }

    .blog-meta {
      color: #999;
      font-size: 0.9rem;
      margin-bottom: 15px;
    }

    .blog-card .card-text {
      color: #666;
      line-height: 1.8;
      font-size: 1rem;
    }
  </style>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles.css">
</head>

<body>
  <?php renderNav(); ?>

  <div class="blog-header">
    <h1><i class="fas fa-blog"></i> Career Guidance Blog</h1>
    <p class="lead">Insights, Tips, and Stories to Guide Your Career Journey</p>
  </div>

  <div class="blog-content">
    <?php foreach ($posts as $post): ?>
      <div class="blog-card">
        <div class="card-body">
          <h3 class="card-title"><?= e($post['title']); ?></h3>
          <p class="blog-meta">
            <i class="fas fa-user"></i> <?= e($post['author']); ?> |
            <i class="fas fa-calendar"></i> <?= e($post['date']); ?>
          </p>
          <p class="card-text"><?= e($post['content']); ?></p>
          <a href="#" class="btn btn-primary btn-sm mt-3">Read More</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <?php renderFooter(); ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>