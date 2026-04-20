<?php
include __DIR__ . '/../config.php';

$page = $_GET['page'] ?? 1;
$limit = 12;
$offset = ($page - 1) * $limit;

$stmt = $pdo->prepare("
    SELECT * FROM degrees WHERE is_active = TRUE 
    ORDER BY name ASC 
    LIMIT :limit OFFSET :offset
");
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$degrees = $stmt->fetchAll();

$total = $pdo->query("SELECT COUNT(*) as count FROM degrees WHERE is_active = TRUE")->fetch()['count'];
$pages = ceil($total / $limit);


// --- Suggestion function ---
function getDegreeSuggestions(PDO $pdo, int $degree_id): array
{
    $stmt = $pdo->prepare("
        SELECT c.* 
        FROM careers c
        JOIN career_degrees cd ON c.id = cd.career_id
        WHERE cd.degree_id = :degree_id AND c.is_active = 1
        ORDER BY c.name ASC
    ");
    $stmt->execute(['degree_id' => $degree_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Degrees - Skill NEXUS</title>
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

        .degree-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 40px;
            margin: 0 auto;
            max-width: 1400px;
        }

        .degree-card {
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

        .degree-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        .degree-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .degree-header-color {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            margin: 0 auto 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .degree-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
        }

        .degree-body {
            padding: 20px;
        }

        .degree-description {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 15px;
            min-height: 60px;
        }

        .degree-meta {
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

        .careers-preview {
            margin-bottom: 15px;
        }

        .careers-label {
            font-size: 0.75rem;
            color: #999;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .career-links {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .career-link {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            color: #667eea;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .btn-learn {
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

        .btn-learn:hover {
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
            .degree-grid {
                grid-template-columns: 1fr;
                padding: 20px;
            }

            .header-section h1 {
                font-size: 1.8rem;
            }
        }
    </style>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles.css">
</head>

<body>
    <?php renderNav(); ?>

    <div class="header-section">
        <h1>📚 Explore Degrees</h1>
        <p>Discover 50+ educational programs and find your learning path</p>
    </div>

    <?php if (!empty($degrees)): ?>
        <div class="degree-grid">
            <?php foreach ($degrees as $degree):
                $bgColor = isset($degree['color_code']) && $degree['color_code'] ? $degree['color_code'] : '#667eea';
                $careers = getDegreeSuggestions($pdo, $degree['id']); // fetch related careers
                ?>
                <div class="degree-card">
                    <div class="degree-header">
                        <div class="degree-header-color" style="background-color: <?= htmlspecialchars($bgColor) ?>;"></div>
                        <h3 class="degree-title"><?= e($degree['name']) ?></h3>
                    </div>
                    <div class="degree-body">
                        <p class="degree-description">
                            <?= e(substr($degree['description'] ?? '', 0, 120)) ?>
                            <?= strlen($degree['description'] ?? '') > 120 ? '...' : '' ?>
                        </p>

                        <div class="degree-meta">
                            <?php if ($degree['duration']): ?>
                                <div class="meta-item">
                                    <strong>⏱️ Duration:</strong> <?= e($degree['duration']) ?>
                                </div>
                            <?php endif; ?>
                            <div class="meta-item">
                                <strong>📊 Status:</strong>
                                <?= $degree['is_active'] ? '<span style="color: green;">Active</span>' : '<span style="color: red;">Inactive</span>' ?>
                            </div>
                        </div>

                        <?php if (!empty($careers)): ?>
                            <div class="careers-preview">
                                <div class="careers-label">Related Careers</div>
                                <div class="career-links">
                                    <?php foreach ($careers as $career): ?>
                                        <span class="career-link"><?= e($career['name']) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <a href="<?= BASE_URL ?>/career/test.php" class="btn-learn">
                            <i class="fas fa-pencil"></i> Take Assessment
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php renderFooter(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>