<?php
session_start();
include "bdconnect.php";

// Если уже авторизован - на профиль
if(isset($_SESSION["logged"]) && $_SESSION["logged"] == "1"){
    header("Location: profile.php");
    exit();
}

$error = "";

if(isset($_POST["auth"])){
    $login = mysqli_real_escape_string($link, $_POST["login"]);
    $password = $_POST["password"];
    
    $query = mysqli_query($link, "SELECT * FROM users WHERE login='$login'");
    
    if($query && mysqli_num_rows($query) == 1){
        $user_data = mysqli_fetch_assoc($query);
        if(password_verify($password, $user_data["hash"])){
            $_SESSION["logged"] = 1;
            $_SESSION["userid"] = $user_data["id"];
            header("Location: profile.php");
            exit();
        } else {
            $error = "Неверный логин или пароль";
        }
    } else {
        $error = "Неверный логин или пароль";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Вход на сайт</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 300px; margin: 100px auto; padding: 20px; }
        input[type="text"], input[type="password"] { 
            width: 100%; 
            padding: 8px; 
            margin: 10px 0;
            box-sizing: border-box;
        }
        input[type="submit"] { 
            width: 100%; 
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover { background-color: #45a049; }
        .error { color: red; margin-top: 10px; text-align: center; }
        a { display: inline-block; margin-top: 15px; margin-right: 10px; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Вход в систему</h2>
    
    <form method="POST">
        <input type="text" name="login" placeholder="Логин" required/>
        <input type="password" name="password" placeholder="Пароль" required/>
        <input type="submit" value="Войти" name="auth"/>
    </form>
    
    <?php if(!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <br>
    <a href="registr.php">Зарегистрироваться</a>
    <a href="index.php">На главную</a>
</body>
</html>