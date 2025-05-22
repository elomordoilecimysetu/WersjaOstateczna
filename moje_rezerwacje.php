<?php
include('includes/db.php');
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Moje rezerwacje | Travel.pl</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <nav class="navbar container">
            <a href="index.php" class="logo">Travel.pl</a>
            <div class="user-menu">
                <a href="index.php">Powrót</a>
                <a href="logout.php">Wyloguj</a>
            </div>
        </nav>
    </header>

    <main class="container">
        <h2>Moje rezerwacje</h2>

        <?php
        $result = $conn->query("SELECT r.*, o.title 
            FROM rezerwacje r 
            JOIN offers o ON r.offer_id = o.id 
            ORDER BY r.data_rezerwacji DESC");

        if ($result->num_rows === 0) {
            echo "<p>Brak aktywnych rezerwacji.</p>";
        }

        while($rez = $result->fetch_assoc()):
            $dni = (strtotime($rez['data_do']) - strtotime($rez['data_od'])) / (60 * 60 * 24);
        ?>
            <div class="reservation-card">
                <h3><?= htmlspecialchars($rez['title']) ?></h3>
                <p><strong>Okres pobytu:</strong> 
                    <?= date('d.m.Y', strtotime($rez['data_od'])) ?> - 
                    <?= date('d.m.Y', strtotime($rez['data_do'])) ?>
                    (<?= $dni ?> dni)
                </p>
                <p><strong>Goście:</strong> <?= $rez['goscie'] ?></p>
                <p><strong>Cena całkowita:</strong> <?= $rez['cena_calkowita'] ?> zł</p>
                <p><strong>Kontakt:</strong><br>
                    <?= htmlspecialchars($rez['imie_nazwisko']) ?><br>
                    Tel: <?= htmlspecialchars($rez['telefon']) ?><br>
                    Email: <?= htmlspecialchars($rez['email']) ?>
                </p>
            </div>
        <?php endwhile; ?>
    </main>
</body>
</html>