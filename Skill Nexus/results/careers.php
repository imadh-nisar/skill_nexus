<?php
include __DIR__ . '/../config.php';

$page = $_GET['page'] ?? 1;
$limit = 12;
$offset = ($page - 1) * $limit;
$careers = getAllCareers($pdo, $limit, $offset);
$total_careers = countCareers($pdo);
$total_pages = ceil($total_careers / $limit);
?>
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Careers - Skill NEXUS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI';
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .career-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 40px;
            margin: 0 auto;
            max-width: 1400px;
        }

        .career-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .career-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .career-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .career-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .career-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
        }

        .career-body {
            padding: 20px;
        }

        .career-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 15px;
            min-height: 50px;
        }

        .career-meta {
            display: grid;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.85rem;
            color: #666;
        }

        .meta-item strong {
            color: #333;
        }

        .degrees-preview {
            margin-bottom: 15px;
        }

        .degrees-label {
            font-size: 0.75rem;
            color: #999;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .degree-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .degree-pill {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            color: #667eea;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .btn-explore {
            width: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: block;
        }

        .btn-explore:hover {
            background: linear-gradient(135deg, #764ba2, #667eea);
            color: white;
            transform: translateY(-2px);
        }

        .header-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 20px;
            text-align: center;
            margin-bottom: 40px;
        }

        .header-section h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .header-section p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
        }

        .pagination {
            justify-content: center;
            margin-top: 40px;
            margin-bottom: 40px;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #999;
        }

        .empty-state-icon {
            font-size: 4rem;
            opacity: 0.5;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .career-grid {
                grid-template-columns: 1fr;
                padding: 20px;
            }

            .header-section h1 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>
    <?php renderNav(); ?>

    <div class="header-section">
        <h1>🌟 Explore Careers</h1>
        <p>Discover 100+ career paths and find the right fit for you</p>
    </div>

    <?php if (!empty($careers)): ?>
        <div class="career-grid">
            <?php foreach ($careers as $career):
                $degrees = getCareerDegrees($pdo, $career['id']);
                ?>
                <div class="career-card">
                    <div class="career-header">
                        <div class="career-icon"><?= e($career['icon'] ?? '💼') ?></div>
                        <h3 class="career-title"><?= e($career['name']) ?></h3>
                    </div>
                    <div class="career-body">
                        <p class="career-description">
                            <?= e(substr($career['description'] ?? '', 0, 100)) ?>
                            <?= strlen($career['description'] ?? '') > 100 ? '...' : '' ?>
                        </p>

                        <div class="career-meta">
                            <?php if ($career['average_salary']): ?>
                                <div class="meta-item">
                                    <strong>💰 Salary:</strong> <?= e($career['average_salary']) ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($career['job_outlook']): ?>
                                <div class="meta-item">
                                    <strong>📈 Outlook:</strong> <?= e($career['job_outlook']) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($degrees)): ?>
                            <div class="degrees-preview">
                                <div class="degrees-label">Related Degrees</div>
                                <div class="degree-pills">
                                    <?php foreach ($degrees as $degree): ?>
                                        <span class="degree-pill"><?= e($degree['name']) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <a href="<?= BASE_URL ?>/career/test.php" class="btn-explore">
                            <i class="fas fa-arrow-right"></i> Take Assessment
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if ($pages > 1): ?>
            <nav aria-label="Careers pagination">
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=1">First</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                        </li>
                    <?php endif; ?>

                    <?php
                    $start = max(1, $page - 2);
                    $end = min($pages, $page + 2);
                    for ($i = $start; $i <= $end; $i++):
                        ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($page < $pages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $pages ?>">Last</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-state-icon"><i class="fas fa-briefcase"></i></div>
            <h3>No Careers Found</h3>
            <p>Check back soon for exciting career opportunities!</p>
        </div>
    <?php endif; ?>

    <footer
        style="background: linear-gradient(135deg, #2d3561 0%, #1a1f3a 100%); color: white; padding: 40px 20px; text-align: center; margin-top: 60px;">
        <p style="margin: 0;">&copy; 2026 Skill NEXUS. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>