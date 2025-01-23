<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Location;
use App\Entity\Measurement;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class WeatherUtil
{
    private $locationRepository;
    private $measurementRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        // Wstrzykujemy repozytoria do serwisu
        $this->locationRepository = $doctrine->getRepository(Location::class);
        $this->measurementRepository = $doctrine->getRepository(Measurement::class);
    }

    /**
     * @return Measurement[]
     */

    public function getWeatherForLocation(Location $location): array
    {
        return $this->measurementRepository->findBy([
            'location' => $location,
        ]);
    }

    /**
     * @return Measurement[]
     */
    public function getWeatherForCountryAndCity(string $countryCode, string $city): array
    {
        $location = $this->locationRepository->findByOne([
            'country' => $countryCode,
            'city' => $city,
        ]);

        if (!$location) {
            return[];
        }

        return $this->getWeatherForLocation($location);
    }
}