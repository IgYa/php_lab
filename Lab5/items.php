<?php
session_start();
require 'db.php';

global $db;

// Перевірка авторизації користувача
if (!isset($_SESSION['auth_expires']) || strtotime($_SESSION['auth_expires']) <= time()) {
    logout($db);
    header('Location: login_form.php');
    exit;
}

//if (!isset($_SESSION['is_authenticated']) || !$_SESSION['is_authenticated']) {
//    header('Location: login_form.php');
//    exit;
//}
//
//$stmt = $db->prepare("SELECT auth_expires FROM users WHERE id = ?");
//$stmt->execute([$_SESSION['user_id']]);
//$auth_expires = $stmt->fetchColumn();
//
//if (!$auth_expires || strtotime($auth_expires) <= time()) {
//    logout($db);
//    header('Location: login_form.php');
//    exit;
//}

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

// Додавання товару до кошика
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_basket'])) {
    $item_id = (int)$_POST['item_id'];
    $quantity = (int)$_POST['quantity'];

    // Перевірка наявності товару в кошику
    $stmt = $db->prepare("SELECT q FROM basket WHERE order_id = ? AND item_id = ?");
    $stmt->execute([$_SESSION['order_id'], $item_id]);
    $existing_quantity = $stmt->fetchColumn();

    if ($existing_quantity !== false) {
        // Якщо товар уже є у кошику, збільшуємо кількість
        $stmt = $db->prepare("UPDATE basket SET q = q + ? WHERE order_id = ? AND item_id = ?");
        $stmt->execute([$quantity, $_SESSION['order_id'], $item_id]);
    } else {
        // Якщо товару немає у кошику, додаємо новий запис
        $stmt = $db->prepare("INSERT INTO basket (order_id, item_id, q) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['order_id'], $item_id, $quantity]);
    }

    // Оновлення залишків на складі
    $stmt = $db->prepare("UPDATE items SET stock = stock - ? WHERE item_id = ?");
    $stmt->execute([$quantity, $item_id]);

    $success = "Товар додано до кошика.";
}

// Отримання списку товарів
$stmt = $db->query("SELECT item_id, name, (price * (extra / 100 + 1)) AS price_out, stock FROM items WHERE stock > 0");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Розрахунок підсумкової вартості кошика
$stmt = $db->prepare("SELECT SUM(price_out * q) AS total FROM basket b JOIN items i ON b.item_id = i.item_id WHERE b.order_id = ?");
$stmt->execute([$_SESSION['order_id']]);
$total = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Список товарів</title>
</head>
<body>
<h1>Список товарів</h1>

<?php if (isset($success)): ?>
    <p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<table border="1">
    <thead>
    <tr>
        <th>Назва</th>
        <th>Ціна</th>
        <th>Залишок</th>
        <th>Дії</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($items as $item): ?>
        <tr>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= number_format($item['price_out'], 2) ?> грн</td>
            <td><?= $item['stock'] ?></td>
            <td>
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="item_id" value="<?= $item['item_id'] ?>">
                    <input type="number" name="quantity" min="1" max="<?= $item['stock'] ?>" value="1" required>
                    <button type="submit" name="add_to_basket">Додати до кошика</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<h3>Підсумкова вартість кошика: <?= number_format($total ?? 0, 2) ?> грн</h3>
<a href="basket.php">Перейти до кошика</a>
<a href="index.php"><br> На головну <br></a>
</body>
</html>
