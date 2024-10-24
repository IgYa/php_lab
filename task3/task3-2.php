<?php
$file1 = 'file1.txt';
$file2 = 'file2.txt';

$words1 = array_filter(explode(' ', file_get_contents($file1)));
$words2 = array_filter(explode(' ', file_get_contents($file2)));

$uniqueToFirst = array_diff($words1, $words2);
$commonWords = array_intersect($words1, $words2);
$moreThanTwo = array_filter(array_count_values($words1), function($count) use ($words2) {
    return $count > 2 && in_array($count, array_count_values($words2));
});

file_put_contents('unique_to_first.txt', implode("\n", $uniqueToFirst));
file_put_contents('common_words.txt', implode("\n", $commonWords));
file_put_contents('more_than_two.txt', implode("\n", array_keys($moreThanTwo)));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fileToDelete = $_POST['file_name'] ?? '';
    if (file_exists($fileToDelete) && unlink($fileToDelete)) {
        $message = "Файл '$fileToDelete' успішно видалено.";
    } else {
        $message = "Не вдалося видалити файл '$fileToDelete'.";
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обробка файлів</title>
</head>
<body>
    <h1>Обробка файлів</h1>

    <h2>Були створені такі файли:</h2>
    <ul>
        <li>unique_to_first.txt</li>
        <li>common_words.txt</li>
        <li>more_than_two.txt</li>
    </ul>
    
    <h2>Видалити файл</h2>
    <form method="POST" action="">
        <label for="file_name">Введіть ім'я файлу:</label>
        <input type="text" id="file_name" name="file_name" required>
        <button type="submit">Видалити</button>
    </form>
    
    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    

</body>
</html>
