<?php
require_once __DIR__ . '/../config.php'; // DB connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  try {
    // Prevent duplicate email registrations
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
      $error = "An account with that email already exists.";
    } else {
      $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
      $stmt = $pdo->prepare($sql);

      $stmt->bindParam(':name', $name, PDO::PARAM_STR);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->bindParam(':password', $password, PDO::PARAM_STR);

      if ($stmt->execute()) {
        header("Location: login.php?success=registered");
        exit();
      } else {
        $error = "Error: Could not create account.";
      }
    }
  } catch (PDOException $e) {
    // In production, do not expose details
    $error = "Database Error: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Register</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles.css">
</head>

<body>
  <?php renderNav(); ?>
  <div class="container mt-5">
    <div class="card p-4 shadow">
      <h2 class="mb-3">Create Account</h2>
      <form method="POST">
        <div class="mb-3">
          <label>Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label>Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
      </form>
    </div>
  </div>
</body>

</html>