<?php
$inputFile = 'words.txt';

$words = file_get_contents($inputFile);
$words = mb_convert_case($words, MB_CASE_LOWER, "UTF-8");

$wordsArray = array_filter(explode(' ', $words));

sort($wordsArray, SORT_STRING);

$outputFile = 'sorted_words.txt'; 
file_put_contents($outputFile, implode("\n", $wordsArray));

echo "Слова успішно впорядковані за алфавітом і збережені у файлі '$outputFile'.";
?>
