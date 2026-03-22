<?php
session_start();
require_once __DIR__ . '/../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE email = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$email]);

  if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (password_verify($password, $row['password'])) {
      session_regenerate_id(true); // prevent fixation
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['user_name'] = $row['name'];
      $_SESSION['user_email'] = $row['email'];

      // Redirect back to the originally requested page when possible
      $redirect = $_SESSION['redirect_url'] ?? (BASE_URL . '/index_.php');
      unset($_SESSION['redirect_url']);

      header("Location: " . $redirect);
      exit();
    } else {
      $error = "Invalid password.";
    }
  } else {
    $error = "No user found.";
  }
}

// Capture redirect if passed in query string
if (isset($_GET['redirect'])) {
  $_SESSION['redirect_url'] = $_GET['redirect'];
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
  <?php renderNav(); ?>
  <div class="container mt-5">
    <div class="card p-4 shadow">
      <h2 class="mb-3">Login</h2>
      <?php if (!empty($error))
        echo "<div class='alert alert-danger'>$error</div>"; ?>
      <form method="POST">
        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Login</button>
      </form>
    </div>
  </div>
</body>

</html>