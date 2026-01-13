<?php
global $database;

// Подключаем утилиты для работы с заказами
require_once __DIR__ . '/../includes/order_utils.php';
require_once __DIR__ . '/../includes/path_helper.php';

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . url('?page=login'));
    exit;
}

$user_id = $_SESSION['user_id'];

// Получение списка заказов для текущего пользователя
$sql = "
    SELECT o.*, u.email as user_email,
           COUNT(oi.id) as items_count
    FROM orders o
    JOIN users u ON o.user_id = u.id
    LEFT JOIN order_items oi ON o.id = oi.order_id
    WHERE o.user_id = :user_id
    GROUP BY o.id
    ORDER BY o.created_at DESC
";
$stmt = $database->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="user-orders container">
    <h1>Мои заказы</h1>

    <?php if (empty($orders)): ?>
        <div class="empty_zakaz_block">
            <span>ВАШИ ЗАКАЗЫ БУДУТ ОТОБРАЖАТЬСЯ ЗДЕСЬ</span>
            <p>Как только вы оформите заказ, вы сможете следить за его доставкой на каждом этапе.</p>
            <a href="<?= url('?page=catalog') ?>" class="black_btn">ПЕРЕЙТИ В КАТАЛОГ</a>
        </div>
    <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <h3>Заказ #<?= htmlspecialchars($order['order_number']) ?></h3>
                        <span class="order-date"><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></span>
                    </div>

                    <div class="order-info">
                        <p>Статус: <strong><?= translateOrderStatus($order['status']) ?></strong></p>
                        <p>Количество товаров: <?= $order['items_count'] ?></p>
                        <p>Сумма заказа: <?= number_format($order['total_amount'], 0, ',', ' ') ?> ₽</p>
                    </div>

                    <div class="order-items">
                        <h4>Состав заказа:</h4>
                        <?php
                        $items_sql = "
                            SELECT oi.*, p.title as product_title, s.title as size_title,
                                   (SELECT path FROM images WHERE product_id = p.id LIMIT 1) as image_path
                            FROM order_items oi
                            JOIN products p ON oi.product_id = p.id
                            JOIN size s ON oi.size_id = s.id
                            WHERE oi.order_id = :order_id
                        ";
                        $stmt_items = $database->prepare($items_sql);
                        $stmt_items->execute([':order_id' => $order['id']]);
                        $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
                        ?>

                        <table>
                            <thead>
                                <tr>
                                    <th>Товар</th>
                                    <th></th>
                                    <th>Размер</th>
                                    <th>Количество</th>
                                    <th>Цена</th>
                                    <th>Сумма</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td>
                                            <?php if ($item['image_path']): ?>
                                                <img src="<?= url('uploads/products/' . htmlspecialchars($item['image_path'])) ?>" alt="<?= htmlspecialchars($item['product_title']) ?>" width="50">
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($item['product_title']) ?></td>
                                        <td><?= htmlspecialchars($item['size_title']) ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td><?= number_format($item['price'], 0, ',', ' ') ?> ₽</td>
                                        <td><?= number_format($item['price'] * $item['quantity'], 0, ',', ' ') ?> ₽</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.user-orders {
    padding: 20px 0;
}
.order-card {
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-bottom: 20px;
    padding: 20px;
}
.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    border-bottom: 1px solid #eee;
    padding-bottom: 15px;
}
.order-date {
    color: #666;
}
.order-info {
    margin-bottom: 15px;
}
.order-info p {
    margin: 5px 0;
}
.order-items h4 {
    margin-top: 20px;
    margin-bottom: 10px;
}
.order-items table {
    width: 100%;
    border-collapse: collapse;
}
.order-items th,
.order-items td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}
.order-items th {
    background: #f0f0f0;
}
.empty_zakaz_block {
    text-align: center;
    padding: 50px 0;
}
</style>

