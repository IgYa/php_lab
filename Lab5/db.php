<?php
require_once 'vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Дані для підключення до бази даних
$dbHost = $_ENV['DB_HOST'];
$dbName = $_ENV['DB_NAME'];
$dbUser = $_ENV['DB_USER'];
$dbPass = $_ENV['DB_PASSWORD'];
$bot_token = $_ENV['BOT_TOKEN'];
$admin = $_ENV['ADMIN'] ?? '';

$adminList = explode(',', $admin);

try {
    $db = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8", $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}


function sendAuthCode($telegram_id, $bot_token, $db) {
    $auth_code = rand(100000, 999999); // Генерація 6-значного коду

    // Збереження коду в базу даних
    $stmt = $db->prepare("UPDATE users SET auth_code = ? WHERE telegram_id = ?");
    $stmt->execute([$auth_code, $telegram_id]);

    // Відправка коду через Telegram
    $message = "Your authentication code: $auth_code";
    $url = "https://api.telegram.org/bot$bot_token/sendMessage?chat_id=$telegram_id&text=" . urlencode($message);

    file_get_contents($url);
}

function logout($db){
    // Перевіряємо, чи користувач залогінений
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];

        // Оновлюємо статус в базі даних
        $stmt = $db->prepare("UPDATE users SET is_authenticated = 0, auth_expires = NULL WHERE id = ?");
        $stmt->execute([$user_id]);
    }

    // Видаляємо всі дані сесії
    session_unset();
    session_destroy();
}

function getUser($username, $phone, $db)
{
    // Перевіряємо, чи введено username або номер телефону
    if (empty($username) && empty($phone)) {
        die("Please provide either a username or a phone number.");
    }

    // Шукаємо користувача за username або номером телефону
    $query = "SELECT * FROM users WHERE ";
    $params = [];
    if (!empty($username)) {
        $query .= "username = ?";
        $params[] = $username;
    } elseif (!empty($phone)) {
        $query .= "phone = ?";
        $params[] = $phone;
    }

    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("User not found in the database.");
    }
    return $user;
}
?>