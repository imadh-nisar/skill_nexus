<?php
include __DIR__ . '/../config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Top Careers - Career Guidance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9fff0;
        }

        .card:hover {
            transform: translateY(-5px);
            transition: 0.3s;
        }

        .btn-career {
            background-color: #4caf50;
            color: white;
        }

        .btn-career:hover {
            background-color: #2e7d32;
        }
    </style>
</head>

<body>

    <!-- Your Navbar goes here -->

    <header class="text-center py-5 bg-success text-white" style="margin-top: 50px;">
        <div class="container">
            <h1 class="fw-bold">Top Careers</h1>
            <p class="lead">Explore the most liked and most challenging careers with salary insights.</p>
        </div>
    </header>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Career Card 1 -->
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-success">Software Developer</h5>
                            <p class="card-text">💚 Most Liked<br>Average Salary: $80,000<br>Growth Rate: 22%</p>
                            <a href="#" class="btn btn-career">Learn More</a>
                        </div>
                    </div>
                </div>
                <!-- Career Card 2 -->
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-success">Data Analyst</h5>
                            <p class="card-text">💚 Popular<br>Average Salary: $70,000<br>Growth Rate: 25%</p>
                            <a href="#" class="btn btn-career">Learn More</a>
                        </div>
                    </div>
                </div>
                <!-- Career Card 3 -->
                <div class="col-md-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title text-success">Customer Support</h5>
                            <p class="card-text">💔 Most Hated<br>Average Salary: $35,000<br>High Stress Levels</p>
                            <a href="#" class="btn btn-career">Learn More</a>
                        </div>
                    </div>
                </div>
                <!-- Add more rows of 3 cards as needed -->
            </div>
        </div>
    </section>

    <footer class="bg-success text-white text-center py-3">
        <p>&copy; 2026 Career Guidance. All rights reserved.</p>
    </footer>

</body>

</html>