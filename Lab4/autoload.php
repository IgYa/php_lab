<?php

/**
 * Функція автозавантаження класів із підтримкою неймспейсів.
 *
 * @param string $class Ім'я класу з неймспейсом.
 */
spl_autoload_register(function($class) {
    // Перетворюємо неймспейси на шлях
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    
    // Перевіряємо наявність файлу і підключаємо його
    if (file_exists($path)) {
        require_once $path;
    } else {
        echo "Не вдалося завантажити файл для класу: $class\n";
    }
});
