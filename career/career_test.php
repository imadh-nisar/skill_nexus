<?php
include __DIR__ . '/../config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Test - Pre-Test Tips - Skill NEXUS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
        }

        .tips-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 20px;
            text-align: center;
            margin-top: 20px;
            border-radius: 0 0 20px 20px;
        }

        .tips-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .tips-header p {
            font-size: 1.1rem;
            opacity: 0.95;
        }

        .tips-content {
            max-width: 1100px;
            margin: -40px auto 40px;
            padding: 0 20px;
        }

        .tip-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            margin-bottom: 25px;
            border-left: 5px solid #667eea;
        }

        .tip-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .tip-card .card-body {
            padding: 30px;
        }

        .tip-icon {
            font-size: 2rem;
            margin-bottom: 15px;
            display: inline-block;
        }

        .tip-card h5 {
            font-size: 1.3rem;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 10px;
        }

        .tip-card p {
            color: #666;
            line-height: 1.7;
            margin: 0;
        }

        .cta-section {
            text-align: center;
            margin: 50px 0;
        }

        .cta-section .btn {
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
            margin: 10px;
        }

        .cta-section .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .cta-section .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .cta-section .btn-secondary {
            background: white;
            color: #667eea;
            border: 2px solid #667eea;
        }

        .cta-section .btn-secondary:hover {
            background: #667eea;
            color: white;
        }

        footer {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
            margin-top: 60px;
        }

        footer p {
            margin: 5px 0;
        }

        @media (max-width: 768px) {
            .tips-header h1 {
                font-size: 1.8rem;
            }

            .tips-header {
                padding: 50px 20px;
            }

            .cta-section {
                display: flex;
                flex-direction: column;
            }

            .cta-section .btn {
                width: 100%;
            }
        }
    </style>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles.css">
</head>

<body>
    <?php renderNav(); ?>

    <div class="tips-header">
        <h1><i class="fas fa-lightbulb"></i> Before You Start</h1>
        <p>Tips to Help You Get the Most Out of Your Career Assessment</p>
    </div>

    <div class="tips-content">
        <div class="row">
            <!-- Tip 1 -->
            <div class="col-md-6">
                <div class="tip-card">
                    <div class="card-body">
                        <div class="tip-icon">🕒</div>
                        <h5>Take Your Time</h5>
                        <p>Don't rush through the questions. Thoughtful, honest answers lead to more accurate results
                            that truly reflect your interests and strengths.</p>
                    </div>
                </div>
            </div>

            <!-- Tip 2 -->
            <div class="col-md-6">
                <div class="tip-card">
                    <div class="card-body">
                        <div class="tip-icon">💡</div>
                        <h5>Be Honest</h5>
                        <p>Think about your true preferences and authentic self, not what others expect from you. This
                            assessment is for your benefit.</p>
                    </div>
                </div>
            </div>

            <!-- Tip 3 -->
            <div class="col-md-6">
                <div class="tip-card">
                    <div class="card-body">
                        <div class="tip-icon">✍️</div>
                        <h5>Choose Naturally</h5>
                        <p>Pick the answer that feels most natural and represents your genuine preferences, even if no
                            option is perfect.</p>
                    </div>
                </div>
            </div>

            <!-- Tip 4 -->
            <div class="col-md-6">
                <div class="tip-card">
                    <div class="card-body">
                        <div class="tip-icon">🔍</div>
                        <h5>No Right or Wrong</h5>
                        <p>This assessment provides insights into your personality and interests. It's not a test with
                            grades—there are no wrong answers.</p>
                    </div>
                </div>
            </div>

            <!-- Tip 5 -->
            <div class="col-md-12">
                <div class="tip-card">
                    <div class="card-body">
                        <div class="tip-icon">📈</div>
                        <h5>Accuracy Matters</h5>
                        <p>The more thoughtful and honest your answers, the more accurate your career guidance and
                            recommendations will be. This is your tool for discovering your ideal career path.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="cta-section">
            <a href="<?= BASE_URL ?>/career/test.php" class="btn btn-primary btn-lg">
                <i class="fas fa-play"></i> Start the Career Test
            </a>
            <a href="<?= BASE_URL ?>/index.php" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left"></i> Back Home
            </a>
        </div>
    </div>

    <?php renderFooter(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>