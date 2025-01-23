<?php

namespace App\Tests\Entity;

use App\Entity\Measurement;
use PHPUnit\Framework\TestCase;

class MeasurementTest extends TestCase
{
    /**
     * @dataProvider dataGetFahrenheit
     */
    public function testGetFahrenheit($celsius, $expectedFahrenheit): void
    {
        $measurement = new Measurement();
        $measurement->setCelsius($celsius);  // Używamy wartości z dataProvider
        $this->assertEquals($expectedFahrenheit, $measurement->getFahrenheit());
    }

    public function dataGetFahrenheit(): array
    {
        return [
            // Dodajemy przypadki testowe, również z wartościami ułamkowymi
            [0, 32],
            [-100, -148],
            [100, 212],
            [0.5, 32.9],
            [-0.5, 31.1],
            [25, 77],
            [-25, -13],
            [37.5, 99.5],
            [-50.5, -58.9],
            [10, 50],
        ];
    }
}