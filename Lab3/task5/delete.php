<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Видалити Папку</title>
</head>
<body>
    <h1>Видалити Папку</h1>
    <form action="delete.php" method="POST">
        <label for="login">Логін:</label>
        <input type="text" id="login" name="login" required>
        <br>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Видалити Папку</button>
    </form>

    <?php
    function deleteDirectory($dir) {
        if (!is_dir($dir)) {
            return;
        }
        
        $items = array_diff(scandir($dir), ['.', '..']);

        foreach ($items as $item) {
            $path = "$dir/$item";
            if (is_dir($path)) {
                deleteDirectory($path);
            } else {
                unlink($path);
            }
        }
        rmdir($dir);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = trim($_POST['login']);
        $password = trim($_POST['password']);

        if (is_dir($login)) {
            deleteDirectory($login);
            echo "<p>Папка '$login' успішно видалена!</p>";
        } else {
            echo "<p style='color: red;'>Помилка: Папка '$login' не існує.</p>";
        }
    }
    ?>
</body>
</html>
