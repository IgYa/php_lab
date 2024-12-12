<?php
require 'db.php';

global $db;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    //$email = $_POST['email'];
    $phone = $_POST['phone'];
    $birthday = $_POST['birthday'];
    //$telegram_username = $_POST['telegram_username'];
    //$telegram_id = $_POST['telegram_id'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    if (!$_POST['email']) { $email = NULL; }
    if (!$_POST['telegram_username']) { $telegram_username = NULL; }
    if (!$_POST['telegram_id']) { $telegram_id = NULL; }

    // Перевірка унікальності email, phone і telegram_id
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? OR phone = ? OR telegram_id = ?");
    $stmt->execute([$email, $phone, $telegram_id]);
    $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing_user) {
        die("User with this email, phone, or Telegram ID already exists.");
    }

    // Вставка нового користувача
    $stmt = $db->prepare("
        INSERT INTO users (name, surname, email, phone, birthday, username, telegram_id, password)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $success = $stmt->execute([$name, $surname, $email, $phone, $birthday, $telegram_username, $telegram_id, $password]);

    if ($success) {
        echo "Registration successful!";
    } else {
        echo "Failed to register.";
    }
}
?>
