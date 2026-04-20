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
            $average_salary = $_POST['average_salary'] ?? '';
            $job_outlook = $_POST['job_outlook'] ?? '';
            $icon = $_POST['icon'] ?? '';
            $color_code = $_POST['color_code'] ?? '#667eea';

            if (empty($name)) {
                $error = 'Career name is required';
            } else {
                createCareer($pdo, $name, $description, $average_salary, $job_outlook, $icon, $color_code);
                $message = 'Career created successfully!';
            }
        } catch (Exception $e) {
            $error = 'Error creating career: ' . $e->getMessage();
        }
    } elseif ($action === 'update') {
        try {
            $career_id = $_POST['career_id'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $average_salary = $_POST['average_salary'] ?? '';
            $job_outlook = $_POST['job_outlook'] ?? '';
            $icon = $_POST['icon'] ?? '';
            $color_code = $_POST['color_code'] ?? '#667eea';
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if (empty($name)) {
                $error = 'Career name is required';
            } else {
                updateCareer($pdo, $career_id, $name, $description, $average_salary, $job_outlook, $icon, $color_code, $is_active);
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

$careers = getAllCareers($pdo);
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #e9ecef;
        }

        tbody td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        .btn-edit,
        .btn-delete {
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background: #667eea;
            color: white;
            margin-right: 10px;
        }

        .btn-edit:hover {
            background: #764ba2;
            color: white;
        }

        .btn-delete {
            background: #f5576c;
            color: white;
        }

        .btn-delete:hover {
            background: #d63031;
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
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .close {
            color: #999;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-check {
            margin-bottom: 15px;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                min-height: auto;
                margin-bottom: 20px;
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            table {
                font-size: 0.85rem;
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
            <li><a href="<?= BASE_URL ?>/admin/dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a></li>
            <li><a href="<?= BASE_URL ?>/admin/careers.php" class="active"><i class="fas fa-briefcase"></i> Manage
                    Careers</a></li>
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
            <h1 style="margin: 0; font-size: 1.5rem; font-weight: 700; color: #333;">Manage Careers</h1>
            <button onclick="openModal('addModal')" class="btn-add">
                <i class="fas fa-plus"></i> Add New Career
            </button>
        </div>

        <!-- Content Section -->
        <div class="content-section">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Career updated successfully!
                </div>
            <?php endif; ?>

            <?php if (empty($careers)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
                    <p>No careers found. <a href="#" onclick="openModal('addModal')">Add your first career</a></p>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Career Name</th>
                                <th>Salary Range</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($careers as $career): ?>
                                <tr>
                                    <td>
                                        <strong><?= e($career['name']) ?></strong><br>
                                        <small
                                            style="color: #999;"><?= substr(e($career['description'] ?? ''), 0, 50) ?>...</small>
                                    </td>
                                    <td><?= e($career['average_salary'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge <?= $career['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $career['is_active'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button onclick="editCareer(<?= htmlspecialchars(json_encode($career)) ?>)"
                                            class="btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $career['id'] ?>">
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
            <?php endif; ?>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addModal')">&times;</span>
            <h2 id="modalTitle">Add New Career</h2>
            <form method="POST">
                <input type="hidden" name="action" id="action" value="add">
                <input type="hidden" name="id" id="careerId">

                <div class="form-group">
                    <label>Career Name *</label>
                    <input type="text" name="name" id="careerName" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="careerDescription" rows="4"></textarea>
                </div>

                <div class="form-group">
                    <label>Average Salary</label>
                    <input type="text" name="salary" id="careerSalary" placeholder="e.g., $80,000 - $120,000">
                </div>

                <div class="form-group">
                    <label>Job Outlook</label>
                    <input type="text" name="outlook" id="careerOutlook" placeholder="e.g., Strong growth">
                </div>

                <div class="form-group">
                    <label>Icon (Emoji)</label>
                    <input type="text" name="icon" id="careerIcon" placeholder="e.g., 💻">
                </div>

                <div class="form-check">
                    <input type="checkbox" name="is_active" id="careerActive" checked>
                    <label for="careerActive">Active</label>
                </div>

                <button type="submit" class="btn-submit">Save Career</button>
            </form>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function editCareer(career) {
            document.getElementById('action').value = 'edit';
            document.getElementById('modalTitle').textContent = 'Edit Career';
            document.getElementById('careerId').value = career.id;
            document.getElementById('careerName').value = career.name;
            document.getElementById('careerDescription').value = career.description || '';
            document.getElementById('careerSalary').value = career.average_salary || '';
            document.getElementById('careerOutlook').value = career.job_outlook || '';
            document.getElementById('careerIcon').value = career.icon || '';
            document.getElementById('careerActive').checked = career.is_active == 1;
            openModal('addModal');
        }

        window.onclick = function (event) {
            let modal = document.getElementById('addModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Reset form when closing
        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('close')) {
                const form = document.querySelector('form');
                form.reset();
                document.getElementById('action').value = 'add';
                document.getElementById('modalTitle').textContent = 'Add New Career';
                document.getElementById('careerId').value = '';
            }
        });
    </script>
</body>

</html>