<?php

require_once 'Student.php';
require_once 'Programmer.php';

$student = new Student(170, 60, 20, 'Университет XYZ', 2);
$programmer = new Programmer(180, 75, 25, ['PHP', 'JavaScript'], 5);

echo "Тест прибирання для студента:<br>";
$student->cleanRoom();
$student->cleanKitchen();

echo "<br>Тест прибирання для програміста:<br>";
$programmer->cleanRoom();
$programmer->cleanKitchen();
