<?php
if (isset($_GET['font_size'])) {
    $fontSize = $_GET['font_size'];
    setcookie('fontSize', $fontSize, time() + (24*3600 * 7), "/");
} else {
    $fontSize = isset($_COOKIE['fontSize']) ? $_COOKIE['fontSize'] : 'medium'; 
}

switch ($fontSize) {
    case 'large':
        $fontStyle = '2em';
        break;
    case 'small':
        $fontStyle = '0.6em';
        break;
    default:
        $fontStyle = '1em';
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Зміна розміру шрифту</title>
    <style>
        body {
            font-size: <?php echo $fontStyle; ?>;
        }
    </style>
</head>
<body>
    <h1>Зміна розміру шрифту</h1>
    <p>Виберіть розмір шрифту:</p>
    <a href="?font_size=large">Великий шрифт</a> | 
    <a href="?font_size=medium">Середній шрифт</a> | 
    <a href="?font_size=small">Маленький шрифт</a>
</body>
</html>

