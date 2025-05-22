<?php
include('includes/db.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
  $stmt->bind_param("ss", $username, $password);
  $stmt->execute();

  header("Location: login.php");
  exit;
}
?>

<form method="post">
  <h2>Rejestracja</h2>
  <input name="username" required><br>
  <input type="password" name="password" required><br>
  <button type="submit">Zarejestruj</button>
</form>
