<?php

require_once 'Student.php';
require_once 'Programmer.php';

// Створюємо об'єкт Student
$student = new Student(170, 60, 20, 'Университет XYZ', 2);
echo "Студент на курсі: " . $student->getCourse() . "<br>";
$student->advanceCourse();
echo "Студент перейшов на наступний курс: " . $student->getCourse() . "<br>";
$student->setHeight(172);
$student->setWeight(62);
echo "Студент трохи виріс: " . $student->getHeight() . " см, та додав маси: " . $student->getWeight() . " кг<br>";

// Створюємо об'єкт Programmer
$programmer = new Programmer(180, 75, 25, ['PHP', 'JavaScript'], 5);
echo "Програміст знає мови: " . implode(', ', $programmer->getProgrammingLanguages()) . "<br>";
$programmer->addProgrammingLanguage('Python');
echo "Після додавання мови: " . implode(', ', $programmer->getProgrammingLanguages()) . "<br>";
$programmer->setHeight(182);
$programmer->setWeight(78);
echo "Зріст програміста після додавання мови: " . $programmer->getHeight() . " см, та маса: " . $programmer->getWeight() . " кг<br>";
