<?php

/**
 * Class Circle
 *
 * Клас для представлення кола із заданими координатами центру та радіусом.
 */
class Circle {
    /**
     * @var float X-координата центру кола
     */
    private $x;

    /**
     * @var float Y-координата центру кола
     */
    private $y;

    /**
     * @var float Радіус кола
     */
    private $radius;

    /**
     * Circle constructor.
     *
     * @param float $x X-координата центру кола
     * @param float $y Y-координата центру кола
     * @param float $radius Радіус кола
     */
    public function __construct($x, $y, $radius) {
        $this->x = $x;
        $this->y = $y;
        $this->radius = $radius;
    }

    /**
     * Метод __toString для виведення інформації про коло в рядковому форматі.
     *
     * @return string
     */
    public function __toString() {
        return "Коло з центром в ({$this->x}, {$this->y}) і радіусом {$this->radius}";
    }

    // Методи GET і SET для координати X
    public function getX() {
        return $this->x;
    }

    public function setX($x) {
        $this->x = $x;
    }

    // Методи GET і SET для координати Y
    public function getY() {
        return $this->y;
    }

    public function setY($y) {
        $this->y = $y;
    }

    // Методи GET і SET для радіуса
    public function getRadius() {
        return $this->radius;
    }

    public function setRadius($radius) {
        $this->radius = $radius;
    }

    /**
     * Перевіряє, чи перетинається поточне коло з іншим колом.
     *
     * @param Circle $other Інший об'єкт кола.
     * @return bool
     */
    public function intersects(Circle $other) {
        // Відстань між центрами двох кіл
        $distance = sqrt(pow($this->x - $other->getX(), 2) + pow($this->y - $other->getY(), 2));

        // Умови перетину: якщо сума радіусів >= відстані між центрами
        return $distance <= ($this->radius + $other->getRadius());
    }
}
