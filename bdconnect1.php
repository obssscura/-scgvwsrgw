<?php
include "bdconnect.php"; //соединение с базой данных.
$sql = "SELECT * FROM tovars"; // выбрать все записи из таблицы workers
$result = mysqli_query ($link, $sql) or die("Query failed"); // запрос к БД
//Создание массива $row из выбранной таблицы (функция mysqli_fetch_array() ):
while ($row = mysqli_fetch_array($result)) // вывод по одной строке
{
 //форматированный вывод на экран (по формату; %s - строка)
printf ("Товар № - %s %s %s %s<br>", $row['id'], $row['name'], $row['cena'], $row['kol']);
}
mysqli_close($link); //закрыть соединение с БД
?>