<?php
include "bdconnect.php"; 
if(isset($_GET["id"])){
    $id = $_GET["id"];
    $sql = "SELECT tovars.id, name, category, cena, kol, srok FROM tovars, categories WHERE tovars.id_cat = categories.id_cat AND tovars.id = $id";
    $result = mysqli_query($link, $sql) or die("Товар не найден");
    
    $row = mysqli_fetch_array($result);
}

if(isset($_POST["red"])){
    $id = $_POST["id"];
    $cena = $_POST["cena"];
    $kol = $_POST["kol"];
    $sql = "UPDATE tovars SET cena = $cena, kol = $kol WHERE id = $id";
    $result = mysqli_query($link, $sql) or die("Ошибка во время обновления информации");
    header("Location: uspex.php?i=3");
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Склад товаров - редактирование товара</title>
</head>
<body>
    <h1>Информация о товаре</h1>
    <form action="" method="post" name="frt">
        <table align="center">
            <tr>
                <td class="t2">Идентификатор товара</td>
                <td><?php echo $row["id"]; ?></td>
            </tr>
            <tr>
                <td class="t2">Название продукта</td>
                <td><?php echo $row["name"]; ?></td>
            </tr>
            <tr>
                <td class="t2">Категория товара</td>
                <td><?php echo $row["category"]; ?></td>
            </tr>
            <tr>
                <td class="t2">Цена товара</td>
                <td><input type="text" size="10" maxlength="10" name="cena" id="cena" value="<?php echo $row["cena"]; ?>" /></td>
            </tr>
            <tr>
                <td class="t2">Количество на складе</td>
                <td><input type="number" size="11" maxlength="11" name="kol" id="kol" value="<?php echo $row["kol"]; ?>" /></td>
            </tr>
            <tr>
                <td class="t2">Срок годности</td>
                <td><?php echo $row["srok"]; ?></td>
            </tr>
            <tr>
                <td><input type="hidden" name="id" value="<?php echo $id; ?>" /></td>
                <td><input type="submit" name="red" value="Сохранить" /></td>
            </tr>
            <tr>
                <td colspan="2"><a href="index.php">На главную</a></td>
            </tr>
        </table>
    </form>
</body>
</html>