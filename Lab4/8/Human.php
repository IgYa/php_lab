<?php

/**
 * Class Human
 *
 * Базовий клас, що представляє людину з основними характеристиками.
 */
class Human8 {
    private $height;
    private $weight;
    private $age;

    /**
     * Human constructor.
     * @param float $height Зріст людини.
     * @param float $weight Маса людини.
     * @param int $age Вік людини.
     */
    public function __construct($height, $weight, $age) {
        $this->height = $height;
        $this->weight = $weight;
        $this->age = $age;
    }

    // Методи GET і SET для height
    public function getHeight() {
        return $this->height;
    }

    public function setHeight($height) {
        $this->height = $height;
    }

    // Методи GET і SET для weight
    public function getWeight() {
        return $this->weight;
    }

    public function setWeight($weight) {
        $this->weight = $weight;
    }

    // Методи GET і SET для age
    public function getAge() {
        return $this->age;
    }

    public function setAge($age) {
        $this->age = $age;
    }
}
