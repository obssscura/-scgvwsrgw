<?php
session_start();
include "bdconnect.php";
include "validate_user.php";

// Проверка: только админ (id_user = 1) имеет доступ
if(!isset($user["id"]) || $user["id"] != 1){
    header("Location: profile.php");
    exit();
}

// Получение списка всех пользователей для фильтра (опционально)
$users_query = mysqli_query($link, "SELECT id, login, name FROM users ORDER BY login");
$users_list = mysqli_fetch_all($users_query, MYSQLI_ASSOC);

// Фильтр по пользователю (если выбран)
$filter_user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

// Базовый запрос на получение всех заказов с данными пользователей и товаров
$sql = "SELECT o.*, 
               u.login as user_login, 
               u.name as user_name,
               t.name as tovar_name, 
               t.cena, 
               t.srok 
        FROM orders o 
        LEFT JOIN users u ON o.id_user = u.id 
        LEFT JOIN tovars t ON o.id_tovar = t.id";

if($filter_user_id > 0) {
    $sql .= " WHERE o.id_user = $filter_user_id";
}

$sql .= " ORDER BY o.datatime DESC";

$orders_query = mysqli_query($link, $sql);

// Подсчет общей суммы всех отфильтрованных заказов
$total_all_sum = 0;
$orders_data = [];
while($row = mysqli_fetch_assoc($orders_query)) {
    $row['sum'] = $row['quantity'] * $row['cost'];
    $total_all_sum += $row['sum'];
    $orders_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель администратора - Все заказы</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            max-width: 1200px; 
            margin: 30px auto; 
            padding: 20px; 
            background-color: #f5f5f5;
        }
        .admin-header {
            background-color: #2c3e50;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-header h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        .admin-header .badge {
            background-color: #e74c3c;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .filter-box {
            background-color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            display: flex;
            gap: 15px;
            align-items: flex-end;
            flex-wrap: wrap;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .filter-group label {
            font-weight: bold;
            font-size: 0.8rem;
            color: #555;
        }
        select, button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 0.9rem;
        }
        button {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }
        button:hover {
            background-color: #2980b9;
        }
        .reset-btn {
            background-color: #95a5a6;
        }
        .reset-btn:hover {
            background-color: #7f8c8d;
        }
        .stats {
            background-color: #ecf0f1;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            gap: 30px;
            font-weight: bold;
        }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .orders-table th, .orders-table td {
            border: 1px solid #ddd;
            padding: 12px 8px;
            text-align: left;
            vertical-align: top;
        }
        .orders-table th {
            background-color: #2c3e50;
            color: white;
            font-weight: bold;
        }
        .orders-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .orders-table tr:hover {
            background-color: #f1f9ff;
        }
        .total-row {
            background-color: #e8f5e9 !important;
            font-weight: bold;
        }
        .total-row td {
            background-color: #e8f5e9;
        }
        .action-links {
            margin-top: 25px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .action-links a {
            display: inline-block;
            padding: 10px 18px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.2s;
        }
        .action-links a:hover {
            background-color: #2980b9;
        }
        .action-links a.logout {
            background-color: #e74c3c;
        }
        .action-links a.logout:hover {
            background-color: #c0392b;
        }
        .no-orders {
            text-align: center;
            padding: 40px;
            background-color: white;
            border-radius: 8px;
            color: #7f8c8d;
        }
        @media (max-width: 768px) {
            .orders-table {
                font-size: 0.8rem;
            }
            .orders-table th, .orders-table td {
                padding: 6px 4px;
            }
            .filter-box {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <h1>👑 Панель администратора</h1>
        <div class="badge">Добро пожаловать, <?php echo htmlspecialchars($user["name"]); ?> (ID: <?php echo $user["id"]; ?>)</div>
    </div>

    <div class="filter-box">
        <div class="filter-group">
            <label>📌 Фильтр по пользователю:</label>
            <form method="GET" style="display: flex; gap: 10px;">
                <select name="user_id">
                    <option value="0">-- Все пользователи --</option>
                    <?php foreach($users_list as $u): ?>
                        <option value="<?php echo $u['id']; ?>" <?php echo ($filter_user_id == $u['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($u['login'] . ' (' . $u['name'] . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Применить фильтр</button>
                <?php if($filter_user_id > 0): ?>
                    <a href="profile_admin.php" style="text-decoration: none;"><button type="button" class="reset-btn">Сбросить</button></a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="stats">
        <span>📊 Количество заказов: <?php echo count($orders_data); ?></span>
        <span>💰 Общая сумма: <?php echo number_format($total_all_sum, 2); ?> руб.</span>
        <?php if($filter_user_id > 0): 
            $filtered_user = array_filter($users_list, function($u) use ($filter_user_id) { return $u['id'] == $filter_user_id; });
            $filtered_user = reset($filtered_user);
        ?>
            <span>👤 Фильтр по: <?php echo htmlspecialchars($filtered_user['login'] ?? 'Пользователь'); ?></span>
        <?php endif; ?>
    </div>

    <?php if(count($orders_data) > 0): ?>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>№ заказа</th>
                    <th>Пользователь</th>
                    <th>Товар</th>
                    <th>Кол-во</th>
                    <th>Цена за шт.</th>
                    <th>Сумма</th>
                    <th>Дата заказа</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($orders_data as $order): ?>
                <tr>
                    <td><?php echo $order["id_order"]; ?></td>
                    <td>
                        <strong><?php echo htmlspecialchars($order["user_login"]); ?></strong><br>
                        <small><?php echo htmlspecialchars($order["user_name"]); ?></small>
                    </td>
                    <td><?php echo htmlspecialchars($order["tovar_name"]); ?></td>
                    <td><?php echo $order["quantity"]; ?></td>
                    <td><?php echo number_format($order["cost"], 2); ?> руб.</td>
                    <td><?php echo number_format($order["sum"], 2); ?> руб.</td>
                    <td><?php echo date("d.m.Y H:i", strtotime($order["datatime"])); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="5" style="text-align: right;"><strong>ИТОГО по всем заказам:</strong></td>
                    <td colspan="2"><strong><?php echo number_format($total_all_sum, 2); ?> руб.</strong></td>
                </tr>
            </tfoot>
        </table>
    <?php else: ?>
        <div class="no-orders">
            <p>📭 Заказов не найдено.</p>
            <?php if($filter_user_id > 0): ?>
                <p>Попробуйте сбросить фильтр или выберите другого пользователя.</p>
            <?php endif; ?>
            <a href="table_tovars.php" style="color: #3498db;">Перейти к товарам</a>
        </div>
    <?php endif; ?>

    <div class="action-links">
        <a href="profile.php">📋 Мой профиль</a>
        <a href="table_tovars.php">🛒 Магазин</a>
        <a href="index.php">🏠 На главную</a>
        <a href="logout.php" class="logout">🚪 Выйти</a>
    </div>
</body>
</html>