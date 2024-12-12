<?php
session_start();
require 'db.php';

global $db;
global $adminList;

// Перевірка, чи користувач є співробітником
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

// Отримання всіх співробітників
$stmt = $db->query("SELECT e.employee_id, u.surname, u.name, e.position, e.salary 
                    FROM employees e 
                    JOIN users u ON e.user_id = u.id");
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Список співробітників</title>
</head>
<body>
<h1>Список співробітників</h1>

<table border="1">
    <thead>
    <tr>
        <th>ID</th>
        <th>Прізвище</th>
        <th>Ім'я</th>
        <th>Посада</th>
        <th>Зарплата</th>
        <th>Дії</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($employees as $employee): ?>
        <tr>
            <td><?= htmlspecialchars($employee['employee_id']) ?></td>
            <td><?= htmlspecialchars($employee['surname']) ?></td>
            <td><?= htmlspecialchars($employee['name']) ?></td>
            <td><?= htmlspecialchars($employee['position']) ?></td>
            <td><?= number_format($employee['salary'], 2) ?> грн</td>
            <td>
                <a href="edit_employee.php?id=<?= $employee['employee_id'] ?>">Редагувати</a> |
                <a href="delete_employee.php?id=<?= $employee['employee_id'] ?>"
                   onclick="return confirm('Ви впевнені, що хочете видалити цього співробітника?')">Видалити</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<a href="add_employee.php"><br> Додати співробітника <br></a>
<a href="index.php"><br> На головну <br></a>
</body>
</html>
