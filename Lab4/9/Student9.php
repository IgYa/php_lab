<?php

require_once 'Human9.php';

/**
 * Клас Student
 *
 * Клас, що представляє студента.
 */
class Student extends Human9 {
    private $university;
    private $course;

    public function __construct($height, $weight, $age, $university, $course) {
        parent::__construct($height, $weight, $age);
        $this->university = $university;
        $this->course = $course;
    }

    public function getUniversity() {
        return $this->university;
    }

    public function setUniversity($university) {
        $this->university = $university;
    }

    public function getCourse() {
        return $this->course;
    }

    public function setCourse($course) {
        $this->course = $course;
    }

    public function advanceCourse() {
        $this->course++;
    }

    /**
     * Реалізація повідомлення при народженні дитини для студента.
     */
    protected function notificationBirthChild() {
        echo "Студент став батьком/матір'ю!<br>";
    }
}
