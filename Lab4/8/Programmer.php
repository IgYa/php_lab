<?php

require_once 'Human.php';

/**
 * Class Programmer
 *
 * Клас, що представляє програміста, який успадковує базові характеристики людини.
 */
class Programmer extends Human8 {
    private $programmingLanguages = [];
    private $experience;

    /**
     * Programmer constructor.
     * @param float $height Зріст програміста.
     * @param float $weight Маса програміста.
     * @param int $age Вік програміста.
     * @param array $programmingLanguages Мови програмування, які знає програміст.
     * @param int $experience Досвід роботи (роки).
     */
    public function __construct($height, $weight, $age, $programmingLanguages, $experience) {
        parent::__construct($height, $weight, $age);
        $this->programmingLanguages = $programmingLanguages;
        $this->experience = $experience;
    }

    // Методи GET і SET для programmingLanguages
    public function getProgrammingLanguages() {
        return $this->programmingLanguages;
    }

    public function setProgrammingLanguages($languages) {
        $this->programmingLanguages = $languages;
    }

    // Метод для додавання нової мови програмування
    public function addProgrammingLanguage($language) {
        $this->programmingLanguages[] = $language;
    }

    // Методи GET і SET для experience
    public function getExperience() {
        return $this->experience;
    }

    public function setExperience($experience) {
        $this->experience = $experience;
    }
}
