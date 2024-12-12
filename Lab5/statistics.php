<?php
session_start();
require 'db.php';

global $db;
global $adminList;

// Перевірка, чи користувач є адміністратором
$userId = $_SESSION['user_id'];
if (!in_array($userId, $adminList)) {
    header('Location: index.php');
    exit;
}

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

// Отримання статистики
$stats = $db->query("
    SELECT 
        COUNT(*) AS total_employees,
        AVG(salary) AS avg_salary,
        MIN(salary) AS min_salary,
        MAX(salary) AS max_salary
    FROM employees
")->fetch(PDO::FETCH_ASSOC);

// Кількість працівників на кожній посаді
$positions = $db->query("
    SELECT position, COUNT(*) AS count 
    FROM employees 
    GROUP BY position
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Статистика</title>
</head>
<body>
<a href="index.php"><br> На головну <br></a>
<h1>Статистика працівників</h1>

<p>Загальна кількість працівників: <?= $stats['total_employees'] ?></p>
<p>Середня зарплата: <?= number_format($stats['avg_salary'], 2) ?> грн</p>
<p>Мінімальна зарплата: <?= number_format($stats['min_salary'], 2) ?> грн</p>
<p>Максимальна зарплата: <?= number_format($stats['max_salary'], 2) ?> грн</p>

<h2>Кількість працівників за посадами:</h2>
<table border="1">
    <thead>
    <tr>
        <th>Посада</th>
        <th>Кількість</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($positions as $position): ?>
        <tr>
            <td><?= htmlspecialchars($position['position']) ?></td>
            <td><?= htmlspecialchars($position['count']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>

