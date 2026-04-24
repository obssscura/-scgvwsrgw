<?php
$host="localhost"; // имя сервера с MySQL
$user="tfkmdctr"; //имя пользователя
$pass="n7Yg2r"; // пароль пользователя
$dbName="tfkmdctr_m1"; // название базы данных
//Создать соединение с сервером и БД
$link = mysqli_connect($host, $user, $pass, $dbName) or die (mysqli_error());//ссылка на бд
?>