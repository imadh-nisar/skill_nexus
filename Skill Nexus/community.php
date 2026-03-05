<?php
include __DIR__ . '/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Community - Career Guidance</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff;
            /* light blue background */
        }

        .btn-blue {
            background-color: #1976d2;
            color: white;
        }

        .btn-blue:hover {
            background-color: #0d47a1;
            color: white;
        }

        footer {
            background-color: #0d47a1;
        }

        .card {
            border: none;
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>

<body>

    <!-- Your Navbar goes here -->
    <!-- Paste your navbar code above this line -->

    <!-- Hero Section -->
    <header class="text-center py-5 bg-primary text-white" style="margin-top: 50px;">
        <div class="container">
            <h1 class="display-4 fw-bold">Join the Career Community</h1>
            <p class="lead">Connect with peers, share experiences, and grow together.</p>
            <a href="#guidance" class="btn btn-blue btn-lg">Get Started</a>
        </div>
    </header>

    <!-- Guidance Section -->
    <section id="guidance" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4 text-primary">How to Connect</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Introduce Yourself</h5>
                            <p class="card-text">Start by sharing your name, degree, or career interest. A friendly
                                intro builds trust.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Ask Questions</h5>
                            <p class="card-text">Show curiosity about others’ journeys. Questions spark meaningful
                                conversations.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Offer Support</h5>
                            <p class="card-text">Encourage and motivate peers. A supportive tone makes everyone feel
                                welcome.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Ready-Made Messages -->
    <section id="messages" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4 text-primary">Ready-Made Messages</h2>
            <p class="text-center">Click a message to copy and start your conversation easily.</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <button class="btn btn-blue"
                    onclick="copyMessage('Hi! I’m excited to connect with others in my field.')">👋 Hi! I’m excited to
                    connect</button>
                <button class="btn btn-blue" onclick="copyMessage('Hello! What degree or career are you pursuing?')">❓
                    What career are you pursuing?</button>
                <button class="btn btn-blue"
                    onclick="copyMessage('Nice to meet you! I’d love to hear about your journey.')">🤝 Nice to meet
                    you!</button>
                <button class="btn btn-blue"
                    onclick="copyMessage('Hey! Do you have any tips for someone starting out?')">💡 Any tips for
                    beginners?</button>
                <button class="btn btn-blue"
                    onclick="copyMessage('Let’s share resources and help each other succeed!')">📚 Let’s share
                    resources</button>
            </div>
        </div>
    </section>

    <!-- Members Showcase -->
    <section id="members" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4 text-primary">Community Members</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h5 class="card-title">Aisha</h5>
                            <p class="card-text">UI/UX Designer, loves creative collaboration.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h5 class="card-title">James</h5>
                            <p class="card-text">Data Analyst, passionate about visualization.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h5 class="card-title">Sarah</h5>
                            <p class="card-text">Software Engineer, enjoys mentoring juniors.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h5 class="card-title">Imadh</h5>
                            <p class="card-text">Full-stack Developer, exploring career guidance systems.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-white text-center py-3">
        <p class="mb-0">&copy; 2026 Career Guidance Community. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyMessage(text) {
            navigator.clipboard.writeText(text);
            alert("Message copied: " + text);
        }
    </script>
</body>

</html>