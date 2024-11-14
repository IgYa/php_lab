<?php

/**
 * Class FileManager
 *
 * Клас для роботи з файлами в директорії "text".
 */
class FileManager {
    /**
     * @var string Директорія для зберігання файлів
     */
    private static $dir = 'text';

    /**
     * Статичний метод для читання вмісту файлу.
     *
     * @param string $filename Ім'я файлу для читання.
     * @return string|null Вміст файлу або null, якщо файл не знайдено.
     */
    public static function readFile($filename) {
        $path = self::$dir . DIRECTORY_SEPARATOR . $filename;
        if (file_exists($path)) {
            return file_get_contents($path);
        }
        echo "Файл '$filename' не знайдено.<br>";
        return null;
    }

    /**
     * Статичний метод для запису рядка у файл.
     *
     * @param string $filename Ім'я файлу для запису.
     * @param string $content Рядок, який потрібно дописати у файл.
     * @return void
     */
    public static function writeFile($filename, $content) {
        $path = self::$dir . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($path, $content, FILE_APPEND);
        echo "Рядок додано до файлу '$filename'.<br>";
    }

    /**
     * Статичний метод для очищення вмісту файлу.
     *
     * @param string $filename Ім'я файлу для очищення.
     * @return void
     */
    public static function clearFile($filename) {
        $path = self::$dir . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($path, ''); // Замінюємо вміст на порожній рядок
        echo "Файл '$filename' очищений.<br>";
    }
}
