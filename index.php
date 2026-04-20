<?php
include __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill NEXUS - Find Your Perfect Career Path</title>
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
            overflow-x: hidden;
        }

        /* ===== ANIMATIONS ===== */
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

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes pulse-glow {

            0%,
            100% {
                box-shadow: 0 0 20px rgba(102, 126, 234, 0.4);
            }

            50% {
                box-shadow: 0 0 40px rgba(102, 126, 234, 0.8);
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.95) 100%);
            padding: 15px 0;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.8rem !important;
            font-weight: 800;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }

        .btn-admin {
            background: white;
            color: #667eea !important;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* ===== HERO SECTION ===== */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            color: white;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%), radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 900px;
            padding: 40px 20px;
            animation: fadeInUp 0.8s ease-out;
        }

        .hero h1 {
            font-size: clamp(2rem, 5vw, 4rem);
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
            animation: slideInLeft 0.8s ease-out;
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 40px;
            opacity: 0.9;
            animation: slideInRight 0.8s ease-out;
        }

        .btn-cta {
            background: white;
            color: #667eea;
            font-weight: 700;
            padding: 15px 40px;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.1rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .btn-cta-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
            margin-left: 15px;
        }

        .btn-cta-secondary:hover {
            background: white;
            color: #667eea;
        }

        /* ===== FEATURES SECTION ===== */
        .features {
            padding: 80px 20px;
            background: #f8f9fa;
        }

        .features .container {
            max-width: 1300px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 60px;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            animation: scaleIn 0.6s ease-out;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #333;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        /* ===== CAREERS SECTION ===== */
        .careers-section {
            padding: 80px 20px;
            background: white;
        }

        .careers-section .container {
            max-width: 1300px;
            margin: 0 auto;
        }

        .career-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .career-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease-out;
            border: 1px solid #f0f0f0;
        }

        .career-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .career-card-header {
            padding: 30px 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .career-card-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
        }

        .career-salary {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 10px;
        }

        .career-card-body {
            padding: 20px;
        }

        .career-description {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .degree-link {
            display: inline-block;
            background: #f0f0f0;
            color: #667eea;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .degree-link:hover {
            background: #667eea;
            color: white;
        }

        /* ===== DEGREES SECTION ===== */
        .degrees-section {
            padding: 80px 20px;
            background: linear-gradient(135deg, #667eea14, #764ba214);
        }

        .degrees-section .container {
            max-width: 1300px;
            margin: 0 auto;
        }

        .degree-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
        }

        .degree-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            animation: scaleIn 0.6s ease-out;
            cursor: pointer;
        }

        .degree-card:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .degree-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .degree-name {
            font-weight: 700;
            color: #333;
            font-size: 1.1rem;
        }

        .degree-duration {
            color: #999;
            font-size: 0.85rem;
            margin-top: 8px;
        }

        /* ===== CTA SECTION ===== */
        .cta-section {
            padding: 80px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 30px;
        }

        .cta-description {
            font-size: 1.2rem;
            margin-bottom: 40px;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-cta-large {
            background: white;
            color: #667eea;
            font-weight: 700;
            padding: 18px 50px;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-cta-large:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        /* ===== FOOTER ===== */
        footer {
            background: #1a1a2e;
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        footer p {
            margin: 0;
            opacity: 0.8;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .btn-cta {
                padding: 12px 30px;
                font-size: 1rem;
            }

            .btn-cta-secondary {
                margin-left: 0;
                margin-top: 10px;
                display: block;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }

            .career-grid {
                grid-template-columns: 1fr;
            }

            .degree-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        position: absolute;
        top: -50%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 800px;
            animation: fadeInUp 1s ease-out;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.95;
            line-height: 1.8;
        }

        .hero .btn {
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-start {
            background: white;
            color: #667eea;
            animation: pulse-glow 2s ease-in-out infinite;
        }

        .btn-start:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            background: white;
            color: #667eea;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: #667eea;
        }

        /* ===== FEATURES SECTION ===== */
        .section-features {
            padding: 80px 0;
            background: white;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 50px;
            text-align: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            animation: fadeInUp 0.8s ease-out;
            animation-fill-mode: both;
        }

        .feature-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .feature-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .feature-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .feature-card:nth-child(4) {
            animation-delay: 0.4s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #333;
        }

        .feature-card p {
            color: #666;
            line-height: 1.8;
        }

        /* ===== CTA SECTION ===== */
        .section-cta {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 80px 0;
            color: white;
            text-align: center;
        }

        .cta-content h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 20px;
        }

        .cta-content p {
            font-size: 1.2rem;
            margin-bottom: 40px;
            opacity: 0.95;
        }

        /* ===== CAREERS PREVIEW ===== */
        .section-careers {
            padding: 80px 0;
            background: white;
        }

        .career-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
            animation: slideInLeft 0.6s ease-out;
        }

        .career-card:hover {
            transform: translateX(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .career-card h4 {
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .career-card p {
            color: #666;
            margin: 0;
            font-size: 0.95rem;
        }

        /* ===== FOOTER ===== */
        footer {
            background: linear-gradient(135deg, #2d3561 0%, #1a1f3a 100%);
            color: white;
            padding: 50px 0 20px;
        }

        footer h5 {
            margin-bottom: 20px;
            font-weight: 700;
        }

        footer a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s;
        }

        footer a:hover {
            color: white;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            margin-top: 30px;
            text-align: center;
            opacity: 0.8;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .hero {
                min-height: auto;
                padding: 60px 0;
            }

            .feature-icon {
                font-size: 2rem;
            }
        }
    </style>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles.css">
</head>

<body>
    <!-- Navigation -->
    <?php renderNav(); ?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>🚀 Discover Your Perfect Career Path</h1>
            <p>Take our intelligent assessment and unlock personalized career recommendations tailored to your unique
                strengths and interests</p>
            <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-top: 40px;">
                <a href="<?= BASE_URL ?>/career/career_test.php" class="btn-cta"><i class="fas fa-play me-2"></i>Start
                    Assessment</a>
                <button class="btn-cta btn-cta-secondary"
                    onclick="document.getElementById('features').scrollIntoView({behavior:'smooth'})"><i
                        class="fas fa-arrow-down me-2"></i>Learn More</button>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <h2 class="section-title">Why Choose Skill NEXUS?</h2>
            <div class="feature-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-brain"></i></div>
                    <h3>AI-Powered Matching</h3>
                    <p>Intelligent algorithm analyzes your responses and matches you with 100+ careers and educational
                        paths</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-clock"></i></div>
                    <h3>Quick & Effortless</h3>
                    <p>Complete our comprehensive 20-question assessment in just 10 minutes</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-lock"></i></div>
                    <h3>100% Free & Private</h3>
                    <p>No registration, no ads, no selling your data. Completely anonymous and free forever</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>Detailed Insights</h3>
                    <p>Get comprehensive career overviews, salary information, and educational requirements</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Careers Section -->
    <section class="careers-section">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 50px;">
                <h2 class="section-title" style="margin-bottom: 0;">Popular Career Paths</h2>
                <a href="<?= BASE_URL ?>/results/careers.php" class="btn-cta">View All →</a>
            </div>
            <div class="career-grid">
                <?php
                try {
                    $careers = getCareers($pdo, 8);
                    foreach ($careers as $career):
                        $degrees = getCareerDegrees($pdo, $career['id']);
                        ?>
                        <div class="career-card">
                            <div class="career-card-header">
                                <div class="career-card-title"><i class="fas fa-briefcase me-2"></i><?= e($career['name']) ?>
                                </div>
                                <div class="career-salary"><?= e($career['average_salary'] ?? 'Competitive Salary') ?></div>
                            </div>
                            <div class="career-card-body">
                                <p class="career-description"><?= substr(e($career['description'] ?? ''), 0, 100) ?>...</p>
                                <div style="margin-bottom: 15px;">
                                    <?php foreach (array_slice($degrees, 0, 2) as $degree): ?>
                                        <span class="degree-link"><?= e($degree['name']) ?></span>
                                    <?php endforeach; ?>
                                    <?php if (count($degrees) > 2): ?>
                                        <span class="degree-link">+<?= count($degrees) - 2 ?> more</span>
                                    <?php endif; ?>
                                </div>
                                <p style="color: #999; font-size: 0.85rem; margin: 0;"><i
                                        class="fas fa-arrow-right me-1"></i><?= e($career['job_outlook'] ?? 'Growing field') ?>
                                </p>
                            </div>
                        </div>
                        <?php
                    endforeach;
                } catch (Exception $e) {
                    echo '<p>Unable to load careers</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Degrees Section -->
    <section class="degrees-section">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 50px;">
                <h2 class="section-title" style="margin-bottom: 0;">Educational Paths</h2>
                <a href="<?= BASE_URL ?>/results/degrees.php" class="btn-cta">View All →</a>
            </div>
            <div class="degree-grid">
                <?php
                try {
                    $degrees = getDegrees($pdo);
                    foreach (array_slice($degrees, 0, 12) as $degree):
                        ?>
                        <div class="degree-card">
                            <div class="degree-icon"><i class="fas fa-graduation-cap"></i></div>
                            <div class="degree-name"><?= e($degree['name']) ?></div>
                            <div class="degree-duration"><?= e($degree['duration'] ?? '4 years') ?></div>
                        </div>
                        <?php
                    endforeach;
                } catch (Exception $e) {
                    echo '<p>Unable to load degrees</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section style="padding: 80px 20px; background: white;">
        <div class="container" style="max-width: 1300px; margin: 0 auto;">
            <h2 class="section-title">How It Works</h2>
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 40px; margin-top: 60px;">
                <div style="text-align: center;">
                    <div
                        style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800; color: white; margin: 0 auto 20px;">
                        1</div>
                    <h3 style="font-weight: 700; color: #333; margin-bottom: 15px;">Answer Questions</h3>
                    <p style="color: #666; line-height: 1.6;">Respond to 20 thoughtful questions about your interests,
                        skills, work style, and career goals.</p>
                </div>
                <div style="text-align: center;">
                    <div
                        style="width: 80px; height: 80px; background: linear-gradient(135deg, #764ba2, #f093fb); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800; color: white; margin: 0 auto 20px;">
                        2</div>
                    <h3 style="font-weight: 700; color: #333; margin-bottom: 15px;">AI Analysis</h3>
                    <p style="color: #666; line-height: 1.6;">Our advanced algorithm analyzes your responses and matches
                        you with careers and degrees.</p>
                </div>
                <div style="text-align: center;">
                    <div
                        style="width: 80px; height: 80px; background: linear-gradient(135deg, #f093fb, #FF6B6B); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800; color: white; margin: 0 auto 20px;">
                        3</div>
                    <h3 style="font-weight: 700; color: #333; margin-bottom: 15px;">Get Results</h3>
                    <p style="color: #666; line-height: 1.6;">Receive personalized recommendations with detailed
                        insights and next steps for your career.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section style="padding: 80px 20px; background: linear-gradient(135deg, #667eea14, #764ba214);">
        <div class="container" style="max-width: 1300px; margin: 0 auto;">
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 40px; text-align: center;">
                <div>
                    <div
                        style="font-size: 3rem; font-weight: 800; background: linear-gradient(135deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        100+</div>
                    <p style="color: #666; font-weight: 600;">Career Paths</p>
                </div>
                <div>
                    <div
                        style="font-size: 3rem; font-weight: 800; background: linear-gradient(135deg, #764ba2, #f093fb); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        50+</div>
                    <p style="color: #666; font-weight: 600;">Degree Programs</p>
                </div>
                <div>
                    <div
                        style="font-size: 3rem; font-weight: 800; background: linear-gradient(135deg, #f093fb, #FF6B6B); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        20</div>
                    <p style="color: #666; font-weight: 600;">Assessment Questions</p>
                </div>
                <div>
                    <div
                        style="font-size: 3rem; font-weight: 800; background: linear-gradient(135deg, #FF6B6B, #4ECDC4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        100%</div>
                    <p style="color: #666; font-weight: 600;">Free & Anonymous</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div style="max-width: 800px; margin: 0 auto;">
            <h2 class="cta-title">Ready to Discover Your Dream Career?</h2>
            <p class="cta-description">Start your journey today and get personalized career recommendations in just 10
                minutes. No registration, no ads, completely free.</p>
            <a href="<?= BASE_URL ?>/career/career_test.php" class="btn-cta-large"><i class="fas fa-play me-2"></i>Begin
                Assessment Now</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container" style="max-width: 1300px; margin: 0 auto;">
            <div
                style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; margin-bottom: 40px;">
                <div>
                    <h5 style="font-size: 1.2rem; font-weight: 800; margin-bottom: 15px;">🚀 Skill NEXUS</h5>
                    <p style="opacity: 0.8; line-height: 1.6;">Empowering careers through intelligent guidance, one
                        assessment at a time.</p>
                </div>
                <div>
                    <h5 style="font-weight: 700; margin-bottom: 15px;">Navigation</h5>
                    <ul style="list-style: none; padding: 0;">
                        <li><a href="<?= BASE_URL ?>/#features"
                                style="color: rgba(255,255,255,0.8); text-decoration: none; transition: color 0.3s;">Features</a>
                        </li>
                        <li><a href="<?= BASE_URL ?>/career/career_test.php"
                                style="color: rgba(255,255,255,0.8); text-decoration: none; transition: color 0.3s;">Career
                                Test</a></li>
                        <li><a href="<?= BASE_URL ?>/results/careers.php"
                                style="color: rgba(255,255,255,0.8); text-decoration: none; transition: color 0.3s;">Careers</a>
                        </li>
                        <li><a href="<?= BASE_URL ?>/results/degrees.php"
                                style="color: rgba(255,255,255,0.8); text-decoration: none; transition: color 0.3s;">Degrees</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h5 style="font-weight: 700; margin-bottom: 15px;">Resources</h5>
                    <ul style="list-style: none; padding: 0;">
                        <li><a href="#"
                                style="color: rgba(255,255,255,0.8); text-decoration: none; transition: color 0.3s;">Blog</a>
                        </li>
                        <li><a href="#"
                                style="color: rgba(255,255,255,0.8); text-decoration: none; transition: color 0.3s;">Community</a>
                        </li>
                        <li><a href="#"
                                style="color: rgba(255,255,255,0.8); text-decoration: none; transition: color 0.3s;">FAQ</a>
                        </li>
                        <li><a href="#"
                                style="color: rgba(255,255,255,0.8); text-decoration: none; transition: color 0.3s;">Support</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h5 style="font-weight: 700; margin-bottom: 15px;">Connect With Us</h5>
                    <div style="font-size: 1.5rem; display: flex; gap: 15px;">
                        <a href="#" style="color: white;"><i class="fab fa-facebook"></i></a>
                        <a href="#" style="color: white;"><i class="fab fa-twitter"></i></a>
                        <a href="#" style="color: white;"><i class="fab fa-linkedin"></i></a>
                        <a href="#" style="color: white;"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div
                style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; text-align: center; opacity: 0.8;">
                <p>&copy; 2026 Skill NEXUS. All rights reserved. | <a href="#" style="color: white;">Privacy Policy</a>
                    | <a href="#" style="color: white;">Terms of Service</a></p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', function (e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Scroll animations
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.8s ease-out forwards';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.feature-card, .career-card, .degree-card').forEach(el => observer.observe(el));
    </script>
</body>

</html>