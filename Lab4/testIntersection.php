<?php

require_once 'Circle.php';

// Створіть два об'єкти Circle
$circle1 = new Circle(0, 0, 5);
$circle2 = new Circle(3, 4, 2);

// Виводимо інформацію про кола
echo $circle1 . "<br>";
echo $circle2 . "<br>";

// Перевіряємо, чи перетинаються кола
if ($circle1->intersects($circle2)) {
    echo "Кола перетинаються.<br>";
} else {
    echo "Кола не перетинаються.<br>";
}

// Перевіряємо інший випадок, коли кола не перетинаються
$circle3 = new Circle(10, 10, 2);
echo $circle3 . "<br>";
if ($circle1->intersects($circle3)) {
    echo "Кола перетинаються.<br>";
} else {
    echo "Кола не перетинаються.<br>";
}
