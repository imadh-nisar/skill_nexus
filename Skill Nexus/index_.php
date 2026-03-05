<?php
include __DIR__ . '/config.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Career Guidance</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0fff4;
            /* light green background */
        }

        .btn-green {
            background-color: #43a047;
            color: white;
        }

        .btn-green:hover {
            background-color: #2e7d32;
            color: white;
        }

        .card {
            border: none;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .table-success th {
            background-color: #2e7d32;
            color: white;
        }

        footer {
            background-color: #2e7d32;
        }
    </style>
</head>

<body>

    <!-- Your Navbar goes here -->
    <!-- Paste your navbar code above this line -->

    <!-- Hero Section -->
    <header class="text-center py-5 bg-success text-white" style="margin-top: 50px;">
        <div class="container">
            <h1 class="display-4 fw-bold">Shape Your Future With Us</h1>
            <p class="lead">Guidance, skills, and opportunities to achieve your dream career.</p>
            <a href="#services" class="btn btn-green btn-lg">Explore Services</a>
        </div>
    </header>

    <!-- Services Section -->
    <section id="services" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4 text-success">Our Services</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success">Career Counseling</h5>
                            <p class="card-text">Personalized sessions to discover your strengths and career paths.</p>
                            <a href="#" class="btn btn-green">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success">Skill Development</h5>
                            <p class="card-text">Workshops and resources to build essential professional skills.</p>
                            <a href="#" class="btn btn-green">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success">Job Placement</h5>
                            <p class="card-text">Connections and guidance to help you land your dream job.</p>
                            <a href="#" class="btn btn-green">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Careers Table -->
    <section id="careers" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4 text-success">Popular Career Paths</h2>
            <div class="table-responsive">
                <table class="table table-success table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Career</th>
                            <th>Required Skills</th>
                            <th>Average Salary</th>
                            <th>Growth Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Software Developer</td>
                            <td>Programming, Problem-Solving</td>
                            <td>$80,000</td>
                            <td>22%</td>
                        </tr>
                        <tr>
                            <td>Data Analyst</td>
                            <td>SQL, Statistics, Visualization</td>
                            <td>$70,000</td>
                            <td>25%</td>
                        </tr>
                        <tr>
                            <td>UI/UX Designer</td>
                            <td>Design Tools, Creativity</td>
                            <td>$65,000</td>
                            <td>18%</td>
                        </tr>
                        <tr>
                            <td>Project Manager</td>
                            <td>Leadership, Communication</td>
                            <td>$90,000</td>
                            <td>12%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Testimonials Carousel -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4 text-success">What Our Clients Say</h2>
            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner text-center">
                    <div class="carousel-item active">
                        <blockquote class="blockquote">
                            <p>"This guidance helped me land my first job!"</p>
                            <footer class="blockquote-footer">Sarah, Software Engineer</footer>
                        </blockquote>
                    </div>
                    <div class="carousel-item">
                        <blockquote class="blockquote">
                            <p>"The skill workshops boosted my confidence."</p>
                            <footer class="blockquote-footer">James, Data Analyst</footer>
                        </blockquote>
                    </div>
                    <div class="carousel-item">
                        <blockquote class="blockquote">
                            <p>"I finally found clarity in my career path."</p>
                            <footer class="blockquote-footer">Aisha, Designer</footer>
                        </blockquote>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4 text-success">Pricing Plans</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success">Basic</h5>
                            <p class="card-text">$49/month</p>
                            <ul class="list-unstyled">
                                <li>Career Counseling</li>
                                <li>Email Support</li>
                            </ul>
                            <a href="#" class="btn btn-green">Choose Plan</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success">Pro</h5>
                            <p class="card-text">$99/month</p>
                            <ul class="list-unstyled">
                                <li>Skill Development Workshops</li>
                                <li>Priority Support</li>
                            </ul>
                            <a href="#" class="btn btn-green">Choose Plan</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success">Premium</h5>
                            <p class="card-text">$149/month</p>
                            <ul class="list-unstyled">
                                <li>Job Placement Assistance</li>
                                <li>1-on-1 Mentorship</li>
                            </ul>
                            <a href="#" class="btn btn-green">Choose Plan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4 text-success">Contact Us</h2>
            <form class="mx-auto" style="max-width:600px;">
                <div class="mb-3">
                    <label for="name" class="form-label">Your Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter your name">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Your Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter your email">
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Message</label>
                    <textarea class="form-control" id="message" rows="4" placeholder="Write your message"></textarea>
                </div>
                <button type=" submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary text-white text-center py-3">
        <p class="mb-0">&copy; 2026 Career Guidance. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>