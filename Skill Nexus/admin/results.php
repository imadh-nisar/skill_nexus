<?php
include __DIR__ . '/../config.php';

requireAdminLogin();

// Get paginated results
$page = $_GET['page'] ?? 1;
$limit = 20;
$offset = ($page - 1) * $limit;

$stmt = $pdo->prepare("
    SELECT * FROM test_results 
    ORDER BY created_at DESC 
    LIMIT :limit OFFSET :offset
");
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll();

// Get total count
$total = $pdo->query("SELECT COUNT(*) as count FROM test_results")->fetch()['count'];
$pages = ceil($total / $limit);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Results - Skill NEXUS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI';
            background: #f8f9fa;
        }

        .sidebar {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            min-height: 100vh;
            padding: 30px 0;
            position: fixed;
            width: 250px;
            left: 0;
            top: 0;
        }

        .sidebar-header {
            padding: 0 30px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 30px;
        }

        .sidebar-header h3 {
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
        }

        .sidebar-menu a {
            display: block;
            padding: 15px 30px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: white;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
        }

        .topbar {
            background: white;
            border-radius: 15px;
            padding: 20px 30px;
            margin-bottom: 30px;
        }

        .content-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: #f8f9fa;
            font-weight: 600;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #e9ecef;
        }

        tbody td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        .pagination {
            justify-content: center;
            margin-top: 30px;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>🚀 Skill NEXUS</h3>
        </div>
        <ul class="sidebar-menu" style="list-style: none; padding: 0;">
            <li><a href="<?= BASE_URL ?>/admin/dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>/admin/careers.php"><i class="fas fa-briefcase"></i> Manage Careers</a></li>
            <li><a href="<?= BASE_URL ?>/admin/degrees.php"><i class="fas fa-graduation-cap"></i> Manage Degrees</a>
            </li>
            <li><a href="<?= BASE_URL ?>/admin/questions.php"><i class="fas fa-question-circle"></i> Manage
                    Questions</a></li>
            <li><a href="<?= BASE_URL ?>/admin/results.php" class="active"><i class="fas fa-chart-bar"></i> View
                    Results</a></li>
            <li><a href="<?= BASE_URL ?>/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1 style="margin: 0; font-size: 1.5rem; font-weight: 700;">Test Results Analytics</h1>
        </div>

        <div class="content-section">
            <h2 class="section-title">Recent Assessment Results</h2>
            <p style="color: #999; margin-bottom: 20px;">Showing <?= count($results) ?> of <?= $total ?> total results
            </p>

            <?php if (!empty($results)): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Session ID</th>
                                <th>Date</th>
                                <th>Career Recommendations</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $result): ?>
                                <tr>
                                    <td>
                                        <code style="background: #f0f0f0; padding: 5px 10px; border-radius: 5px;">
                                                    <?= substr($result['session_id'], 0, 20) ?>...
                                                </code>
                                    </td>
                                    <td><?= date('M d, Y H:i', strtotime($result['created_at'])) ?></td>
                                    <td>
                                        <?php
                                        $recommendations = json_decode($result['career_recommendations'], true);
                                        if (is_array($recommendations)) {
                                            echo count($recommendations) . ' career(s)';
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($result['overall_score']): ?>
                                            <span class="badge bg-success"><?= round($result['overall_score']) ?>%</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($pages > 1): ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $pages; $i++): ?>
                                <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 40px; color: #999;">
                    <p><i class="fas fa-inbox" style="font-size: 3rem; opacity: 0.5;"></i></p>
                    <p>No test results available yet.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>