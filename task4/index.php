<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Завантажити Зображення</title>
</head>
<body>
    <h1>Форма для Завантаження Зображення</h1>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="image">Оберіть зображення:</label>
        <input type="file" id="image" name="image" accept="image/*" required>
        <br>
        <button type="submit">Завантажити</button>
    </form>
</body>
</html>
