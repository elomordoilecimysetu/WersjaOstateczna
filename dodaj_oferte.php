<?php
include('includes/db.php');
session_start();
if (!$_SESSION['is_admin']) exit;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $tytul = $_POST['tytul'];
  $opis = $_POST['opis'];
  $cena = $_POST['cena'];

  $obrazek = $_FILES['obrazek']['name'];
  move_uploaded_file($_FILES['obrazek']['tmp_name'], "uploads/$obrazek");

  $stmt = $conn->prepare("INSERT INTO offers (title, description, price, image) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssds", $tytul, $opis, $cena, $obrazek);
  $stmt->execute();

  header("Location: admin.php");
  exit;
}
?>

<form method="post" enctype="multipart/form-data">
  <h2>Dodaj ofertę</h2>
  <input name="tytul" placeholder="Tytuł" required><br>
  <textarea name="opis" placeholder="Opis"></textarea><br>
  <input name="cena" type="number" step="0.01" required><br>
  <input type="file" name="obrazek" required><br>
  <button type="submit">Dodaj</button>
</form>