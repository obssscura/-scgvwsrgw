<?php
// Файл для проверки авторизации пользователя и получения его данных

// Проверяем, что сессия запущена
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Проверка авторизации
if(!isset($_SESSION["logged"]) || $_SESSION["logged"] != "1") {
    header("Location: login.php");
    exit();
}

// Получаем ID пользователя из сессии
$user_id = isset($_SESSION["userid"]) ? $_SESSION["userid"] : 0;

if($user_id == 0) {
    header("Location: login.php");
    exit();
}

// Подключаемся к базе данных
if (!isset($link)) {
    include_once "bdconnect.php";
}

// Получаем данные пользователя
$query = mysqli_query($link, "SELECT * FROM users WHERE id = $user_id");

if(!$query || mysqli_num_rows($query) == 0) {
    // Пользователь не найден - завершаем сессию
    session_destroy();
    header("Location: login.php");
    exit();
}

// Получаем данные пользователя в массив $user
$user = mysqli_fetch_assoc($query);

// Проверяем, является ли пользователь администратором
$is_admin = ($user_id == 1);
?>