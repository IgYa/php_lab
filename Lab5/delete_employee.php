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

$stmt = $db->prepare("DELETE FROM employees WHERE employee_id = ?");
$stmt->execute([$employee_id]);

header('Location: employees.php');
exit;
?>

