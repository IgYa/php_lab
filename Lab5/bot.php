<?php
require 'vendor/autoload.php';
require 'db.php';

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

global $db;
//global $botToken;
$bot = new BotApi($_ENV['BOT_TOKEN']);

// Визначення початкового офсету
$offset = 0;

// Очистка черги старих повідомлень
try {
    $updates = $bot->getUpdates();
    if (!empty($updates)) {
        $offset = end($updates)->getUpdateId() + 1;
    }
    echo "Бот запущено...\n";
} catch (Exception $e) {
    error_log('Помилка при ініціалізації офсету: ' . $e->getMessage());
    exit;
}

while (true) {
    try {
        // Отримання оновлень з використанням офсету
        $updates = $bot->getUpdates($offset);

        foreach ($updates as $update) {
            if ($update->getMessage() !== null) {
                $message = $update->getMessage();

                if ($message->getText() === '/start') {
                    // Отримання даних користувача
                    $chatId = $message->getFrom()->getId();
                    $firstName = $message->getFrom()->getFirstName();
                    $lastName = $message->getFrom()->getLastName();
                    $username = $message->getFrom()->getUsername();

                    // Перевірка, чи є користувач у базі
                    $stmt = $db->prepare("SELECT * FROM users WHERE telegram_id = ?");
                    $stmt->execute([$chatId]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$user) {
                        // Реєстрація нового користувача
                        $stmt = $db->prepare("INSERT INTO users (telegram_id, username, name, surname) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$chatId, $username, $firstName, $lastName]);

                        $bot->sendMessage($chatId, "Вас зареєстровано! Якщо у вас немає username, поділіться своїм номером телефону.");
                    } else {
                        $bot->sendMessage($chatId, "Ви вже зареєстровані!");
                    }


                    // Відправка клавіатури з кнопкою "Поділитись номером телефону"
                    $keyboard = new ReplyKeyboardMarkup(
                        [
                            [
                                ['text' => 'Поділитись номером телефону', 'request_contact' => true],
                            ]
                        ],
                        true,
                        true
                    );
                    $bot->sendMessage($chatId, "Поділіться номером телефону, якщо у вас немає username.", null, false, null, $keyboard);
                }

                // Обробка отримання номера телефону
                if ($message->getContact()) {
                    $phone = $message->getContact()->getPhoneNumber();

                    // Перевіряємо, чи отримано номер телефону та chatId
                    if ($phone && $chatId) {
                        try {
                            // Оновлення номера телефону в базі
                            $stmt = $db->prepare("UPDATE users SET phone = ? WHERE telegram_id = ?");
                            $stmt->execute([$phone, $chatId]);

                            if ($stmt->rowCount() > 0) {
                                $bot->sendMessage($chatId, "Дякуємо! Ваш номер телефону збережено.");
                            } else {
                                $bot->sendMessage($chatId, "Не вдалося оновити номер телефону. Можливо, користувача з таким telegram_id не існує.");
                            }
                        } catch (Exception $e) {
                            $bot->sendMessage($chatId, "Сталася помилка: " . $e->getMessage());
                        }
                    } else {
                        $bot->sendMessage($chatId, "Не вдалося отримати номер телефону або chatId.");
                    }
                }
                    // Оновлення номера телефону в базі
                    // $stmt = $db->prepare("UPDATE users SET phone = ? WHERE telegram_id = ?");
                    // $stmt->execute([$phone, $chatId]);

                    // $bot->sendMessage($chatId, "Дякуємо! Ваш номер телефону збережено.");



                // Оновлення офсету
                $offset = $update->getUpdateId() + 1;
            }
        }

        // Затримка між циклами для зменшення навантаження
        sleep(2);
    } catch (Exception $e) {
        error_log('Помилка: ' . $e->getMessage());
        sleep(5); // Затримка у випадку помилки
    }
}

