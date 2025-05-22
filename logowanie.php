<?php
include('includes/db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($user = $result->fetch_assoc()) {
    if (password_verify($password, $user['password'])) {
      $_SESSION['username'] = $user['username'];
      $_SESSION['is_admin'] = $user['is_admin'];
      header("Location: index.php");
      exit;
    }
  }

  $error = "Nieprawidłowe dane logowania.";
}
?>

<form method="post">
  <h2>Logowanie</h2>
  <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
  <input type="text" name="username" placeholder="Login" required><br>
  <input type="password" name="password" placeholder="Hasło" required><br>
  <button type="submit">Zaloguj się</button>
</form>
