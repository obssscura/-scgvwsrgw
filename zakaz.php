<?php 
session_start();
include "bdconnect.php";

// Если товары выбраны для добавления в корзину
if(isset($_POST["add_to_cart"]) && isset($_POST["id"])) {
    $mass = $_POST["id"];
    
    // Сохраняем выбранные товары в сессию
    if(!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    }
    
    foreach($mass as $item_id) {
        if(!in_array($item_id, $_SESSION["cart"])) {
            $_SESSION["cart"][] = $item_id;
        }
    }
}

// Обработка оформления заказа
if(isset($_POST["zak"])) {
    $mass = $_POST["id"];
    $kol = $_POST["kol"];
    $cena = $_POST["cena"];
    
    $id_user = $_SESSION["userid"];
    $data = date("Y-m-d H:i:s");
    $id_order = time(); // Используем timestamp как ID заказа
    
    for($i = 0; $i < count($mass); $i++) {
        $tovar_id = intval($mass[$i]);
        $quantity = intval($kol[$i]);
        $price = floatval($cena[$i]);
        
        $sql = "INSERT INTO orders (id_order, id_user, id_tovar, quantity, cost, datatime) 
                VALUES ('$id_order', '$id_user', '$tovar_id', '$quantity', '$price', '$data')";
        $result1 = mysqli_query($link, $sql) or die("Query failed: " . mysqli_error($link));
        
        // Уменьшаем количество товара на складе
        $update_stock = "UPDATE tovars SET kol = kol - $quantity WHERE id = $tovar_id";
        mysqli_query($link, $update_stock);
    }
    
    // Очищаем корзину
    unset($_SESSION["cart"]);
    
    // Перенаправление на страницу uspex.php
    header("Location: uspex.php?i=5");
    exit();
}

// Получаем товары из сессии (корзины)
$mass = isset($_SESSION["cart"]) ? $_SESSION["cart"] : array();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Склад товаров->Корзина товаров</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        .stoi {
            font-weight: bold;
        }
        .total {
            font-size: 20px;
            font-weight: bold;
            margin-top: 20px;
            text-align: right;
        }
        .btn {
            padding: 10px 20px;
            margin: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function calculateTotal() {
            var total = 0;
            var rows = document.querySelectorAll('table tr');
            for(var i = 1; i < rows.length - 1; i++) {
                var row = rows[i];
                var quantity = row.querySelector('input[type="number"]').value;
                var price = row.querySelector('input[type="hidden"]').value;
                var subtotal = quantity * price;
                var stoiSpan = row.querySelector('.stoi');
                if(stoiSpan) {
                    stoiSpan.innerHTML = subtotal;
                }
                total += subtotal;
            }
            var totalSpan = document.getElementById('total_amount');
            if(totalSpan) {
                totalSpan.innerHTML = total;
            }
        }
        
        window.onload = function() {
            calculateTotal();
        }
    </script>
</head>
<body>
    <h1>Ваша корзина</h1>
    
    <?php if(count($mass) > 0): ?>
        <form action="" method="post" name="frt">
            <table align="center" border="1">
                <tr>
                    <th>ID товара</th>
                    <th>Наименование</th>
                    <th>Количество</th>
                    <th>Цена за 1 шт.</th>
                    <th>Общая стоимость</th>
                    <th>Выбрать</th>
                </tr>
                
                <?php
                $items = array();
                foreach($mass as $item_id) {
                    $sql = "SELECT * FROM tovars WHERE id=" . intval($item_id);
                    $result = mysqli_query($link, $sql) or die("Query failed");
                    $row = mysqli_fetch_array($result);
                    if($row) {
                        $items[] = $row;
                    }
                }
                
                foreach($items as $index => $row):
                ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td>
                        <?php echo htmlspecialchars($row["name"]); ?>
                        <input type="hidden" name="name[]" value="<?php echo htmlspecialchars($row["name"]); ?>">
                    </td>
                    <td>
                        <input type="number" name="kol[]" value="1" min="1" max="<?php echo $row["kol"]; ?>" onchange="calculateTotal()">
                    </td>
                    <td>
                        <?php echo $row["cena"]; ?> руб.
                        <input type="hidden" name="cena[]" value="<?php echo $row["cena"]; ?>">
                    </td>
                    <td>
                        <span class="stoi"><?php echo $row["cena"]; ?></span>
                    </td>
                    <td>
                        <input type="checkbox" name="id[]" value="<?php echo $row["id"]; ?>" checked>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            
            <div class="total">
                Общая стоимость заказа: <span id="total_amount">0</span> руб.
            </div>
            
            <input type="submit" name="zak" value="Оформить заказ" class="btn" onclick="return confirm('Подтвердите оформление заказа');">
        </form>
        
    <?php else: ?>
        <p>Корзина пуста. <a href="table_tovars.php">Перейти к товарам</a></p>
    <?php endif; ?>
    
    <br>
    <a href="table_tovars.php">Назад к товарам</a> |
    <a href="index.php">На главную</a>
</body>
</html>

<?php
mysqli_close($link);
?>