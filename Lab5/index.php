<?php
session_start();
require 'db.php';

global $db;
global $adminList;

if (isset($_SESSION['is_authenticated']) && $_SESSION['is_authenticated']) {
    $stmt = $db->prepare("SELECT auth_expires FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $auth_expires = $stmt->fetchColumn();

    if ($auth_expires && strtotime($auth_expires) > time()) {
        echo "<h2>Welcome, {$_SESSION['user_name']}!</h2>";
        echo '<a href="items.php">Список Товарів</a> | <a href="basket.php">Кошик</a> | <a href="orders.php">Мої замовлення</a>';
        echo '. . . . . . . . . . . ';
        echo '<a href="edit_profile.php">Edit Profile</a> | <a href="logout.php">Logout</a> | <a href="delete_profile.php">Delete Profile<br><br></a>';
        // Перевірка, чи користувач є адміністратором
        $userId = $_SESSION['user_id'];
        if (in_array($userId, $adminList)) {
            echo '<a href="employees.php">Співробітники<br><br></a>';
            echo '<a href="statistics.php">Статистика<br><br></a>';
        }

    } else {
        logout($db);
        echo "<p>Your session has expired. Please <a href='login_form.php'>log in again</a>.</p>";
    }
} else {
    echo '<a href="login_form.php">Login</a> | <a href="register_form.html">Register</a>';
    echo "<p>Пропоную скористатись сервісом спрощеної реєстрації, просто зайдить до <a href='https://t.me/ihor_yakunin_bot'>мого бота</a>, та розпочніть роботу.<br>
          Якщо у Вас є username, то більш нічого не треба робити, Ви зареєстровані.<br>
          Але, якщо є бажання, можете поділитись номером телефону і у Вас буде можливість заходити на сайт як по username, так і по номеру телефону<br>
          Якщо немає username, то натисніть кнопку 'Поділитись номером телефону'.</p>";
}


?>