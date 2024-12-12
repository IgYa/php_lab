<?php
session_start();
require 'db.php';

global $db;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (!$username && !$phone) {
        die("Please provide username or phone number.");
    }

    // Отримуємо користувача
    $user = getUser($username, $phone, $db);

    if (!$user) {
        die("User not found.");
    }

    if (isset($_POST['send_code'])) {
        // Вхід через Telegram-код
        sendAuthCode($user['telegram_id'], $_ENV['BOT_TOKEN'], $db);
        echo "Authentication code sent to Telegram.";
        exit;

    } elseif (isset($_POST['login'])) {
        // Вхід через пароль
        $password = trim($_POST['password'] ?? '');
        $auth_code = trim($_POST['auth_code'] ?? '');

        if ($password) {
            // Перевірка пароля
            if (!password_verify($password, $user['password'])) {
                die("Invalid password.");
            }

            // Успішний вхід через пароль
        } elseif ($auth_code) {
            // Перевірка Telegram-коду
            if ($user['auth_code'] !== $auth_code) {
                die("Invalid authentication code.");
            }
        } else {
            die("Please provide a password or authentication code.");
        }

        // Встановлення терміну авторизації, скидання auth_code
        date_default_timezone_set('Europe/Kyiv');
        $auth_expires = date('Y-m-d H:i:s', strtotime('+2 hours'));
        $stmt = $db->prepare("UPDATE users SET is_authenticated = 1, auth_code = NULL, auth_expires = ? WHERE id = ?");
        $stmt->execute([$auth_expires, $user['id']]);

        // Збереження даних у сесію
        $_SESSION['is_authenticated'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['auth_expires'] = $auth_expires;

        header("Location: index.php");
        exit;
    }
}
