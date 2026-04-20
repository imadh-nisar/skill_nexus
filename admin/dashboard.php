<?php
include __DIR__ . '/../config.php';

requireAdminLogin();

// Get statistics
$stats = [
    'careers' => countCareers($pdo),
    'degrees' => countDegrees($pdo),
    'questions' => countQuestions($pdo),
    'results' => countTestResults($pdo)
];

// Get recent test results
$recent_results = $pdo->query("
    SELECT * FROM test_results 
    ORDER BY created_at DESC 
    LIMIT 10
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Skill NEXUS</title>
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
            background: #f8f9fa;
        }

        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-height: 100vh;
            padding: 30px 0;
            position: fixed;
            width: 250px;
            left: 0;
            top: 0;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
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

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: block;
            padding: 15px 30px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: white;
            padding-left: 35px;
        }

        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: white;
            font-weight: 600;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .topbar-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .admin-info {
            text-align: right;
        }

        .admin-info p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }

        .btn-logout {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .stat-icon.careers {
            color: #667eea;
        }

        .stat-icon.degrees {
            color: #764ba2;
        }

        .stat-icon.questions {
            color: #f093fb;
        }

        .stat-icon.results {
            color: #FF6B6B;
        }

        .content-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-add {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .menu-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
        }

        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            color: #333;
        }

        .menu-card-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .menu-card h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .menu-card p {
            color: #999;
            margin: 0;
            font-size: 0.9rem;
        }

        .content-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-add {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .menu-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
        }

        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            color: #333;
        }

        .menu-card-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .menu-card h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .menu-card p {
            color: #999;
            margin: 0;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                min-height: auto;
            }

            .main-content {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .topbar {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>🚀 Skill NEXUS</h3>
        </div>
        <ul class="sidebar-menu">
            <li><a href="<?= BASE_URL ?>/admin/dashboard.php" class="active"><i class="fas fa-chart-line"></i>
                    Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>/admin/careers.php"><i class="fas fa-briefcase"></i> Manage Careers</a></li>
            <li><a href="<?= BASE_URL ?>/admin/degrees.php"><i class="fas fa-graduation-cap"></i> Manage Degrees</a>
            </li>
            <li><a href="<?= BASE_URL ?>/admin/questions.php"><i class="fas fa-question-circle"></i> Manage
                    Questions</a></li>
            <li><a href="<?= BASE_URL ?>/admin/results.php"><i class="fas fa-chart-bar"></i> View Results</a></li>
            <li><a href="<?= BASE_URL ?>/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <h1 class="topbar-title">Dashboard</h1>
            <div class="topbar-right">
                <div class="admin-info">
                    <p><strong>Welcome back!</strong></p>
                    <p><?= e($_SESSION['admin_name'] ?? 'Admin') ?></p>
                </div>
                <a href="<?= BASE_URL ?>/admin/logout.php" class="btn-logout">Logout</a>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon icon-careers"><i class="fas fa-briefcase"></i></div>
                <div class="stat-number"><?= $stats['careers'] ?></div>
                <div class="stat-label">Total Careers</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-degrees"><i class="fas fa-graduation-cap"></i></div>
                <div class="stat-number"><?= $stats['degrees'] ?></div>
                <div class="stat-label">Total Degrees</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-questions"><i class="fas fa-question-circle"></i></div>
                <div class="stat-number"><?= $stats['questions'] ?></div>
                <div class="stat-label">Assessment Questions</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon icon-results"><i class="fas fa-chart-bar"></i></div>
                <div class="stat-number"><?= $stats['results'] ?></div>
                <div class="stat-label">Test Results</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="content-section">
            <h2 class="section-title">Quick Actions</h2>
            <div class="menu-grid">
                <a href="<?= BASE_URL ?>/admin/careers.php" class="menu-card">
                    <div class="menu-card-icon">💼</div>
                    <h3>Manage Careers</h3>
                    <p>Add, edit, or delete career paths</p>
                </a>
                <a href="<?= BASE_URL ?>/admin/degrees.php" class="menu-card">
                    <div class="menu-card-icon">🎓</div>
                    <h3>Manage Degrees</h3>
                    <p>Manage educational programs</p>
                </a>
                <a href="<?= BASE_URL ?>/admin/questions.php" class="menu-card">
                    <div class="menu-card-icon">❓</div>
                    <h3>Manage Questions</h3>
                    <p>Edit assessment questions</p>
                </a>
                <a href="<?= BASE_URL ?>/admin/results.php" class="menu-card">
                    <div class="menu-card-icon">📊</div>
                    <h3>View Results</h3>
                    <p>Analyze test results</p>
                </a>
            </div>
        </div>

        <!-- Recent Results -->
        <div class="content-section">
            <h2 class="section-title">Recent Test Results</h2>
            <?php if (empty($recent_results)): ?>
                <p class="text-muted">No test results yet.</p>
            <?php else: ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Session ID</th>
                            <th>Date Taken</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_results as $result): ?>
                            <tr>
                                <td><code><?= substr(e($result['session_id']), 0, 20) ?>...</code></td>
                                <td><?= date('M d, Y H:i', strtotime($result['created_at'])) ?></td>
                                <td><span class="badge badge-success">Completed</span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>