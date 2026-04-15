<?php
include __DIR__ . '/../config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Test Advice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Career Guide</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="test.php">Career Test</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Workshops</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <h1 class="mb-4 text-primary text-center fw-bold">Before You Take the Career Test</h1>

        <div class="row g-4">
            <!-- Card 1 -->
            <div class="col-md-6">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title text-success">🕒 Take Your Time</h5>
                        <p class="card-text">Don’t rush through the questions. Thoughtful answers lead to better
                            results.</p>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col-md-6">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title text-success">💡 Be Honest</h5>
                        <p class="card-text">Think about your true preferences, not what others expect from you.</p>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col-md-6">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title text-success">✍️ Choose Naturally</h5>
                        <p class="card-text">Pick the answer that feels most natural, even if it’s “maybe.”</p>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col-md-6">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title text-success">🔍 No Right or Wrong</h5>
                        <p class="card-text">This test is about insights into your personality and interests, not
                            grades.</p>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="col-md-12">
                <div class="card shadow-lg border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title text-success">📈 Accuracy Matters</h5>
                        <p class="card-text">The more thoughtful your answers, the more accurate your career guidance
                            will be.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center mt-5">
            <a href="test.php" class="btn btn-success btn-lg px-5 py-3 shadow">Start the Career Test</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container text-center">
            <p class="mb-1">© 2026 Career Guide. All Rights Reserved.</p>
            <p class="small">Helping you discover your path with confidence and clarity.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>