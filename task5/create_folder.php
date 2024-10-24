<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Створення Папки</title>
</head>
<body>
    <h1>Створити Папку</h1>
    <form action="create_folder.php" method="POST">
        <label for="login">Логін:</label>
        <input type="text" id="login" name="login" required>
        <br>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Створити Папку</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = trim($_POST['login']);
        $password = trim($_POST['password']);
        
        if (!is_dir($login)) {
            mkdir($login, 0755, true);

            mkdir("$login/video", 0755, true);
            mkdir("$login/music", 0755, true);
            mkdir("$login/photo", 0755, true);

            file_put_contents("$login/video/video1.mp4", "Це тестове відео.");
            file_put_contents("$login/music/music1.mp3", "Це тестова музика.");
            file_put_contents("$login/photo/photo1.jpg", "Це тестове зображення.");

            echo "<p>Папка '$login' успішно створена!</p>";
        } else {
            echo "<p style='color: red;'>Помилка: Папка '$login' вже існує.</p>";
        }
    }
    ?>
</body>
</html>
