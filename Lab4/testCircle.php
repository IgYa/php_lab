<?php

require_once 'Circle5.php';

// Створюємо об'єкт кола з початковими значеннями для центру і радіуса
$circle = new Circle(5, 10, 25);

// Виводимо об'єкт як рядок (метод __toString())
echo $circle . "<br>";

// Перевіряємо методи GET
echo "Координата центру X: " . $circle->getX() . "<br>";
echo "Координата центру Y: " . $circle->getY() . "<br>";
echo "Радіус: " . $circle->getRadius() . "<br>";

// Перевіряємо методи SET
$circle->setX(20);
$circle->setY(25);
$circle->setRadius(50);

// Перевіряємо оновлені значення
echo "Оновлені координати та радіус:<br>";
echo $circle . "<br>";
