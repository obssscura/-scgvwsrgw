<?php
include "func.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Склад товаров->Информация о товарах</title>
</head>
<body>
<h3 align="center">Список товаров</h3>

<form action="" method="post">
    <label for="category">Выбор по категории </label>
    <select name="category">
        <option value="Bce">Bce</option>
        <?php echo show_categories(); ?>
    </select>
    <input type="submit" value="Фильтр" name="filtr">
</form>
<br>

<form action="zakaz.php" method="post">
<table width="100%" border="2">
    <tr>
        <th>Номер</th>
        <th>Наименование</th>
        <th>Категория</th>
        <th>Цена</th>
        <th>Количество</th>
        <th>Срок годности</th>
        <th>Подробнее</th>
        <th>Добавить в корзину</th>
    </tr>

<?php
include "bdconnect.php";

// Фильтр по категориям
$category_filter = isset($_POST['category']) ? $_POST['category'] : 'Bce';
$sql = "SELECT tovars.*, categories.category 
        FROM tovars 
        LEFT JOIN categories ON tovars.id_cat = categories.id_cat";

if($category_filter != 'Bce') {
    $category_filter_escaped = mysqli_real_escape_string($link, $category_filter);
    $sql .= " WHERE categories.category = '$category_filter_escaped'";
}

$result = mysqli_query($link, $sql) or die("Query failed");

while($row = mysqli_fetch_array($result)) {
    ?>
    <tr>
        <td><?php echo $row["id"]; ?></td>
        <td><?php echo $row["name"]; ?></td>
        <td><?php echo $row["category"]; ?></td>
        <td><?php echo $row["cena"]; ?></td>
        <td><?php echo $row["kol"]; ?></td>
        <td><?php echo $row["srok"]; ?></td>
        <td><a href='tovar.php?id=<?php echo $row["id"]; ?>'>Подробнее</a></td>
        <td><input type='checkbox' name='id[]' value='<?php echo $row["id"]; ?>'></td>
    </tr>
    <?php
}
?>

</table>
<br>
<input type="submit" value="Добавить в корзину" name="add_to_cart">
</form>
<br>
<a href="index.php">На главную</a>
</body>
</html>

<?php
mysqli_close($link);
?>