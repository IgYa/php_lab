<?php

require_once 'Human.php';

/**
 * Class Student
 *
 * Клас, що представляє студента, який успадковує базові характеристики людини.
 */
class Student extends Human8 {
    private $university;
    private $course;

    /**
     * Student constructor.
     * @param float $height Зріст студента.
     * @param float $weight Маса студента.
     * @param int $age Вік студента.
     * @param string $university Назва університету.
     * @param int $course Курс студента.
     */
    public function __construct($height, $weight, $age, $university, $course) {
        parent::__construct($height, $weight, $age);
        $this->university = $university;
        $this->course = $course;
    }

    // Методи GET і SET для university
    public function getUniversity() {
        return $this->university;
    }

    public function setUniversity($university) {
        $this->university = $university;
    }

    // Методи GET і SET для course
    public function getCourse() {
        return $this->course;
    }

    public function setCourse($course) {
        $this->course = $course;
    }

    /**
     * Переводить студента на новий курс.
     */
    public function advanceCourse() {
        $this->course++;
    }
}
