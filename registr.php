<?php
session_start();
include "bdconnect.php";

$error = "";

if(isset($_POST["reg"])){
    $name = htmlspecialchars(trim($_POST["name"]));
    $login = htmlspecialchars(trim($_POST["login"]));
    $password = htmlspecialchars(trim($_POST["password"]));
    
    // Проверка на пустые поля
    if(empty($name) || empty($login) || empty($password)) {
        $error = "Заполните все поля!";
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        
        // Экранирование для безопасности
        $login_escaped = mysqli_real_escape_string($link, $login);
        $name_escaped = mysqli_real_escape_string($link, $name);
        
        // Проверка существования логина
        $check_query = mysqli_query($link, "SELECT * FROM users WHERE login='$login_escaped'");
        
        if($check_query && mysqli_num_rows($check_query) > 0){
            $error = "Логин уже занят, выберите другой";
        } else {
            $sql = "INSERT INTO users (login, hash, name) VALUES ('$login_escaped', '$hash', '$name_escaped')";
            $result = mysqli_query($link, $sql);
            
            if($result){
                // Получаем ID нового пользователя
                $user_id = mysqli_insert_id($link);
                $_SESSION["logged"] = 1;
                $_SESSION["userid"] = $user_id;
                header("Location: profile.php");
                exit();
            } else {
                $error = "Ошибка регистрации: " . mysqli_error($link);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 50px auto; padding: 20px; }
        .error { color: red; margin-bottom: 10px; }
        label { display: block; margin-top: 10px; }
        input[type="text"], input[type="password"] { 
            width: 100%; 
            padding: 8px; 
            margin-top: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] { 
            margin-top: 15px; 
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover { background-color: #45a049; }
        a { display: inline-block; margin-top: 15px; margin-right: 10px; }
    </style>
</head>
<body>
    <h2>Регистрация нового пользователя</h2>
    
    <?php if(!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form action="" method="post">
        <label>Логин *</label>
        <input type="text" name="login" required value="<?php echo isset($_POST['login']) ? htmlspecialchars($_POST['login']) : ''; ?>">
        
        <label>Имя *</label>
        <input type="text" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
        
        <label>Пароль *</label>
        <input type="password" name="password" required>
        
        <input type="submit" value="Зарегистрироваться" name="reg">
    </form>
    
    <br>
    <a href="login.php">Уже есть аккаунт? Войти</a>
    <br>
    <a href="index.php">На главную</a>
</body>
</html>