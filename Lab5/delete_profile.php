<?php
session_start();
require 'db.php';

global $db;

// Перевірка авторизації
if (!isset($_SESSION['auth_expires']) || strtotime($_SESSION['auth_expires']) <= time()) {
    logout($db);
    header('Location: login_form.php');
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Перевірка завершених замовлень
    $stmt = $db->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND pay = 1");
    $stmt->execute([$userId]);
    $completedOrders = $stmt->fetchColumn();

    if ($completedOrders > 0) {
        echo "Не можливо видалити, бо цей користувач має оплачені замовлення. <a href='index.php'>Go back</a>";
        exit;
    }

    // Перевірка співробітників
    $stmt = $db->prepare("SELECT COUNT(*) FROM employees WHERE user_id = ?");
    $stmt->execute([$userId]);
    $completedOrders = $stmt->fetchColumn();

    if ($completedOrders > 0) {
        echo "Не можливо видалити, бо під цим user.id оформлен співробітник <a href='index.php'>Go back</a>";
        exit;
    }

    // Видалення незавершених замовлень
    $stmt = $db->prepare("DELETE FROM orders WHERE user_id = ? AND pay = 0");
    $stmt->execute([$userId]);

    // Видалення профілю
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$userId]);

    // Вихід із системи
    logout($db);
    echo "Your profile and any incomplete orders have been deleted. <a href='index.php'>Go to Home</a>";
    exit;
}
?>

<form method="POST">
    <h3>Are you sure you want to delete your profile?</h3>
    <p>If you have any completed orders, your profile cannot be deleted.</p>
    <button type="submit">Yes, delete my profile</button>
    <a href="index.php">Cancel</a>
</form>
