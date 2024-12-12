<?php
session_start();
require 'db.php';

global $db;

// Перевірка авторизації
if (!isset($_SESSION['is_authenticated']) || !$_SESSION['is_authenticated']) {
    header('Location: login_form.php');
    exit;
}
// Перевірка терміну дії сесії
$stmt = $db->prepare("SELECT auth_expires FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$auth_expires = $stmt->fetchColumn();

if (!$auth_expires || strtotime($auth_expires) <= time()) {
    logout($db);
    header('Location: login_form.php');
    exit;
}

// Отримання замовлень користувача
$stmt = $db->prepare("
    SELECT o.order_id, o.date, b.item_id, b.q, b.price_out, i.name, o.total
    FROM orders o
    JOIN basket b ON o.order_id = b.order_id
    JOIN items i ON b.item_id = i.item_id
    WHERE o.user_id = ? AND o.pay = 1
    ORDER BY o.date DESC
");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Історія замовлень</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Історія замовлень</h1>

<?php if (empty($orders)): ?>
    <p>У вас поки що немає оплачених замовлень.</p>
<?php else: ?>
    <?php
    $current_order_id = null;
    foreach ($orders as $order):
        // Виводимо новий блок замовлення
        if ($current_order_id !== $order['order_id']):
            if ($current_order_id !== null) echo "</table><hr>";
            $current_order_id = $order['order_id'];
            ?>
            <h2>Замовлення №<?= htmlspecialchars($order['order_id']) ?>, Сума - <?= htmlspecialchars($order['total']) ?>, <?= htmlspecialchars($order['date']) ?></h2>
            <table border="1">
            <thead>
            <tr>
                <th>Назва товару</th>
                <th>Кількість</th>
                <th>Ціна за одиницю</th>
                <th>Загальна вартість</th>
            </tr>
            </thead>
            <tbody>
        <?php endif; ?>
        <tr>
            <td><?= htmlspecialchars($order['name']) ?></td>
            <td><?= htmlspecialchars($order['q']) ?></td>
            <td><?= number_format($order['price_out'], 2) ?> грн</td>
            <td><?= number_format($order['q'] * $order['price_out'], 2) ?> грн</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
<?php endif; ?>
</body>
</html>

