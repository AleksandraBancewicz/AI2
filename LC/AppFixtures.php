<?php

namespace App\DataFixtures;

use App\Entity\Location;
use App\Entity\WeatherData;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        // Dodanie przykładowych lokalizacji

        // Lokalizacja 1: Szczecin
        $szczecin = new Location();
        $szczecin->setCity('Szczecin');
        $szczecin->setCountry('PL');
        $szczecin->setLatitude(53.4289);
        $szczecin->setLongitude(14.553);
        $manager->persist($szczecin);

        // Lokalizacja 2: Police
        $police = new Location();
        $police->setCity('Police');
        $police->setCountry('PL');
        $police->setLatitude(53.5521);
        $police->setLongitude(14.5718);
        $manager->persist($police);

        // Dodawanie przykładowych danych pogodowych dla Szczecina
        $weatherData1 = new WeatherData();
        $weatherData1->setLocation($szczecin);
        $weatherData1->setDate(new \DateTime('2023-09-23'));
        $weatherData1->setTemperatureCelsius(18);
        $weatherData1->setPressure(1013);
        $weatherData1->setWindSpeed(5);
        $weatherData1->setPrecipitation(0);
        $manager->persist($weatherData1);

        $weatherData2 = new WeatherData();
        $weatherData2->setLocation($szczecin);
        $weatherData2->setDate(new \DateTime('2023-09-24'));
        $weatherData2->setTemperatureCelsius(19);
        $weatherData2->setPressure(1015);
        $weatherData2->setWindSpeed(6);
        $weatherData2->setPrecipitation(1);
        $manager->persist($weatherData2);

        // Wprowadzenie danych do bazy
        $manager->flush();
    }
}
