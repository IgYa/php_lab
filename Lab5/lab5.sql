-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Час створення: Гру 12 2024 р., 20:49
-- Версія сервера: 8.0.40
-- Версія PHP: 8.2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `lab5`
--

-- --------------------------------------------------------

--
-- Структура таблиці `basket`
--

CREATE TABLE `basket` (
  `order_id` int NOT NULL,
  `item_id` int NOT NULL,
  `q` int NOT NULL,
  `price_out` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `basket`
--

INSERT INTO `basket` (`order_id`, `item_id`, `q`, `price_out`) VALUES
(1, 1, 1, 32500),
(1, 7, 1, 52000),
(2, 1, 1, 32500),
(2, 4, 1, 45500),
(2, 5, 1, 13000),
(3, 3, 1, 48100),
(3, 6, 1, 39000),
(6, 5, 1, 13000);

--
-- Тригери `basket`
--
DELIMITER $$
CREATE TRIGGER `order_total_after_ins` AFTER INSERT ON `basket` FOR EACH ROW BEGIN
    DECLARE total_order INT;

    -- Обчислюємо загальну суму замовлення
    SELECT SUM(q * price_out) INTO total_order
    FROM basket
    WHERE order_id = NEW.order_id;

    -- Оновлюємо таблицю orders
    UPDATE orders
    SET total = total_order
    WHERE order_id = NEW.order_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `order_total_after_update` AFTER UPDATE ON `basket` FOR EACH ROW BEGIN
    DECLARE total_order INT;

    SELECT SUM(q * price_out) INTO total_order
    FROM basket
    WHERE order_id = NEW.order_id;

    UPDATE orders
    SET total = total_order
    WHERE order_id = NEW.order_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `order_total_del` AFTER DELETE ON `basket` FOR EACH ROW BEGIN
    DECLARE total_order INT;

    SELECT SUM(q * price_out) INTO total_order
    FROM basket
    WHERE order_id = OLD.order_id;

    UPDATE orders
    SET total = total_order
    WHERE order_id = OLD.order_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `set_price_out` BEFORE INSERT ON `basket` FOR EACH ROW BEGIN
    DECLARE item_price INT;
    DECLARE item_extra INT;

    -- Отримуємо значення price і extra для товару
    SELECT price, extra 
    INTO item_price, item_extra
    FROM items
    WHERE item_id = NEW.item_id;

    -- чи існує товар із заданим item_id
    IF item_price IS NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Invalid item_id: Item not found';
    END IF;

    -- Розраховуємо price_out
    SET NEW.price_out = item_price * (item_extra / 100 + 1);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблиці `employees`
--

CREATE TABLE `employees` (
  `employee_id` int NOT NULL,
  `position` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary` decimal(8,2) NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `employees`
--

INSERT INTO `employees` (`employee_id`, `position`, `salary`, `user_id`) VALUES
(1, 'адмін', 25000.00, 10),
(3, 'Директор', 35000.00, 12),
(4, 'Менеджер', 20000.00, 13),
(5, 'Бухгалтер', 25000.00, 14),
(6, 'Менеджер', 17000.00, 15),
(7, 'Менеджер', 18000.00, 16),
(8, 'Бухгалтер', 21500.00, 11);

-- --------------------------------------------------------

--
-- Структура таблиці `items`
--

CREATE TABLE `items` (
  `item_id` int NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `extra` int NOT NULL DEFAULT '30',
  `stock` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `items`
--

INSERT INTO `items` (`item_id`, `name`, `price`, `extra`, `stock`) VALUES
(1, 'Телевизор', 25000, 30, 4),
(2, 'Телефон', 15000, 30, 20),
(3, 'Телевизор 55\"', 37000, 30, 4),
(4, 'iPhone', 35000, 30, 19),
(5, 'Планшет', 10000, 30, 18),
(6, 'Компьютер', 30000, 30, 18),
(7, 'Ноутбук', 40000, 30, 19);

-- --------------------------------------------------------

--
-- Структура таблиці `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` int DEFAULT NULL,
  `pay` tinyint(1) DEFAULT '0',
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `orders`
--

INSERT INTO `orders` (`order_id`, `date`, `total`, `pay`, `user_id`) VALUES
(1, '2024-12-11 20:09:37', 84500, 1, 10),
(2, '2024-12-11 21:50:45', 91000, 1, 10),
(3, '2024-12-11 22:30:01', 87100, 1, 10),
(4, '2024-12-11 22:32:40', NULL, 0, 10),
(5, '2024-12-12 16:17:06', NULL, 0, 15),
(6, '2024-12-12 20:41:31', 13000, 1, 19),
(7, '2024-12-12 20:41:47', NULL, 0, 19);

-- --------------------------------------------------------

--
-- Структура таблиці `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `telegram_id` bigint DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surname` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` char(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `auth_code` char(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_authenticated` tinyint(1) DEFAULT '0',
  `auth_expires` datetime DEFAULT NULL,
  `password` char(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп даних таблиці `users`
--

INSERT INTO `users` (`id`, `telegram_id`, `username`, `name`, `surname`, `email`, `phone`, `birthday`, `auth_code`, `is_authenticated`, `auth_expires`, `password`) VALUES
(10, 1327984097, 'Ihor_master', 'Ihor', 'Yakunin', NULL, '+380123456789', NULL, NULL, 1, '2024-12-11 22:08:50', NULL),
(11, 7152702466, NULL, 'Олена', 'Брунько', NULL, '+123456789012', NULL, NULL, 0, NULL, NULL),
(12, 7452636415, 'ihor', 'Ігор', 'Якунін', '1234@ukr.net', '+380123456777', '2024-12-12', NULL, 0, NULL, NULL),
(13, NULL, NULL, 'Василь', 'Петрунько', NULL, '+380123456781', '2024-12-09', NULL, 0, NULL, '$2y$10$br1YdB0jv5rp3PeARV/Op.zgqpbBDUqSM3UvxjpREB0yZFGmlKeGi'),
(14, NULL, NULL, 'Оксана', 'Петрунько', NULL, '+380123456782', '2024-12-09', NULL, 0, NULL, '$2y$10$a4hX60FwHOqR.AbRkebGCOrRDEaWJV3OHuOp7wrQZdOGLvjoTB6Ri'),
(15, NULL, NULL, 'Петро', 'Васильченко', NULL, '+380123456783', '2024-12-01', NULL, 0, NULL, '$2y$10$E6HaKLRSpIBHS53FtafDAOaPS0FmH8nRKgWIvs0JmLCQbNkDJESpm'),
(16, NULL, NULL, 'Іван', 'Дудко', NULL, '+380123456784', '2024-11-07', NULL, 0, NULL, '$2y$10$eIE85dYW141yJklmh0P78OpSKjTeQiBHbd7aClHNRt7U3KJWHb8L6'),
(17, NULL, NULL, 'Лера', 'Керова', NULL, '+380123456785', '2024-10-29', NULL, 0, NULL, '$2y$10$apkEslWcBc43OyFUhD6Ple34qFAHtfoTCA17moDmYs.O5e4axvEIm'),
(18, NULL, NULL, 'Яна', 'Василішина', NULL, '+380123456786', '2024-10-29', NULL, 0, NULL, '$2y$10$U6ej7M55xMm8a6YYyW47k.Iw1zrpHu1QLp9n40ohesucYAftxyVZe'),
(19, NULL, NULL, 'Степан', 'Василішин', NULL, '+380123456787', '2024-10-29', NULL, 1, '2024-12-12 22:35:00', '$2y$10$0BDpKkhPjX2Niry3BslS4O3vaut9jgK.yG10SC8c.rLu0vQ7KcNFG');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `basket`
--
ALTER TABLE `basket`
  ADD PRIMARY KEY (`order_id`,`item_id`),
  ADD KEY `fk_basket_items` (`item_id`);

--
-- Індекси таблиці `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `fk_employees_users` (`user_id`);

--
-- Індекси таблиці `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `idx_items_name` (`name`);

--
-- Індекси таблиці `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_orders_users` (`user_id`),
  ADD KEY `idx_orders_date` (`date`);

--
-- Індекси таблиці `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `telegram_id` (`telegram_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phone` (`phone`),
  ADD UNIQUE KEY `uniq_users_username` (`username`),
  ADD KEY `idx_users_surname` (`surname`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `employees`
--
ALTER TABLE `employees`
  MODIFY `employee_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблиці `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблиці `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблиці `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `basket`
--
ALTER TABLE `basket`
  ADD CONSTRAINT `fk_basket_items` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_basket_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `fk_employees_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Обмеження зовнішнього ключа таблиці `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
