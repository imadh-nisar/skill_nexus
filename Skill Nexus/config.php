<?php
// Configuration - Modern Career Guidance Platform
// Efficient, scalable, and secure configuration

// Start session early
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base file system path for includes / requires
define('BASE_PATH', __DIR__);

// Base URL used for generating links; the folder name includes a space, so use URL encoding.
define('BASE_URL', '/skill_nexus/Skill%20Nexus');

// Database connection
try {
    $dsn = "mysql:host=localhost;dbname=career_guidance;charset=utf8mb4";
    $username = "root";
    $password = "";

    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // In production, avoid exposing implementation details.
    die("Database connection failed: " . $e->getMessage());
}

// Utility helpers
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

// Check if user is logged in to admin panel
function isAdminLoggedIn(): bool
{
    return !empty($_SESSION['admin_id']);
}

// Require admin login
// Require admin login
function requireAdminLogin(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['admin_id'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'] ?? '/';
        header('Location: ' . BASE_URL . '/admin/login.php');
        exit;
    }
}

// Render modern navigation bar
function renderNav(): void
{
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/index.php" style="font-size: 1.8rem;">🚀 Skill NEXUS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Explore</a>
                        <ul class="dropdown-menu dropdown-menu-dark">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/career/career_test.php">Career Test</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/results/careers.php">Careers</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>/results/degrees.php">Degrees</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/community.php">Community</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/partners%20&%20blog/blog.php">Blog</a>
                    </li>
                    <li class="nav-item"><a class="nav-link"
                            href="<?= BASE_URL ?>/partners%20&%20blog/partners.php">Partners</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/login.php"
                            class="btn btn-light btn-sm ms-2">Admin</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <?php
}

// Get all active careers with pagination
function getCareers($pdo, $limit = 10, $offset = 0)
{
    $stmt = $pdo->prepare("SELECT * FROM careers WHERE is_active = TRUE ORDER BY name ASC LIMIT :limit OFFSET :offset");
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get all active degrees
function getDegrees($pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM degrees WHERE is_active = TRUE ORDER BY name ASC");
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get all active questions
function getQuestions($pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE is_active = TRUE ORDER BY sequence ASC");
    $stmt->execute();
    return $stmt->fetchAll();
}

// Get question options
function getQuestionOptions($pdo, $question_id)
{
    $stmt = $pdo->prepare("SELECT * FROM question_options WHERE question_id = :id ORDER BY sequence ASC");
    $stmt->bindParam(':id', $question_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

// Calculate career scores based on test answers
function calculateCareerScores($pdo, $answers)
{
    $stmt = $pdo->prepare("
        SELECT c.*, SUM(cs.score_weight) as total_score
        FROM careers c
        LEFT JOIN career_scoring cs ON c.id = cs.career_id
        WHERE cs.question_id IN (" . implode(',', array_fill(0, count($answers), '?')) . ")
        AND cs.selected_option_value IN (" . implode(',', array_fill(0, count($answers), '?')) . ")
        AND c.is_active = TRUE
        GROUP BY c.id
        ORDER BY total_score DESC
        LIMIT 5
    ");

    $values = array_merge(array_keys($answers), array_values($answers));
    $stmt->execute($values);
    return $stmt->fetchAll();
}

// ============================================
// ADMIN CRUD FUNCTIONS
// ============================================

// ===== CAREERS CRUD =====
function getAllCareers($pdo, $limit = null, $offset = 0)
{
    $sql = "SELECT * FROM careers ORDER BY name ASC";
    if ($limit) {
        $sql .= " LIMIT :limit OFFSET :offset";
    }
    $stmt = $pdo->prepare($sql);
    if ($limit) {
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll();
}

function getCareerById($pdo, $career_id)
{
    $stmt = $pdo->prepare("SELECT * FROM careers WHERE id = :id");
    $stmt->execute([':id' => $career_id]);
    return $stmt->fetch();
}

function createCareer($pdo, $name, $description, $average_salary, $job_outlook, $icon = null, $color_code = null)
{
    $stmt = $pdo->prepare("
        INSERT INTO careers (name, description, average_salary, job_outlook, icon, color_code) 
        VALUES (:name, :description, :salary, :outlook, :icon, :color)
    ");
    $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':salary' => $average_salary,
        ':outlook' => $job_outlook,
        ':icon' => $icon,
        ':color' => $color_code
    ]);
    return $pdo->lastInsertId();
}

function updateCareer($pdo, $career_id, $name, $description, $average_salary, $job_outlook, $icon = null, $color_code = null, $is_active = true)
{
    $stmt = $pdo->prepare("
        UPDATE careers 
        SET name = :name, description = :description, average_salary = :salary, 
            job_outlook = :outlook, icon = :icon, color_code = :color, is_active = :active
        WHERE id = :id
    ");
    $stmt->execute([
        ':id' => $career_id,
        ':name' => $name,
        ':description' => $description,
        ':salary' => $average_salary,
        ':outlook' => $job_outlook,
        ':icon' => $icon,
        ':color' => $color_code,
        ':active' => $is_active ? 1 : 0
    ]);
    return $stmt->rowCount();
}

function deleteCareer($pdo, $career_id)
{
    $stmt = $pdo->prepare("DELETE FROM careers WHERE id = :id");
    $stmt->execute([':id' => $career_id]);
    return $stmt->rowCount();
}

function toggleCareerActive($pdo, $career_id)
{
    $stmt = $pdo->prepare("UPDATE careers SET is_active = NOT is_active WHERE id = :id");
    $stmt->execute([':id' => $career_id]);
    return $stmt->rowCount();
}

// ===== DEGREES CRUD =====
function getAllDegrees($pdo, $limit = null, $offset = 0)
{
    $sql = "SELECT * FROM degrees ORDER BY name ASC";
    if ($limit) {
        $sql .= " LIMIT :limit OFFSET :offset";
    }
    $stmt = $pdo->prepare($sql);
    if ($limit) {
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll();
}

function getDegreeById($pdo, $degree_id)
{
    $stmt = $pdo->prepare("SELECT * FROM degrees WHERE id = :id");
    $stmt->execute([':id' => $degree_id]);
    return $stmt->fetch();
}

function createDegree($pdo, $name, $description, $duration = null, $icon = null, $color_code = null)
{
    $stmt = $pdo->prepare("
        INSERT INTO degrees (name, description, duration, icon, color_code) 
        VALUES (:name, :description, :duration, :icon, :color)
    ");
    $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':duration' => $duration,
        ':icon' => $icon,
        ':color' => $color_code
    ]);
    return $pdo->lastInsertId();
}

function updateDegree($pdo, $degree_id, $name, $description, $duration = null, $icon = null, $color_code = null, $is_active = true)
{
    $stmt = $pdo->prepare("
        UPDATE degrees 
        SET name = :name, description = :description, duration = :duration, 
            icon = :icon, color_code = :color, is_active = :active
        WHERE id = :id
    ");
    $stmt->execute([
        ':id' => $degree_id,
        ':name' => $name,
        ':description' => $description,
        ':duration' => $duration,
        ':icon' => $icon,
        ':color' => $color_code,
        ':active' => $is_active ? 1 : 0
    ]);
    return $stmt->rowCount();
}

function deleteDegree($pdo, $degree_id)
{
    $stmt = $pdo->prepare("DELETE FROM degrees WHERE id = :id");
    $stmt->execute([':id' => $degree_id]);
    return $stmt->rowCount();
}

function toggleDegreeActive($pdo, $degree_id)
{
    $stmt = $pdo->prepare("UPDATE degrees SET is_active = NOT is_active WHERE id = :id");
    $stmt->execute([':id' => $degree_id]);
    return $stmt->rowCount();
}

// ===== QUESTIONS CRUD =====
function getAllQuestions($pdo, $limit = null, $offset = 0)
{
    $sql = "SELECT * FROM questions ORDER BY sequence ASC";
    if ($limit) {
        $sql .= " LIMIT :limit OFFSET :offset";
    }
    $stmt = $pdo->prepare($sql);
    if ($limit) {
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    }
    $stmt->execute();
    return $stmt->fetchAll();
}

function getQuestionById($pdo, $question_id)
{
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = :id");
    $stmt->execute([':id' => $question_id]);
    return $stmt->fetch();
}

function createQuestion($pdo, $question_text, $category, $sequence)
{
    $stmt = $pdo->prepare("
        INSERT INTO questions (question_text, category, sequence, question_type) 
        VALUES (:text, :category, :sequence, 'multiple_choice')
    ");
    $stmt->execute([
        ':text' => $question_text,
        ':category' => $category,
        ':sequence' => $sequence
    ]);
    return $pdo->lastInsertId();
}

function updateQuestion($pdo, $question_id, $question_text, $category, $sequence, $is_active = true)
{
    $stmt = $pdo->prepare("
        UPDATE questions 
        SET question_text = :text, category = :category, sequence = :sequence, is_active = :active
        WHERE id = :id
    ");
    $stmt->execute([
        ':id' => $question_id,
        ':text' => $question_text,
        ':category' => $category,
        ':sequence' => $sequence,
        ':active' => $is_active ? 1 : 0
    ]);
    return $stmt->rowCount();
}

function deleteQuestion($pdo, $question_id)
{
    $stmt = $pdo->prepare("DELETE FROM questions WHERE id = :id");
    $stmt->execute([':id' => $question_id]);
    return $stmt->rowCount();
}

function toggleQuestionActive($pdo, $question_id)
{
    $stmt = $pdo->prepare("UPDATE questions SET is_active = NOT is_active WHERE id = :id");
    $stmt->execute([':id' => $question_id]);
    return $stmt->rowCount();
}

// ===== QUESTION OPTIONS CRUD =====
function getQuestionOptionsById($pdo, $question_id)
{
    $stmt = $pdo->prepare("SELECT * FROM question_options WHERE question_id = :id ORDER BY sequence ASC");
    $stmt->execute([':id' => $question_id]);
    return $stmt->fetchAll();
}

function createQuestionOption($pdo, $question_id, $option_text, $option_value, $sequence)
{
    $stmt = $pdo->prepare("
        INSERT INTO question_options (question_id, option_text, option_value, sequence) 
        VALUES (:question_id, :text, :value, :sequence)
    ");
    $stmt->execute([
        ':question_id' => $question_id,
        ':text' => $option_text,
        ':value' => $option_value,
        ':sequence' => $sequence
    ]);
    return $pdo->lastInsertId();
}

function updateQuestionOption($pdo, $option_id, $option_text, $option_value, $sequence)
{
    $stmt = $pdo->prepare("
        UPDATE question_options 
        SET option_text = :text, option_value = :value, sequence = :sequence
        WHERE id = :id
    ");
    $stmt->execute([
        ':id' => $option_id,
        ':text' => $option_text,
        ':value' => $option_value,
        ':sequence' => $sequence
    ]);
    return $stmt->rowCount();
}

function deleteQuestionOption($pdo, $option_id)
{
    $stmt = $pdo->prepare("DELETE FROM question_options WHERE id = :id");
    $stmt->execute([':id' => $option_id]);
    return $stmt->rowCount();
}

// ===== CAREER-DEGREE RELATIONSHIPS =====
function linkCareerToDegree($pdo, $career_id, $degree_id)
{
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO career_degrees (career_id, degree_id) 
        VALUES (:career_id, :degree_id)
    ");
    $stmt->execute([':career_id' => $career_id, ':degree_id' => $degree_id]);
    return $stmt->rowCount();
}

function unlinkCareerFromDegree($pdo, $career_id, $degree_id)
{
    $stmt = $pdo->prepare("
        DELETE FROM career_degrees 
        WHERE career_id = :career_id AND degree_id = :degree_id
    ");
    $stmt->execute([':career_id' => $career_id, ':degree_id' => $degree_id]);
    return $stmt->rowCount();
}

function getCareerDegrees($pdo, $career_id)
{
    $stmt = $pdo->prepare("
        SELECT d.* 
        FROM degrees d
        JOIN career_degrees cd ON d.id = cd.degree_id
        WHERE cd.career_id = :career_id 
          AND d.is_active = 1
        ORDER BY d.name ASC
    ");
    $stmt->execute([':career_id' => $career_id]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result ?: []; // always return an array
}


function getDegreeCareers($pdo, $degree_id)
{
    $stmt = $pdo->prepare("
        SELECT c.* FROM careers c
        JOIN career_degrees cd ON c.id = cd.career_id
        WHERE cd.degree_id = :degree_id AND c.is_active = TRUE
        ORDER BY c.name ASC
    ");
    $stmt->execute([':degree_id' => $degree_id]);
    return $stmt->fetchAll();
}

// ===== CAREER SCORING =====
function addCareerScore($pdo, $career_id, $question_id, $option_value, $weight = 1.0)
{
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO career_scoring (career_id, question_id, selected_option_value, score_weight) 
        VALUES (:career_id, :question_id, :option_value, :weight)
    ");
    $stmt->execute([
        ':career_id' => $career_id,
        ':question_id' => $question_id,
        ':option_value' => $option_value,
        ':weight' => $weight
    ]);
    return $pdo->lastInsertId();
}

function getCareerScores($pdo, $career_id)
{
    $stmt = $pdo->prepare("
        SELECT cs.*, q.question_text, qo.option_text
        FROM career_scoring cs
        JOIN questions q ON cs.question_id = q.id
        JOIN question_options qo ON cs.question_id = qo.question_id AND cs.selected_option_value = qo.option_value
        WHERE cs.career_id = :career_id
        ORDER BY q.sequence ASC
    ");
    $stmt->execute([':career_id' => $career_id]);
    return $stmt->fetchAll();
}

function countCareers($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM careers");
    return $stmt->fetch()['count'];
}

function countDegrees($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM degrees");
    return $stmt->fetch()['count'];
}

function countQuestions($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM questions");
    return $stmt->fetch()['count'];
}

function countTestResults($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM test_results");
    return $stmt->fetch()['count'];
}

