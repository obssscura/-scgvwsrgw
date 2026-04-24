<?php
$i = 0;
$i = $_GET["i"];
if($i == 1) $st = "данные успешно добавлены";
if($i == 2) $st = "записи успешно удалены";
if($i == 3) $st = "записи успешно обновлены";
if($i == 4) $st = "категория успешно добавлена";
if($i == 5) $st = "заказ успешно оформлен! Спасибо за покупку!"; // Добавлено для заказа

// Получаем название категории если есть
if(isset($_GET["cat"])) {
    $cat_name = htmlspecialchars($_GET["cat"]);
    $st .= ": " . $cat_name;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Успешно</title>
</head>
<body>
    <table border="0" width="100%">
        <tr>
            <td align="center">
                <br><br><br><br><br><br><br><br><br><br>
                <table>
                    <tr>
                        <td><big><strong><?php echo $st; ?></strong></big></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>
    <a href="index.php">На главную</a>
</body>
</html>