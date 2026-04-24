<?php
include "bdconnect.php";
$name = htmlspecialchars($_POST["name"]);
$cena = htmlspecialchars($_POST["cena"]);
$kol = htmlspecialchars($_POST["kol"]);
$srok = htmlspecialchars($_POST["srok"]);
$id_cat = htmlspecialchars($_POST["category"]);

if (!empty($name) && !empty($cena) && !empty($kol) && !empty($srok) && !empty($id_cat)) {
    $sql = "INSERT INTO tovars(name, id_cat, cena, kol, srok) VALUES('$name', '$id_cat', '$cena', '$kol', '$srok')";
    $result = mysqli_query($link, $sql) or die("Query failed");
    
    Header("Location: uspex.php?i=1");
} else {
    echo '<p style="color: red; font-size: 50px; font-weight: bold;">Заполните все поля!</p>';
    echo '<a href="vvod_tovars.php">Вернуться</a>';
}


?>