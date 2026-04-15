<?php
include __DIR__ . '/../config.php';

requireAdminLogin();

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete' && isset($_POST['id'])) {
        $stmt = $pdo->prepare("DELETE FROM questions WHERE id = :id");
        $stmt->execute([':id' => $_POST['id']]);
        header('Location: ' . BASE_URL . '/admin/questions.php?success=1');
        exit;
    } elseif ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
        $id = $_POST['id'] ?? null;
        $question = $_POST['question'] ?? '';
        $category = $_POST['category'] ?? '';
        $seq = $_POST['sequence'] ?? 0;
        $is_active = isset($_POST['is_active']) ? 1 : 0;

        if (empty($seq)) {
            // Auto-assign next sequence
            $result = $pdo->query("SELECT MAX(sequence) as max_seq FROM questions")->fetch();
            $seq = ($result['max_seq'] ?? 0) + 1;
        }

        if ($_POST['action'] === 'add') {
            $stmt = $pdo->prepare("
                INSERT INTO questions (question_text, category, sequence, is_active)
                VALUES (:question, :category, :seq, :active)
            ");
        } else {
            $stmt = $pdo->prepare("
                UPDATE questions SET question_text = :question, category = :category, 
                sequence = :seq, is_active = :active WHERE id = :id
            ");
            $stmt->bindParam(':id', $id);
        }

        $stmt->execute([
            ':question' => $question,
            ':category' => $category,
            ':seq' => $seq,
            ':active' => $is_active
        ]);

        header('Location: ' . BASE_URL . '/admin/questions.php?success=1');
        exit;
    }
}

$questions = $pdo->query("SELECT * FROM questions ORDER BY sequence ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Questions - Skill NEXUS Admin</title>
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
            background: rgba(0, 0, 0, 0.5);
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
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
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
            <li><a href="<?= BASE_URL ?>/admin/questions.php" class="active"><i class="fas fa-question-circle"></i>
                    Manage Questions</a></li>
            <li><a href="<?= BASE_URL ?>/admin/results.php"><i class="fas fa-chart-bar"></i> View Results</a></li>
            <li><a href="<?= BASE_URL ?>/admin/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h1 style="margin: 0; font-size: 1.5rem; font-weight: 700;">Manage Assessment Questions</h1>
            <button onclick="openModal()" class="btn-add">
                <i class="fas fa-plus"></i> Add Question
            </button>
        </div>

        <div class="content-section">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Question updated successfully!
                </div>
            <?php endif; ?>

            <?php if (!empty($questions)): ?>
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Question</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($questions as $q): ?>
                                <tr>
                                    <td><strong><?= $q['sequence'] ?></strong></td>
                                    <td><?= e($q['question_text']) ?></td>
                                    <td><?= e($q['category'] ?? '-') ?></td>
                                    <td>
                                        <span class="badge <?= $q['is_active'] ? 'bg-success' : 'bg-danger' ?>">
                                            <?= $q['is_active'] ? 'Active' : 'Inactive' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button onclick="editQuestion(<?= htmlspecialchars(json_encode($q)) ?>)"
                                            class="btn-edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Sure?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $q['id'] ?>">
                                            <button type="submit" class="btn-delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p>No questions found. <a href="#" onclick="openModal()">Add your first question</a></p>
            <?php endif; ?>
        </div>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Add Assessment Question</h2>
            <form method="POST">
                <input type="hidden" name="action" id="action" value="add">
                <input type="hidden" name="id" id="questionId">

                <div class="form-group">
                    <label>Question Text *</label>
                    <textarea name="question" id="questionText" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category" id="questionCategory">
                        <option value="">Select a category</option>
                        <option value="Interest">Interest</option>
                        <option value="Lifestyle">Lifestyle</option>
                        <option value="Problem-solving">Problem-solving</option>
                        <option value="Motivation">Motivation</option>
                        <option value="Personality">Personality</option>
                        <option value="Work Environment">Work Environment</option>
                        <option value="Career Path">Career Path</option>
                        <option value="Stress Management">Stress Management</option>
                        <option value="Task Preference">Task Preference</option>
                        <option value="Balance">Balance</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Sequence Number</label>
                    <input type="number" name="sequence" id="questionSequence" min="1"
                        value="<?= (count($questions) + 1) ?>">
                </div>

                <div class="form-check" style="margin-bottom: 20px;">
                    <input type="checkbox" name="is_active" id="questionActive" checked>
                    <label for="questionActive">Active</label>
                </div>

                <button type="submit" class="btn-submit">Save Question</button>
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
            document.getElementById('modalTitle').textContent = 'Add Assessment Question';
        }

        function editQuestion(q) {
            document.getElementById('action').value = 'edit';
            document.getElementById('modalTitle').textContent = 'Edit Question';
            document.getElementById('questionId').value = q.id;
            document.getElementById('questionText').value = q.question_text;
            document.getElementById('questionCategory').value = q.category || '';
            document.getElementById('questionSequence').value = q.sequence;
            document.getElementById('questionActive').checked = q.is_active == 1;
            openModal();
        }

        window.onclick = function (event) {
            let modal = document.getElementById('modal');
            if (event.target == modal) closeModal();
        }
    </script>
</body>

</html>