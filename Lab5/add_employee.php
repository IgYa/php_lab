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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)$_POST['user_id'];
    $position = $_POST['position'];
    $salary = (float)$_POST['salary'];

    $stmt = $db->prepare("INSERT INTO employees (user_id, position, salary) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $position, $salary]);

    header('Location: employees.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Додати співробітника</title>
</head>
<body>
<h1>Додати співробітника</h1>

<form action="" method="post">
    <label for="user_id">ID користувача:</label>
    <input type="number" name="user_id" id="user_id" required><br><br>

    <label for="position">Посада:</label>
    <input type="text" name="position" id="position" required><br><br>

    <label for="salary">Зарплата:</label>
    <input type="number" name="salary" id="salary" step="0.01" required><br><br>

    <button type="submit">Додати</button>
</form>
</body>
</html>

