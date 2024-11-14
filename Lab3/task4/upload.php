<?php
$targetDirectory = 'uploads/';

if (!is_dir($targetDirectory)) {
    mkdir($targetDirectory, 0755, true);
}

$targetFile = $targetDirectory . basename($_FILES['image']['name']);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Перевіряємо, чи є файл зображення за допомогою Fileinfo
if (isset($_POST['submit'])) {
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $_FILES['image']['tmp_name']);
    finfo_close($finfo);

    if (in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif'])) {
        echo "Файл є зображенням - " . $mimeType . ".";
        $uploadOk = 1;
    } else {
        echo "Файл не є зображенням.";
        $uploadOk = 0;
    }
}

if (file_exists($targetFile)) {
    echo "Вибачте, файл вже існує.";
    $uploadOk = 0;
}

if ($_FILES['image']['size'] > 5000000) {
    echo "Вибачте, ваш файл занадто великий.";
    $uploadOk = 0;
}

if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
    echo "Вибачте, лише файли формату JPG, JPEG, PNG & GIF дозволені.";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo "Вибачте, ваш файл не був завантажений.";
} else {
    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
        echo "Файл " . htmlspecialchars(basename($_FILES['image']['name'])) . " успішно завантажено.";
    } else {
        echo "Вибачте, сталася помилка під час завантаження вашого файлу.";
    }
}
?>
