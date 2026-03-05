<?php
include __DIR__ . '/../config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Top Degrees - Career Guidance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
        }

        .card:hover {
            transform: translateY(-5px);
            transition: 0.3s;
        }

        .btn-degree {
            background-color: #1976d2;
            color: white;
        }

        .btn-degree:hover {
            background-color: #0d47a1;
        }
    </style>
</head>

<body>

    <!-- Your Navbar goes here -->

    <header class="text-center py-5 bg-primary text-white" style="margin-top: 50px;">
        <div class="container">
            <h1 class="fw-bold">Top Degrees</h1>
            <p class="lead">Compare tuition fees, duration, and popularity of degrees.</p>
        </div>
    </header>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Degree Card 1 -->
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Computer Science</h5>
                            <p class="card-text">💚 Most Liked<br>Duration: 4 years<br>Tuition Fee: $40,000</p>
                            <a href="#" class="btn btn-degree">Learn More</a>
                        </div>
                    </div>
                </div>
                <!-- Degree Card 2 -->
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Business Administration</h5>
                            <p class="card-text">💚 Popular<br>Duration: 3 years<br>Tuition Fee: $35,000</p>
                            <a href="#" class="btn btn-degree">Learn More</a>
                        </div>
                    </div>
                </div>
                <!-- Degree Card 3 -->
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Law</h5>
                            <p class="card-text">💔 Most Hated (long duration)<br>Duration: 5 years<br>Tuition Fee:
                                $60,000</p>
                            <a href="#" class="btn btn-degree">Learn More</a>
                        </div>
                    </div>
                </div>
                <!-- Add more rows of 3 cards as needed -->
            </div>
        </div>
    </section>

    <footer class="bg-primary text-white text-center py-3">
        <p>&copy; 2026 Career Guidance. All rights reserved.</p>
    </footer>

</body>

</html>