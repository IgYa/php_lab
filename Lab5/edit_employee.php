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

$employee_id = (int)$_GET['id'];

// Отримання поточного запису
$stmt = $db->prepare("SELECT * FROM employees WHERE employee_id = ?");
$stmt->execute([$employee_id]);
$employee = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $position = $_POST['position'];
    $salary = (float)$_POST['salary'];

    $stmt = $db->prepare("UPDATE employees SET position = ?, salary = ? WHERE employee_id = ?");
    $stmt->execute([$position, $salary, $employee_id]);

    header('Location: employees.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Редагувати співробітника</title>
</head>
<body>
<h1>Редагувати співробітника</h1>

<form action="" method="post">
    <label for="position">Посада:</label>
    <input type="text" name="position" id="position" value="<?= htmlspecialchars($employee['position']) ?>" required><br><br>

    <label for="salary">Зарплата:</label>
    <input type="number" name="salary" id="salary" step="0.01" value="<?= htmlspecialchars($employee['salary']) ?>" required><br><br>

    <button type="submit">Зберегти</button>
</form>
</body>
</html>

