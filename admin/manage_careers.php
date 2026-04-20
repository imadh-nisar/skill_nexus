<?php
include __DIR__ . '/../config.php';
requireAdminLogin();

$message = '';
$error = '';
$action = $_GET['action'] ?? '';
$career_id = $_GET['id'] ?? '';
$career = null;

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        try {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $average_salary = trim($_POST['average_salary'] ?? '');
            $job_outlook = trim($_POST['job_outlook'] ?? '');
            $icon = trim($_POST['icon'] ?? 'fas fa-briefcase');
            $color_code = trim($_POST['color_code'] ?? '#667eea');
            $degrees = $_POST['degrees'] ?? [];

            if (empty($name)) {
                $error = 'Career name is required.';
            } else {
                $new_id = createCareer($pdo, $name, $description, $average_salary, $job_outlook, $icon, $color_code);
                
                // Link degrees
                foreach ($degrees as $degree_id) {
                    linkCareerToDegree($pdo, $new_id, $degree_id);
                }
                
                $message = 'Career created successfully!';
                $_POST = [];
            }
        } catch (Exception $e) {
            $error = 'Error creating career: ' . $e->getMessage();
        }
    } elseif ($action === 'update') {
        try {
            $career_id = $_POST['career_id'] ?? '';
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $average_salary = trim($_POST['average_salary'] ?? '');
            $job_outlook = trim($_POST['job_outlook'] ?? '');
            $icon = trim($_POST['icon'] ?? 'fas fa-briefcase');
            $color_code = trim($_POST['color_code'] ?? '#667eea');
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            $degrees = $_POST['degrees'] ?? [];

            if (empty($name)) {
                $error = 'Career name is required.';
            } else {
                updateCareer($pdo, $career_id, $name, $description, $average_salary, $job_outlook, $icon, $color_code, $is_active);
                
                // Update degree links
                $stmt = $pdo->prepare("DELETE FROM career_degrees WHERE career_id = :id");
                $stmt->execute([':id' => $career_id]);
                
                foreach ($degrees as $degree_id) {
                    linkCareerToDegree($pdo, $career_id, $degree_id);
                }
                
                $message = 'Career updated successfully!';
            }
        } catch (Exception $e) {
            $error = 'Error updating career: ' . $e->getMessage();
        }
    } elseif ($action === 'delete') {
        try {
            $career_id = $_POST['career_id'] ?? '';
            deleteCareer($pdo, $career_id);
            $message = 'Career deleted successfully!';
        } catch (Exception $e) {
            $error = 'Error deleting career: ' . $e->getMessage();
        }
    }
}

if ($action === 'edit' && $career_id) {
    $career = getCareerById($pdo, $career_id);
}

$careers = getAllCareers($pdo);
$all_degrees = getAllDegrees($pdo);
$career_degrees = $career ? getCareerDegrees($pdo, $career['id']) : [];
$career_degree_ids = array_column($career_degrees, 'id');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Careers - Skill NEXUS Admin</title>
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
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
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
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.3);
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
            padding: 40px;
        }

        .page-header {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .page-header h1 {
            color: #333;
            font-weight: 700;
            margin: 0;
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            border: none;
        }

        .table-responsive {
            border-radius: 15px;
            overflow: hidden;
        }

        .table {
            margin: 0;
            border-radius: 15px;
        }

        .table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            border: none;
            padding: 15px;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .badge-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        .alert {
            border: none;
            border-radius: 10px;
        }

        .alert-success {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            color: #fff;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: #fff;
        }

        .badge {
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        .modal-content {
            border: none;
            border-radius: 15px;
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .form-check {
            padding: 12px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .form-check:hover {
            background: #f8f9fa;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }

            .main-content {
                margin-left: 200px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h3><i class="fas fa-rocket"></i> Admin</h3>
        </div>
        <nav class="sidebar-menu">
            <a href="<?= BASE_URL ?>/admin/dashboard.php" class="nav-link">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
            <a href="<?= BASE_URL ?>/admin/manage_careers.php" class="nav-link active">
                <i class="fas fa-briefcase"></i> Careers
            </a>
            <a href="<?= BASE_URL ?>/admin/manage_degrees.php" class="nav-link">
                <i class="fas fa-graduation-cap"></i> Degrees
            </a>
            <a href="<?= BASE_URL ?>/admin/manage_questions.php" class="nav-link">
                <i class="fas fa-question-circle"></i> Questions
            </a>
            <a href="<?= BASE_URL ?>/admin/logout.php" class="nav-link">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1><i class="fas fa-briefcase"></i> Manage Careers</h1>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?= e($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?= e($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-list"></i> Careers List
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Salary</th>
                                        <th>Outlook</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($careers)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="fas fa-inbox text-muted"></i> No careers found
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($careers as $c): ?>
                                            <tr>
                                                <td>
                                                    <i class="<?= e($c['icon']) ?>" style="color: <?= e($c['color_code']) ?>"></i>
                                                    <?= e($c['name']) ?>
                                                </td>
                                                <td><?= e($c['average_salary']) ?></td>
                                                <td><?= e($c['job_outlook']) ?></td>
                                                <td>
                                                    <?php if ($c['is_active']): ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="?action=edit&id=<?= $c['id'] ?>" class="btn btn-sm btn-info">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" style="display:inline;" onclick="return confirm('Delete this career?');">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="career_id" value="<?= $c['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-plus-circle"></i> <?= $action === 'edit' && $career ? 'Edit Career' : 'Add New Career' ?>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="<?= $action === 'edit' && $career ? 'update' : 'create' ?>">
                            <?php if ($career): ?>
                                <input type="hidden" name="career_id" value="<?= $career['id'] ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label">Career Name *</label>
                                <input type="text" class="form-control" name="name" value="<?= $career ? e($career['name']) : e($_POST['name'] ?? '') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3"><?= $career ? e($career['description']) : e($_POST['description'] ?? '') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Average Salary</label>
                                <input type="text" class="form-control" name="average_salary" value="<?= $career ? e($career['average_salary']) : e($_POST['average_salary'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Job Outlook</label>
                                <input type="text" class="form-control" name="job_outlook" value="<?= $career ? e($career['job_outlook']) : e($_POST['job_outlook'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Icon (Font Awesome Class)</label>
                                <input type="text" class="form-control" name="icon" placeholder="fas fa-briefcase" value="<?= $career ? e($career['icon']) : e($_POST['icon'] ?? 'fas fa-briefcase') ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Color Code</label>
                                <input type="color" class="form-control" name="color_code" value="<?= $career ? e($career['color_code']) : e($_POST['color_code'] ?? '#667eea') ?>" style="height: 45px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Related Degrees</label>
                                <div class="form-check-container" style="max-height: 250px; overflow-y: auto;">
                                    <?php foreach ($all_degrees as $degree): ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="degrees[]" value="<?= $degree['id'] ?>" 
                                                id="degree_<?= $degree['id'] ?>"
                                                <?= in_array($degree['id'], $career_degree_ids) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="degree_<?= $degree['id'] ?>">
                                                <?= e($degree['name']) ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <?php if ($career): ?>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" <?= $career['is_active'] ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary-gradient flex-grow-1">
                                    <i class="fas fa-save"></i> Save
                                </button>
                                <?php if ($action === 'edit' && $career): ?>
                                    <a href="<?= BASE_URL ?>/admin/manage_careers.php" class="btn btn-secondary flex-grow-1">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
