<?php
session_start();
include "bdconnect.php";
include "validate_user.php";

// Проверка на админа (id_user = 1)
if(isset($user["id"]) && $user["id"] == 1){
    header("Location: profile_admin.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; }
        .info { background-color: #f0f0f0; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .orders-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .orders-table th, .orders-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .orders-table th { background-color: #4CAF50; color: white; }
        a { display: inline-block; margin-top: 10px; margin-right: 10px; }
        h3 { margin-top: 30px; }
    </style>
</head>
<body>
    <div class="info">
        <h2>Добро пожаловать, <?php echo htmlspecialchars($user["name"]); ?>!</h2>
        <p><strong>Логин:</strong> <?php echo htmlspecialchars($user["login"]); ?></p>
        <p><strong>Имя:</strong> <?php echo htmlspecialchars($user["name"]); ?></p>
        <p><strong>ID пользователя:</strong> <?php echo $user["id"]; ?></p>
        
        <?php if(!empty($user["photo"])): ?>
            <p><strong>Фото:</strong> <?php echo htmlspecialchars($user["photo"]); ?></p>
        <?php endif; ?>
        
        <?php if(!empty($user["age"])): ?>
            <p><strong>Возраст:</strong> <?php echo htmlspecialchars($user["age"]); ?></p>
        <?php endif; ?>
        
        <?php if(!empty($user["salary"])): ?>
            <p><strong>Зарплата:</strong> <?php echo htmlspecialchars($user["salary"]); ?></p>
        <?php endif; ?>
    </div>
    
    <h3>Мои заказы</h3>
    <?php
    // Получаем заказы пользователя с информацией о товарах
    $user_id = $user["id"];
    $orders_query = mysqli_query($link, "SELECT o.*, t.name as tovar_name, t.cena, t.srok 
                                        FROM orders o 
                                        LEFT JOIN tovars t ON o.id_tovar = t.id 
                                        WHERE o.id_user = $user_id 
                                        ORDER BY o.datatime DESC");
    
    if(mysqli_num_rows($orders_query) > 0):
    ?>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>№ заказа</th>
                    <th>Товар</th>
                    <th>Количество</th>
                    <th>Цена за шт.</th>
                    <th>Сумма</th>
                    <th>Дата заказа</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $total_sum = 0;
            while($order = mysqli_fetch_assoc($orders_query)): 
                $sum = $order["quantity"] * $order["cost"];
                $total_sum += $sum;
            ?>
                <tr>
                    <td><?php echo $order["id_order"]; ?></td>
                    <td><?php echo htmlspecialchars($order["tovar_name"]); ?></td>
                    <td><?php echo $order["quantity"]; ?></td>
                    <td><?php echo number_format($order["cost"], 2); ?> руб.</td>
                    <td><?php echo number_format($sum, 2); ?> руб.</td>
                    <td><?php echo date("d.m.Y H:i", strtotime($order["datatime"])); ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
            <tfoot>
                <tr style="background-color: #f0f0f0; font-weight: bold;">
                    <td colspan="4" style="text-align: right;">ИТОГО:</td>
                    <td colspan="2"><?php echo number_format($total_sum, 2); ?> руб.</td>
                </tr>
            </tfoot>
        </table>
    <?php else: ?>
        <p>У вас пока нет заказов. <a href="table_tovars.php">Перейти к товарам</a></p>
    <?php endif; ?>
    
    <br>
    <a href="logout.php">Выйти из аккаунта</a>
    <a href="index.php">На главную</a>
    <a href="table_tovars.php">Магазин</a>
</body>
</html>