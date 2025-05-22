<?php

include('includes/db.php');
session_start();


if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    exit("Brak uprawnień dostępu");
}


$id_oferty = $_GET['id'];


$zapytanie = $conn->prepare("DELETE FROM offers WHERE id = ?");
$zapytanie->bind_param("i", $id_oferty);
$zapytanie->execute();

header("Location: admin.php");
exit;
?>