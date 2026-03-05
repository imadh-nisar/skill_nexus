<?php
// blog.php
$posts = [
    [
        "title" => "How to Choose the Right Career Path",
        "author" => "Admin",
        "date" => "February 10, 2026",
        "content" => "Choosing a career can feel overwhelming. Start by identifying your interests, strengths, and long-term goals..."
    ],
    [
        "title" => "Top Skills Employers Value in 2026",
        "author" => "Career Team",
        "date" => "February 5, 2026",
        "content" => "Employers are increasingly looking for adaptability, problem-solving, and digital literacy..."
    ],
    [
        "title" => "Balancing Studies and Personal Growth",
        "author" => "Guest Writer",
        "date" => "January 28, 2026",
        "content" => "University life is more than academics. Building relationships and exploring hobbies are equally important..."
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Career Blog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h1 class="mb-4 text-primary">Career Guidance Blog</h1>
  <?php foreach ($posts as $post): ?>
    <div class="card mb-4 shadow-sm">
      <div class="card-body">
        <h3 class="card-title"><?= $post['title']; ?></h3>
        <p class="text-muted">By <?= $post['author']; ?> | <?= $post['date']; ?></p>
        <p class="card-text"><?= $post['content']; ?></p>
      </div>
    </div>
  <?php endforeach; ?>
</div>
</body>
</html>