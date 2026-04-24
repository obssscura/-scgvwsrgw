<?php
include "bdconnect.php";

// Обработка добавления категории
if(isset($_POST["add_category"])) {
    $category_name = htmlspecialchars($_POST["category_name"]);
    
    if(!empty($category_name)) {
        $sql = "INSERT INTO categories(category) VALUES('$category_name')";
        $result = mysqli_query($link, $sql) or die("Query failed");
        header("Location: uspex.php?i=4");
        exit();
    } else {
        $error = "Введите название категории!";
    }
}

// Получение списка категорий для отображения
$sql = "SELECT * FROM categories ORDER BY id_cat";
$result = mysqli_query($link, $sql) or die("Query failed");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Склад товаров - Управление категориями</title>
</head>
<body>

    
    <hr>
    
    <h2>Добавление новой категории</h2>
    <form action="add_category.php" method="post">
        <label for="category_name">Название категории:</label><br>
        <input type="text" name="category_name" id="category_name"  required>
        <?php if(isset($error)): ?>
            <br><strong style="color:red;"><?php echo $error; ?></strong>
        <?php endif; ?>
        <br><br>
        <input type="submit" name="add_category" value="Добавить категорию">
    </form>
    
    <hr>
    
    <h2>Существующие категории</h2>
    
    <?php
    if(mysqli_num_rows($result) > 0) {
        $count = mysqli_num_rows($result);
        echo "<p>Всего категорий: $count</p>";
        
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Название категории</th><th>Действия</th></tr>";
        
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id_cat'] . "</td>";
            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
            echo "<td>";
            echo "<a href='#' onclick='return confirm(\"Удаление категории возможно только если в ней нет товаров!\");'>[Удалить]</a>";
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "<p>Категории пока не добавлены</p>";
    }
    ?>
    
    <br>
    <hr>
    <a href="index.php">На главную</a>
</body>
</html>

<?php
mysqli_close($link);
?>