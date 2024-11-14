<?php

require_once 'Student9.php';
require_once 'Programmer9.php';

// Создаем объект Student и вызываем метод народження дитини
$student = new Student(170, 60, 20, 'Университет XYZ', 2);
echo "Тест для студента:<br>";
$student->birthChild();

echo "<br>";

// Создаем объект Programmer и вызываем метод народження дитини
$programmer = new Programmer(180, 75, 25, ['PHP', 'JavaScript'], 5);
echo "Тест для програміста:<br>";
$programmer->birthChild();
