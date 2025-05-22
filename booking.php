<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('includes/db.php');
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: logowanie.php");
    exit;
}

if (!isset($_GET['id'])) {
    die("Nieprawidłowe ID oferty!");
}

$id_oferty = (int)$_GET['id'];


$zapytanie = $conn->prepare("SELECT * FROM offers WHERE id = ?");
$zapytanie->bind_param("i", $id_oferty);
$zapytanie->execute();
$oferta = $zapytanie->get_result()->fetch_assoc();

if (!$oferta) {
    die("Oferta nie istnieje w systemie!");
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $wymagane_pola = ['data_poczatek', 'data_koniec', 'ilosc_osob', 'imie_nazwisko', 'email', 'telefon'];
    foreach ($wymagane_pola as $pole) {
        if (empty($_POST[$pole])) {
            die("Proszę wypełnić wszystkie wymagane pola!");
        }
    }

    $data_poczatek = $_POST['data_poczatek'];
    $data_koniec = $_POST['data_koniec'];
    $ilosc_dni = (strtotime($data_koniec) - strtotime($data_poczatek)) / 86400;
    
    if ($ilosc_dni <= 0) {
        die("Data zakończenia musi być późniejsza niż data rozpoczęcia!");
    }

    $cena_laczna = $oferta['price'] * $ilosc_dni;

    try {
        $stmt = $conn->prepare("
            INSERT INTO rezerwacje 
            (offer_id, data_od, data_do, goscie, cena_calkowita, imie_nazwisko, email, telefon) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param(
            "issidsss",
            $id_oferty,
            $data_poczatek,
            $data_koniec,
            $_POST['ilosc_osob'],
            $cena_laczna,
            $_POST['imie_nazwisko'],
            $_POST['email'],
            $_POST['telefon']
        );

        if ($stmt->execute()) {
            header("Location: moje_rezerwacje.php");
            exit;
        } else {
            die("Błąd zapisu danych: " . $conn->error);
        }
    } catch (Exception $e) {
        die("Wystąpił błąd: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Rezerwacja | Travel.pl</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="naglowek">
        <div class="kontener">
            <a href="index.php" class="logo">Travel.pl</a>
            <nav class="menu-uzytkownika">
                <?php if(isset($_SESSION['username'])): ?>
                    <a href="moje_rezerwacje.php">Moje rezerwacje</a>
                    <a href="wyloguj.php">Wyloguj się</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <main class="kontener">
        <div class="panel-rezerwacji">
            <h2>Rezerwacja: <?= htmlspecialchars($oferta['title']) ?></h2>
            <p class="cena">Cena: <?= htmlspecialchars($oferta['price']) ?> zł za dobę</p>

            <form method="post">
                <div class="grupa-formularza">
                    <label>Data rozpoczęcia:</label>
                    <input type="date" name="data_poczatek" required>
                </div>

                <div class="grupa-formularza">
                    <label>Data zakończenia:</label>
                    <input type="date" name="data_koniec" required>
                </div>

                <div class="grupa-formularza">
                    <label>Ilość osób:</label>
                    <select name="ilosc_osob" required>
                        <option value="1">1 osoba</option>
                        <option value="2">2 osoby</option>
                        <option value="3">3 osoby</option>
                        <option value="4">4 osoby</option>
                    </select>
                </div>

                <div class="grupa-formularza">
                    <label>Imię i nazwisko:</label>
                    <input type="text" name="imie_nazwisko" required>
                </div>

                <div class="grupa-formularza">
                    <label>Adres e-mail:</label>
                    <input type="email" name="email" required>
                </div>

                <div class="grupa-formularza">
                    <label>Numer telefonu:</label>
                    <input type="tel" name="telefon" required>
                </div>

                <button type="submit" class="przycisk">Potwierdź rezerwację</button>
                <a href="index.php" class="przycisk wstecz">Powrót</a>
            </form>
        </div>
    </main>
</body>
</html>