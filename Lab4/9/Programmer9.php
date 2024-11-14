<?php

require_once 'Human9.php';

/**
 * Клас Programmer
 *
 * Клас, що представляє програміста.
 */
class Programmer extends Human9 {
    private $programmingLanguages = [];
    private $experience;

    public function __construct($height, $weight, $age, $programmingLanguages, $experience) {
        parent::__construct($height, $weight, $age);
        $this->programmingLanguages = $programmingLanguages;
        $this->experience = $experience;
    }

    public function getProgrammingLanguages() {
        return $this->programmingLanguages;
    }

    public function setProgrammingLanguages($languages) {
        $this->programmingLanguages = $languages;
    }

    public function addProgrammingLanguage($language) {
        $this->programmingLanguages[] = $language;
    }

    public function getExperience() {
        return $this->experience;
    }

    public function setExperience($experience) {
        $this->experience = $experience;
    }

    /**
     * Реалізація повідомлення при народженні дитини для програміста.
     */
    protected function notificationBirthChild() {
        echo "Програміст став батьком/матір'ю!<br>";
    }
}
