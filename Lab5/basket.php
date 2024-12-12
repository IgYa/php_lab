<?php
session_start();
require 'db.php';

global $db;

// Перевірка авторизації та терміну дії сесії
if (!isset($_SESSION['is_authenticated']) || !$_SESSION['is_authenticated']) {
    header('Location: login_form.php');
    exit;
}

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
    $stmt = $db->prepare("SELECT order_id FROM orders WHERE user_id = ? AND pay = 0 LIMIT 1");
    $stmt->execute([$_SESSION['user_id']]);
    $existing_order = $stmt->fetchColumn();

    if ($existing_order) {
        $_SESSION['order_id'] = $existing_order;
    } else {
        $stmt = $db->prepare("INSERT INTO orders (user_id) VALUES (?)");
        $stmt->execute([$_SESSION['user_id']]);
        $_SESSION['order_id'] = $db->lastInsertId();
    }
}

// Обробка дій у кошику
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantity'])) {
        $order_id = $_SESSION['order_id'];
        $item_id = (int)$_POST['item_id'];
        $quantity = (int)$_POST['quantity'];

        // Отримання попередньої кількості
        $stmt = $db->prepare("SELECT q FROM basket WHERE order_id = ? AND item_id = ?");
        $stmt->execute([$order_id, $item_id]);
        $current_quantity = $stmt->fetchColumn();

        if ($current_quantity !== false) {
            $difference = $quantity - $current_quantity;

            // Оновлення кількості товару в кошику
            $stmt = $db->prepare("UPDATE basket SET q = ? WHERE order_id = ? AND item_id = ?");
            $stmt->execute([$quantity, $order_id, $item_id]);

            // Оновлення залишків на складі
            $stmt = $db->prepare("UPDATE items SET stock = stock - ? WHERE item_id = ?");
            $stmt->execute([$difference, $item_id]);

            $success = "Кількість оновлено.";
        }
    } elseif (isset($_POST['remove_item'])) {
        $order_id = $_SESSION['order_id'];
        $item_id = (int)$_POST['item_id'];

        // Отримання кількості
        $stmt = $db->prepare("SELECT q FROM basket WHERE order_id = ? AND item_id = ?");
        $stmt->execute([$order_id, $item_id]);
        $quantity = $stmt->fetchColumn();

        if ($quantity !== false) {
            // Видалення товару з кошика
            $stmt = $db->prepare("DELETE FROM basket WHERE order_id = ? AND item_id = ?");
            $stmt->execute([$order_id, $item_id]);

            // Повернення залишків на склад
            $stmt = $db->prepare("UPDATE items SET stock = stock + ? WHERE item_id = ?");
            $stmt->execute([$quantity, $item_id]);

            $success = "Товар видалено з кошика.";
        }
    }
}

// Отримання товарів у кошику
$stmt = $db->prepare(
    "SELECT b.item_id, i.name, b.q, 
            (i.price * (i.extra / 100 + 1)) AS price_out, 
            (b.q * (i.price * (i.extra / 100 + 1))) AS total_price 
     FROM basket b 
     JOIN items i ON b.item_id = i.item_id 
     WHERE b.order_id = ?"
);
$stmt->execute([$_SESSION['order_id']]);
$basket_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Розрахунок підсумкової вартості
$total = array_sum(array_column($basket_items, 'total_price'));
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Кошик</title>
</head>
<body>
<h1>Кошик</h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if (isset($success)): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<table border="1">
    <thead>
    <tr>
        <th>Назва</th>
        <th>Кількість</th>
        <th>Ціна за одиницю</th>
        <th>Загальна вартість</th>
        <th>Дії</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($basket_items as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
                    <input type="number" name="quantity" min="1" value="<?= $item['q'] ?>" required>
                    <button type="submit" name="update_quantity">Оновити</button>
                </form>
            </td>
            <td><?= number_format($item['price_out'], 2) ?> грн</td>
            <td><?= number_format($item['total_price'], 2) ?> грн</td>
            <td>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
                    <button type="submit" name="remove_item">Видалити</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<h3>Підсумкова вартість: <?= number_format($total, 2) ?> грн</h3>

<a href="items.php">Продовжити покупки</a>
<a href="checkout.php">Оформити замовлення</a>
</body>
</html>
