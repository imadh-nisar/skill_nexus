<?php
include __DIR__ . '/../config.php';

requireAdminLogin();

$message = '';
$error = '';

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        try {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $duration = $_POST['duration'] ?? '';
            $icon = $_POST['icon'] ?? '';
            $color_code = $_POST['color_code'] ?? '#764ba2';

            if (empty($name)) {
                $error = 'Degree name is required';
            } else {
                createDegree($pdo, $name, $description, $duration, $icon, $color_code);
                $message = 'Degree created successfully!';
            }
        } catch (Exception $e) {
            $error = 'Error creating degree: ' . $e->getMessage();
        }
    } elseif ($action === 'update') {
        try {
            $degree_id = $_POST['degree_id'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $duration = $_POST['duration'] ?? '';
            $icon = $_POST['icon'] ?? '';
            $color_code = $_POST['color_code'] ?? '#764ba2';
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if (empty($name)) {
                $error = 'Degree name is required';
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
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
        }

        .btn-add {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
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

        .btn-edit,
        .btn-delete {
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background: #667eea;
            color: white;
            margin-right: 10px;
        }

        .btn-delete {
            background: #f5576c;
            color: white;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
        }

        .close {
            color: #999;
            float: right;
            font-size: 28px;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #667eea;
            outline: none;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
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
            <li><a href="<?= BASE_URL ?>/admin/degrees.php" class="active"><i class="fas fa-graduation-cap"></i> Manage
                    Degrees</a></li>
            <li><a href="<?= BASE_URL ?>/admin/questions.php"><i class="fas fa-question-circle"></i> Manage
                    Questions</a></li>
            <li><a href="<?= BASE_URL ?>/admin/results.php"><i class="fas fa-chart-bar"></i> View Results</a></li>
            <li><a href="<?= BASE_URL ?>/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1 style="margin: 0; font-size: 1.5rem; font-weight: 700;">Manage Degrees</h1>
            <button onclick="openModal()" class="btn-add">
                <i class="fas fa-plus"></i> Add New Degree
            </button>
        </div>

        <div class="content-section">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Degree updated successfully!
                </div>
            <?php endif; ?>

            <?php if (!empty($degrees)): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Degree Name</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($degrees as $degree): ?>
                                <tr>
                                    <td>
                                        <strong><?= e($degree['name']) ?></strong><br>
                                        <small
                                            style="color: #999;"><?= substr(e($degree['description'] ?? ''), 0, 50) ?>...</small>
                                    </td>
                                    <td><?= e($degree['duration'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge <?= $degree['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $degree['is_active'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button onclick="editDegree(<?= htmlspecialchars(json_encode($degree)) ?>)"
                                            class="btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Sure?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $degree['id'] ?>">
                                            <button type="submit" class="btn-delete">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No degrees found. <a href="#" onclick="openModal()">Add your first degree</a></p>
            <?php endif; ?>
        </div>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Add New Degree</h2>
            <form method="POST">
                <input type="hidden" name="action" id="action" value="add">
                <input type="hidden" name="id" id="degreeId">

                <div class="form-group">
                    <label>Degree Name *</label>
                    <input type="text" name="name" id="degreeName" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="degreeDescription" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label>Duration (e.g., 4 Years)</label>
                    <input type="text" name="duration" id="degreeDuration">
                </div>

                <div class="form-group">
                    <label>Icon (Emoji)</label>
                    <input type="text" name="icon" id="degreeIcon" placeholder="e.g., 🎓">
                </div>

                <div class="form-group">
                    <label>Color Code</label>
                    <input type="color" name="color" id="degreeColor" value="#667eea">
                </div>

                <div class="form-check" style="margin-bottom: 20px;">
                    <input type="checkbox" name="is_active" id="degreeActive" checked>
                    <label for="degreeActive">Active</label>
                </div>

                <button type="submit" class="btn-submit">Save Degree</button>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
            document.querySelector('form').reset();
            document.getElementById('action').value = 'add';
            document.getElementById('modalTitle').textContent = 'Add New Degree';
        }

        function editDegree(degree) {
            document.getElementById('action').value = 'edit';
            document.getElementById('modalTitle').textContent = 'Edit Degree';
            document.getElementById('degreeId').value = degree.id;
            document.getElementById('degreeName').value = degree.name;
            document.getElementById('degreeDescription').value = degree.description || '';
            document.getElementById('degreeDuration').value = degree.duration || '';
            document.getElementById('degreeIcon').value = degree.icon || '';
            document.getElementById('degreeColor').value = degree.color_code || '#667eea';
            document.getElementById('degreeActive').checked = degree.is_active == 1;
            openModal();
        }

        window.onclick = function (event) {
            let modal = document.getElementById('modal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>