<?php

require_once 'FileManager.php';

// Перевіряємо метод запису в файл
FileManager::writeFile('file1.txt', "\nДоданий текст в file1\n");

// Перевіряємо метод читання файлу
$content = FileManager::readFile('file1.txt');
if ($content !== null) {
    echo "Вміст file1.txt:<br>";
    // nl2br — Вставляє HTML-код розриву рядка перед кожним переведенням рядка \r\n
    echo nl2br($content) . "<br>";
}

// Перевіряємо метод очищення файлу
FileManager::clearFile('file1.txt');

// Перевіряємо вміст файлу після очищення
$content = FileManager::readFile('file1.txt');
if ($content !== null) {
    echo "Вміст file1.txt після очищення:<br>";
    echo nl2br($content) . "<br>";
}
