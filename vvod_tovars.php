<?
    include "func.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Склад товаров->Добавление товара</title>
</head>
<body>
    <form action="insert_tovars.php" method="post" name="form1">
        Название товара <input type="text" name="name"><br><br>
        Категория товара 
        <select name="category">
            <?
                 include "bdconnect.php";
                 $sql = "SELECT * FROM categories";
                 $result = mysqli_query($link, $sql) or die("Query failed");
                 $str = "";
                 while ($row = mysqli_fetch_array($result)) {
                     $array_category[$row["id_cat"]] = $row["category"];
                     $str = $str."<option value='".$row["id_cat"]."'>".$row["category"]."</option>";
                 };
             
             
               echo $str;
            ?>
        </select><br><br>
        Цена товара <input type="number" name="cena"><br><br>
        Количество <input type="text" name="kol"><br><br>
        Срок годности <input type="date" name="srok"><br><br>
<input type="submit" name="insert" value="Добавить">
</form>
</body>
</html> 