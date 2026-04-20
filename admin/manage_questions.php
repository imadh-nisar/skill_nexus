<?php
include __DIR__ . '/../config.php';
requireAdminLogin();

$message = '';
$error = '';
$action = $_GET['action'] ?? '';
$question_id = $_GET['id'] ?? '';
$question = null;
$options = [];

// Handle question CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_action = $_POST['action'] ?? '';

    if ($post_action === 'create_question') {
        try {
            $question_text = trim($_POST['question_text'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $sequence = intval($_POST['sequence'] ?? 0);

            if (empty($question_text) || empty($sequence)) {
                $error = 'Question text and sequence are required.';
            } else {
                createQuestion($pdo, $question_text, $category, $sequence);
                $message = 'Question created successfully!';
                $_POST = [];
            }
        } catch (Exception $e) {
            $error = 'Error creating question: ' . $e->getMessage();
        }
    } elseif ($post_action === 'update_question') {
        try {
            $qid = $_POST['question_id'] ?? '';
            $question_text = trim($_POST['question_text'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $sequence = intval($_POST['sequence'] ?? 0);
            $is_active = isset($_POST['is_active']) ? 1 : 0;

            if (empty($question_text) || empty($sequence)) {
                $error = 'Question text and sequence are required.';
            } else {
                updateQuestion($pdo, $qid, $question_text, $category, $sequence, $is_active);
                $message = 'Question updated successfully!';
            }
        } catch (Exception $e) {
            $error = 'Error updating question: ' . $e->getMessage();
        }
    } elseif ($post_action === 'delete_question') {
        try {
            $qid = $_POST['question_id'] ?? '';
            deleteQuestion($pdo, $qid);
            $message = 'Question deleted successfully!';
        } catch (Exception $e) {
            $error = 'Error deleting question: ' . $e->getMessage();
        }
    } elseif ($post_action === 'add_option') {
        try {
            $qid = $_POST['question_id'] ?? '';
            $option_text = trim($_POST['option_text'] ?? '');
            $option_value = intval($_POST['option_value'] ?? 0);
            $opt_sequence = intval($_POST['option_sequence'] ?? 0);

            if (empty($option_text) || $option_value === null) {
                $error = 'Option text and value are required.';
            } else {
                createQuestionOption($pdo, $qid, $option_text, $option_value, $opt_sequence);
                $message = 'Option added successfully!';
            }
        } catch (Exception $e) {
            $error = 'Error adding option: ' . $e->getMessage();
        }
    } elseif ($post_action === 'delete_option') {
        try {
            $opt_id = $_POST['option_id'] ?? '';
            deleteQuestionOption($pdo, $opt_id);
            $message = 'Option deleted successfully!';
        } catch (Exception $e) {
            $error = 'Error deleting option: ' . $e->getMessage();
        }
    }
}

if ($action === 'edit' && $question_id) {
    $question = getQuestionById($pdo, $question_id);
    $options = getQuestionOptionsById($pdo, $question_id);
}

$questions = getAllQuestions($pdo);
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
            margin-bottom: 30px;
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

        .question-item {
            background: white;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .question-item:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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

        .option-badge {
            display: inline-block;
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 8px;
            margin-right: 8px;
            margin-bottom: 8px;
            border-left: 3px solid #667eea;
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
            <a href="<?= BASE_URL ?>/admin/manage_degrees.php" class="nav-link">
                <i class="fas fa-graduation-cap"></i> Degrees
            </a>
            <a href="<?= BASE_URL ?>/admin/manage_questions.php" class="nav-link active">
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
            <h1><i class="fas fa-question-circle"></i> Manage Assessment Questions</h1>
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
            <!-- Questions List -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-list"></i> Questions List
                    </div>
                    <div class="card-body">
                        <?php if (empty($questions)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">No questions found</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($questions as $q): ?>
                                <div class="question-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-2">
                                                <strong><?= $q['sequence'] ?>.</strong>
                                                <?= e(substr($q['question_text'], 0, 60)) ?>        <?= strlen($q['question_text']) > 60 ? '...' : '' ?>
                                            </h6>
                                            <p class="mb-0 text-muted small">
                                                <i class="fas fa-tag"></i> <?= e($q['category']) ?>
                                                <?php if ($q['is_active']): ?>
                                                    <span class="badge bg-success ms-2">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary ms-2">Inactive</span>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        <div>
                                            <a href="?action=edit&id=<?= $q['id'] ?>" class="btn btn-sm btn-info" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" style="display:inline;"
                                                onclick="return confirm('Delete this question and all options?');">
                                                <input type="hidden" name="action" value="delete_question">
                                                <input type="hidden" name="question_id" value="<?= $q['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Add/Edit Form -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-plus-circle"></i>
                        <?= $action === 'edit' && $question ? 'Edit Question' : 'Add New Question' ?>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action"
                                value="<?= $action === 'edit' && $question ? 'update_question' : 'create_question' ?>">
                            <?php if ($question): ?>
                                <input type="hidden" name="question_id" value="<?= $question['id'] ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label">Sequence # *</label>
                                <input type="number" class="form-control" name="sequence"
                                    value="<?= $question ? e($question['sequence']) : e($_POST['sequence'] ?? '') ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Question Text *</label>
                                <textarea class="form-control" name="question_text" rows="4"
                                    required><?= $question ? e($question['question_text']) : e($_POST['question_text'] ?? '') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <input type="text" class="form-control" name="category"
                                    value="<?= $question ? e($question['category']) : e($_POST['category'] ?? '') ?>">
                            </div>

                            <?php if ($question): ?>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                            <?= $question['is_active'] ? 'checked' : '' ?>>
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
                                <?php if ($action === 'edit' && $question): ?>
                                    <a href="<?= BASE_URL ?>/admin/manage_questions.php"
                                        class="btn btn-secondary flex-grow-1">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Options Management -->
                <?php if ($question): ?>
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-list-ul"></i> Answer Options
                        </div>
                        <div class="card-body">
                            <form method="POST" class="mb-4">
                                <input type="hidden" name="action" value="add_option">
                                <input type="hidden" name="question_id" value="<?= $question['id'] ?>">

                                <div class="mb-2">
                                    <label class="form-label">Option Text</label>
                                    <input type="text" class="form-control" name="option_text"
                                        placeholder="e.g., Collaborative team environment" required>
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Option Value</label>
                                    <input type="number" class="form-control" name="option_value" placeholder="e.g., 1"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Sequence</label>
                                    <input type="number" class="form-control" name="option_sequence" placeholder="e.g., 1">
                                </div>

                                <button type="submit" class="btn btn-primary-gradient w-100">
                                    <i class="fas fa-plus"></i> Add Option
                                </button>
                            </form>

                            <div>
                                <h6>Existing Options:</h6>
                                <?php if (empty($options)): ?>
                                    <p class="text-muted small">No options yet</p>
                                <?php else: ?>
                                    <?php foreach ($options as $opt): ?>
                                        <div class="option-badge d-flex justify-content-between align-items-center">
                                            <span>
                                                <strong><?= $opt['option_value'] ?>:</strong>
                                                <?= e(substr($opt['option_text'], 0, 30)) ?>
                                            </span>
                                            <form method="POST" style="display:inline;"
                                                onclick="return confirm('Delete this option?');">
                                                <input type="hidden" name="action" value="delete_option">
                                                <input type="hidden" name="option_id" value="<?= $opt['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-link text-danger p-0"
                                                    style="font-size: 0.8rem;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>