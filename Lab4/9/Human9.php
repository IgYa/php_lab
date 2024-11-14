<?php

/**
 * Абстрактний клас Human
 *
 * Клас, що представляє людину з основними характеристиками.
 */
abstract class Human9 {
    private $height;
    private $weight;
    private $age;

    public function __construct($height, $weight, $age) {
        $this->height = $height;
        $this->weight = $weight;
        $this->age = $age;
    }

    public function getHeight() {
        return $this->height;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    public function getWeight() {
        return $this->weight;
    }

    public function setWeight($weight) {
        $this->weight = $weight;
    }

    public function getAge() {
        return $this->age;
    }

    public function setAge($age) {
        $this->age = $age;
    }

    /**
     * Метод народження дитини
     */
    public function birthChild() {
        $this->notificationBirthChild();
    }

    /**
     * Абстрактний метод для виведення повідомлення при народженні дитини
     */
    abstract protected function notificationBirthChild();
}
