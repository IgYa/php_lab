<?php
$filename = 'comments.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $comment = htmlspecialchars(trim($_POST['comment']));
    
    $data = "$name: $comment\n";

    file_put_contents($filename, $data, FILE_APPEND);
}

function readComments($filename) {
    if (!file_exists($filename)) {
        return [];
    }
    
    $comments = [];
    $handle = fopen($filename, 'r');
    while (($line = fgets($handle)) !== false) {
        $comments[] = $line;
    }
    fclose($handle);
    return $comments;
}

$comments = readComments($filename);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Коментарі</title>
</head>
<body>
    <h1>Залиште коментар</h1>
    
    <form method="POST" action="">
        <label for="name">Ім’я:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="comment">Коментар:</label>
        <textarea id="comment" name="comment" required></textarea>
        <br>
        <button type="submit">Відправити</button>
    </form>

    <h2>Коментарі:</h2>
    <table border="1">
        <tr>
            <th>Ім’я</th>
            <th>Коментар</th>
        </tr>
        <?php foreach ($comments as $line): ?>
            <?php list($name, $comment) = explode(': ', $line, 2); ?>
            <tr>
                <td><?php echo htmlspecialchars($name); ?></td>
                <td><?php echo htmlspecialchars($comment); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
