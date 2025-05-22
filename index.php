<?php 
include('includes/db.php'); 
session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Travel.pl - Oferty podróży</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <nav class="navbar container">
            <a href="index.php" class="logo">Travel.pl</a>
            <div class="user-menu">
                <?php if(isset($_SESSION['username'])): ?>
                    <span>Witaj, <?= $_SESSION['username'] ?></span>
                    <a href="moje_rezerwacje.php">Moje rezerwacje</a>
                    <a href="logout.php">Wyloguj</a>
                <?php else: ?>
                    <a href="login.php">Zaloguj</a>
                    <a href="register.php">Rejestracja</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main class="container">
        <h1>Odkrywaj świat z nami!</h1>
        
        <div class="offers-grid">
            <?php
            $result = $conn->query("SELECT * FROM offers");
            while($row = $result->fetch_assoc()):
            ?>
                <article class="offer-card">
                    <img src="uploads/<?= $row['image'] ?>" class="offer-image" alt="<?= $row['title'] ?>">
                    <div class="offer-content">
                        <h2><?= $row['title'] ?></h2>
                        <p><?= $row['description'] ?></p>
                        <p class="offer-price"><?= $row['price'] ?> zł / noc</p>
                        <a href="booking.php?id=<?= $row['id'] ?>" class="btn">Zarezerwuj teraz</a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>