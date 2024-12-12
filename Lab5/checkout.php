<?php
session_start();
require 'db.php';

global $db;

// Перевірка авторизації користувача
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

// Перевірка наявності відкритого замовлення
if (!isset($_SESSION['order_id'])) {
    header('Location: items.php');
    exit;
}

// Отримання товарів у кошику
$stmt = $db->prepare(
    "SELECT i.name, b.q, (i.price * (i.extra / 100 + 1)) AS price_out, 
    (b.q * (i.price * (i.extra / 100 + 1))) AS total_price 
    FROM basket b 
    JOIN items i ON b.item_id = i.item_id 
    WHERE b.order_id = ?"
);
$stmt->execute([$_SESSION['order_id']]);
$basket_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Розрахунок підсумкової вартості
$total = array_sum(array_column($basket_items, 'total_price'));

// Обробка підтвердження замовлення
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    // Оновлення статусу замовлення
    $stmt = $db->prepare("UPDATE orders SET pay = 1, date = NOW() WHERE order_id = ?");
    $stmt->execute([$_SESSION['order_id']]);

    // Очистка кошика
    //$stmt = $db->prepare("DELETE FROM basket WHERE order_id = ?");
    //$stmt->execute([$_SESSION['order_id']]);

    // Видалення order_id із сесії
    unset($_SESSION['order_id']);

    $success = "Замовлення успішно оформлено!";
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Оформлення замовлення</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1>Оформлення замовлення</h1>

<?php if (isset($success)): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
    <a href="index.php">Повернутися на головну</a> <a href="items.php">Список Товарів</a>
    <?php exit; ?>
<?php endif; ?>

<table border="1">
    <thead>
    <tr>
        <th>Назва</th>
        <th>Кількість</th>
        <th>Ціна за одиницю</th>
        <th>Загальна вартість</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($basket_items as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= $item['q'] ?></td>
            <td><?= number_format($item['price_out'], 2) ?> грн</td>
            <td><?= number_format($item['total_price'], 2) ?> грн</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<h3>Підсумкова вартість: <?= number_format($total, 2) ?> грн</h3>

<form method="POST">
    <button type="submit" name="confirm_order">Підтвердити замовлення</button>
</form>

<a href="basket.php">Повернутися до кошика</a>
</body>
</html>
