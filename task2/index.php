<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($login === 'admin' && $password === 'password') {
        $_SESSION['user'] = $login;
    } else {
        $error = 'Невірний логін або пароль.';
    }
}

if (isset($_SESSION['user'])) {
    $welcomeMessage = "Добрий день, " . $_SESSION['user'] . "!";
} else {
    $welcomeMessage = '';
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизація</title>
</head>
<body>
    <h1>Авторизація</h1>
    
    <?php if ($welcomeMessage): ?>
        <p><?php echo $welcomeMessage; ?></p>
        <a href="logout.php">Вийти</a> 
    <?php else: ?>
        <form method="POST" action="">
            <label for="login">Логін:</label>
            <input type="text" id="login" name="login" required>
            <br>
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
            <br>
            <button type="submit">Увійти</button>
        </form>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
