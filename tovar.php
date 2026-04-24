<?php
include "bdconnect.php";

if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $sql = "SELECT tovars.*, categories.category 
            FROM tovars 
            LEFT JOIN categories ON tovars.id_cat = categories.id_cat 
            WHERE tovars.id = $id";
    $result = mysqli_query($link, $sql) or die("Query failed");
    $row = mysqli_fetch_array($result);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Подробнее о товаре</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .product-info { background-color: #f0f0f0; padding: 20px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="product-info">
        <h2><?php echo $row['name']; ?></h2>
        <p><strong>ID:</strong> <?php echo $row['id']; ?></p>
        <p><strong>Категория:</strong> <?php echo $row['category']; ?></p>
        <p><strong>Цена:</strong> <?php echo $row['cena']; ?> руб.</p>
        <p><strong>Количество на складе:</strong> <?php echo $row['kol']; ?> шт.</p>
        <p><strong>Срок годности:</strong> <?php echo $row['srok']; ?></p>
    </div>
    <br>
    <a href="table_tovars.php">Назад к товарам</a>
</body>
</html>