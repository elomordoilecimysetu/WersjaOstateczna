<?php
session_start();
if (!$_SESSION['is_admin']) {
  header("Location: index.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Panel administratora | Travel.pl</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Panel administratora</h2>
    <a href="dodaj_oferte.php">Dodaj nową ofertę</a>

    <?php
    include('includes/db.php');
    $wynik = $conn->query("SELECT * FROM offers");
    while($oferta = $wynik->fetch_assoc()):
    ?>
      <div class="oferta">
          <h3><?= htmlspecialchars($oferta['title']) ?></h3>
          <p><?= htmlspecialchars($oferta['description']) ?></p>
          <p>Cena: <?= $oferta['price'] ?> PLN</p>
          <a href="usun_oferte.php?id=<?= $oferta['id'] ?>" class="przycisk-usun">Usuń</a>
      </div>
    <?php endwhile; ?>
</body>
</html>