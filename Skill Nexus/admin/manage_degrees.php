<?php
include __DIR__ . '/../config.php';
requireAdminLogin();

$message = '';
$error = '';
$action = $_GET['action'] ?? '';
$degree_id = $_GET['id'] ?? '';
$degree = null;

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        try {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $duration = trim($_POST['duration'] ?? '');
            $icon = trim($_POST['icon'] ?? 'fas fa-graduation-cap');
            $color_code = trim($_POST['color_code'] ?? '#764ba2');

            if (empty($name)) {
                $error = 'Degree name is required.';
            } else {
                createDegree($pdo, $name, $description, $duration, $icon, $color_code);
                $message = 'Degree created successfully!';
                $_POST = [];
            }
        } catch (Exception $e) {
            $error = 'Error creating degree: ' . $e->getMessage();
        }
    } elseif ($action === 'update') {
        try {
            $degree_id = $_POST['degree_id'] ?? '';
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $duration = trim($_POST['duration'] ?? '');
            $icon = trim($_POST['icon'] ?? 'fas fa-graduation-cap');
            $color_code = trim($_POST['color_code'] ?? '#764ba2');
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if (empty($name)) {
                $error = 'Degree name is required.';
            } else {
                updateDegree($pdo, $degree_id, $name, $description, $duration, $icon, $color_code, $is_active);
                $message = 'Degree updated successfully!';
            }
        } catch (Exception $e) {
            $error = 'Error updating degree: ' . $e->getMessage();
        }
    } elseif ($action === 'delete') {
        try {
            $degree_id = $_POST['degree_id'] ?? '';
            deleteDegree($pdo, $degree_id);
            $message = 'Degree deleted successfully!';
        } catch (Exception $e) {
            $error = 'Error deleting degree: ' . $e->getMessage();
        }
    }
}

if ($action === 'edit' && $degree_id) {
    $degree = getDegreeById($pdo, $degree_id);
}

$degrees = getAllDegrees($pdo);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Degrees - Skill NEXUS Admin</title>
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

        .form-control,
        .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
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

        .degree-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid;
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
            <a href="<?= BASE_URL ?>/admin/manage_careers.php" class="nav-link">
                <i class="fas fa-briefcase"></i> Careers
            </a>
            <a href="<?= BASE_URL ?>/admin/manage_degrees.php" class="nav-link active">
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
            <h1><i class="fas fa-graduation-cap"></i> Manage Degrees</h1>
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
                        <i class="fas fa-list"></i> Degrees List
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($degrees)): ?>
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <i class="fas fa-inbox text-muted"></i> No degrees found
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($degrees as $d): ?>
                                            <tr>
                                                <td>
                                                    <i class="<?= e($d['icon']) ?>"
                                                        style="color: <?= e($d['color_code']) ?>"></i>
                                                    <?= e($d['name']) ?>
                                                </td>
                                                <td><?= e($d['duration']) ?></td>
                                                <td>
                                                    <?php if ($d['is_active']): ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="?action=edit&id=<?= $d['id'] ?>" class="btn btn-sm btn-info">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form method="POST" style="display:inline;"
                                                        onclick="return confirm('Delete this degree?');">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="degree_id" value="<?= $d['id'] ?>">
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
                        <i class="fas fa-plus-circle"></i>
                        <?= $action === 'edit' && $degree ? 'Edit Degree' : 'Add New Degree' ?>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action"
                                value="<?= $action === 'edit' && $degree ? 'update' : 'create' ?>">
                            <?php if ($degree): ?>
                                <input type="hidden" name="degree_id" value="<?= $degree['id'] ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label">Degree Name *</label>
                                <input type="text" class="form-control" name="name"
                                    value="<?= $degree ? e($degree['name']) : e($_POST['name'] ?? '') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description"
                                    rows="3"><?= $degree ? e($degree['description']) : e($_POST['description'] ?? '') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Duration</label>
                                <input type="text" class="form-control" placeholder="e.g., 4 years" name="duration"
                                    value="<?= $degree ? e($degree['duration']) : e($_POST['duration'] ?? '') ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Icon (Font Awesome Class)</label>
                                <input type="text" class="form-control" name="icon" placeholder="fas fa-graduation-cap"
                                    value="<?= $degree ? e($degree['icon']) : e($_POST['icon'] ?? 'fas fa-graduation-cap') ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Color Code</label>
                                <input type="color" class="form-control" name="color_code"
                                    value="<?= $degree ? e($degree['color_code']) : e($_POST['color_code'] ?? '#764ba2') ?>"
                                    style="height: 45px;">
                            </div>

                            <?php if ($degree): ?>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                            <?= $degree['is_active'] ? 'checked' : '' ?>>
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
                                <?php if ($action === 'edit' && $degree): ?>
                                    <a href="<?= BASE_URL ?>/admin/manage_degrees.php"
                                        class="btn btn-secondary flex-grow-1">
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