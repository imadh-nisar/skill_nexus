<?php
include __DIR__ . '/../config.php';

$session_id = $_GET['session'] ?? null;

if (!$session_id) {
    header('Location: ' . BASE_URL . '/career/test.php');
    exit;
}

// Calculate career scores based on database mappings
function getCareerRecommendations($pdo, $answers, $limit = 5)
{
    // Build a scoring system based on answer patterns
    $careers = $pdo->query("SELECT * FROM careers WHERE is_active = TRUE")->fetchAll();
    $scores = [];

    foreach ($careers as $career) {
        $score = 0;
        $weight = 0;

        // Get all scoring rules for this career
        $stmt = $pdo->prepare("
            SELECT * FROM career_scoring 
            WHERE career_id = :career_id
        ");
        $stmt->execute([':career_id' => $career['id']]);
        $rules = $stmt->fetchAll();

        foreach ($rules as $rule) {
            if (isset($answers[$rule['question_id']]) && $answers[$rule['question_id']] == $rule['selected_option_value']) {
                $score += $rule['score_weight'];
            }
            $weight += $rule['score_weight'];
        }

        // Calculate normalized score
        $normalized_score = $weight > 0 ? ($score / $weight) * 100 : 0;

        if ($normalized_score > 0) {
            $scores[$career['id']] = [
                'career' => $career,
                'score' => $normalized_score,
                'raw_score' => $score,
                'weight' => $weight
            ];
        }
    }

    // Sort by score descending
    usort($scores, function ($a, $b) {
        return $b['score'] <=> $a['score'];
    });

    return array_slice($scores, 0, $limit);
}

// Get test result
$stmt = $pdo->prepare("SELECT * FROM test_results WHERE session_id = :session_id");
$stmt->execute([':session_id' => $session_id]);
$result = $stmt->fetch();

$answers = [];
if ($result && $result['career_recommendations']) {
    $answers = json_decode($result['career_recommendations'], true);
}

// Get recommendations
$recommendations = getCareerRecommendations($pdo, $answers);

// Get related degrees for recommended careers
function getCareerDegrees($pdo, $career_id)
{
    $stmt = $pdo->prepare("
        SELECT d.* FROM degrees d
        JOIN career_degrees cd ON d.id = cd.degree_id
        WHERE cd.career_id = :career_id AND d.is_active = TRUE
    ");
    $stmt->execute([':career_id' => $career_id]);
    return $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Assessment Results - Skill NEXUS</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            padding: 40px 0;
        }

        .results-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .header-section {
            text-align: center;
            color: white;
            margin-bottom: 50px;
            animation: fadeInDown 0.8s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        .header-section h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .header-section p {
            font-size: 1.2rem;
            opacity: 0.95;
        }

        .results-grid {
            display: grid;
            gap: 30px;
            margin-bottom: 50px;
        }

        .career-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
            animation: fadeInUp 0.8s ease-out;
            animation-fill-mode: both;
        }

        .career-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .career-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .career-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .career-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .career-card:nth-child(5) {
            animation-delay: 0.5s;
        }

        .career-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        }

        .career-rank {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 800;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .career-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            padding: 30px;
            position: relative;
            border-bottom: 2px solid #e9ecef;
        }

        .career-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .match-score {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .match-bar {
            flex-grow: 1;
            height: 8px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }

        .match-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
            border-radius: 10px;
            transition: width 1.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            width: 0;
        }

        .match-percentage {
            font-weight: 700;
            color: #667eea;
            min-width: 50px;
            text-align: right;
        }

        .career-body {
            padding: 30px;
        }

        .career-description {
            color: #666;
            line-height: 1.8;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .career-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .detail-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
        }

        .detail-label {
            font-size: 0.8rem;
            color: #999;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 1rem;
            color: #333;
            font-weight: 700;
        }

        .related-degrees {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e9ecef;
        }

        .related-degrees h5 {
            font-size: 0.9rem;
            color: #999;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        .degree-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .degree-tag {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
            color: #667eea;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1px solid rgba(102, 126, 234, 0.3);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-explore {
            flex: 1;
            padding: 12px 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-explore:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .empty-state {
            background: white;
            border-radius: 20px;
            padding: 60px 30px;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 20px;
        }

        .cta-section {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            margin-top: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 1s ease-out;
        }

        .cta-section h2 {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 20px;
        }

        .cta-section p {
            color: #666;
            margin-bottom: 30px;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
        }

        .btn-primary:hover {
            color: white;
        }

        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 1.8rem;
            }

            .career-title {
                font-size: 1.3rem;
            }

            .career-details {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>
    <?php renderNav(); ?>

    <div class="results-container px-3">
        <!-- Header -->
        <div class="header-section">
            <h1>🎯 Your Career Matches</h1>
            <p>Based on your assessment, here are your top career recommendations</p>
        </div>

        <!-- Results -->
        <?php if (!empty($recommendations)): ?>
            <div class="results-grid">
                <?php foreach ($recommendations as $rank => $item):
                    $career = $item['career'];
                    $score = round($item['score']);
                    $degrees = getCareerDegrees($pdo, $career['id']);
                    ?>
                    <div class="career-card">
                        <div class="career-header">
                            <div class="career-rank"><?= $rank + 1 ?></div>
                            <h2 class="career-title"><?= e($career['name']) ?></h2>
                            <div class="match-score">
                                <span style="font-size: 0.9rem; color: #999;">Match Score</span>
                                <div class="match-bar">
                                    <div class="match-bar-fill" style="width: 0;" onload="this.style.width = '<?= $score ?>%'">
                                    </div>
                                </div>
                                <div class="match-percentage"><?= $score ?>%</div>
                            </div>
                        </div>

                        <div class="career-body">
                            <p class="career-description"><?= e($career['description']) ?></p>

                            <div class="career-details">
                                <?php if ($career['average_salary']): ?>
                                    <div class="detail-item">
                                        <div class="detail-label">💰 Salary</div>
                                        <div class="detail-value"><?= e($career['average_salary']) ?></div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($career['job_outlook']): ?>
                                    <div class="detail-item">
                                        <div class="detail-label">📈 Outlook</div>
                                        <div class="detail-value"><?= e($career['job_outlook']) ?></div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($degrees)): ?>
                                <div class="related-degrees">
                                    <h5>Recommended Degrees</h5>
                                    <div class="degree-tags">
                                        <?php foreach ($degrees as $degree): ?>
                                            <span class="degree-tag"><?= e($degree['name']) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="action-buttons">
                                <a href="#" class="btn-explore">📖 Learn More</a>
                                <a href="<?= BASE_URL ?>/career/test.php" class="btn-explore"
                                    style="background: white; color: #667eea; border: 2px solid #667eea;">🔄 Retake Test</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- CTA -->
            <div class="cta-section">
                <h2>Explore More Opportunities</h2>
                <p>Browse our complete database of careers and educational programs to find your perfect fit.</p>
                <a href="<?= BASE_URL ?>/results/careers.php" class="btn btn-primary btn-lg"><i
                        class="fas fa-briefcase"></i> Browse All Careers</a>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon"><i class="fas fa-inbox"></i></div>
                <h3>No Results Found</h3>
                <p>Please take the career assessment to see personalized recommendations.</p>
                <a href="<?= BASE_URL ?>/career/test.php" class="btn btn-primary btn-lg">Start Assessment</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animate progress bars on page load
        window.addEventListener('load', () => {
            document.querySelectorAll('.match-bar-fill').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 100);
            });
        });
    </script>
</body>

</html>