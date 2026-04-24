<?php
include "bdconnect.php";

// Обработка удаления
if(isset($_POST["ud_id"]))
{
    $mass = $_POST["ud_id"];
    $i = 0;
    while(isset($mass[$i]))
    {
        $sql = "DELETE FROM tovars WHERE id=" . $mass[$i];
        $result1 = mysqli_query($link, $sql) or die("Query failed");
        $i++;
    }
    header("Location: uspex.php?i=2");
    exit();
}
?>

<h3 align="center">Список товаров</h3>

<form method="post" action="ud_tovars.php">
<table width="100%" border="2">
<tr>
    <td>Номер</td>
    <td>Наименование</td>
    <td>Цена</td>
    <td>Количество</td>
    <td>Срок</td>
    <td>Редактировать</td>
    <td>Удалить</td>
</tr>

<?php
$result = mysqli_query($link, "SELECT * FROM tovars");
while($row = mysqli_fetch_array($result))
{
    $id = $row[0];
    echo "<tr>
            <td>".$row["id"]."</td>
            <td>".$row["name"]."</td>
            <td>".$row["cena"]."</td>
            <td>".$row["kol"]."</td>
            <td>".$row["srok"]."</td>
            <td>
                <a href='updata.php?id=".$id."'>редактировать</a>
            </td>
            <td>
                <input type='checkbox' name='ud_id[]' value='".$id."'>
            </td>
        </tr>";
}
?>
</table>

<center><input type="submit" name="ud" value="Удалить"></center>
</form>

<a href="index.php">На главную</a>