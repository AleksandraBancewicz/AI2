<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;
use App\Service\WeatherUtil;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{city}/', name: 'app_weather')]
    public function city(
        string $city,
        LocationRepository $locationRepository,
        WeatherUtil $util,
    ): Response
    {
        // Znajdź lokalizację na podstawie miasta
        $location = $locationRepository->findOneBy(['city' => $city]);

        if (!$location) {
            throw $this->createNotFoundException("City '$city' not found in the database.");
        }

        $measurements = $util->getWeatherForLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }

    #[Route('/weather/location/{id}', name: 'app_weather_location')]
    public function location(
        int $id,
        LocationRepository $locationRepository,
        WeatherUtil $util
    ): Response {
        // Znajdź lokalizację na podstawie ID
        $location = $locationRepository->find($id);

        if (!$location) {
            throw $this->createNotFoundException("Location with ID '$id' not found in the database.");
        }

        $measurements = $util->getWeatherForLocation($location);

        return $this->render('weather/location.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }

    #[Route('/weather/{country}/{city}', name: 'app_weather_country_and_city')]
    public function countryAndCity(
        string $country,
        string $city,
        LocationRepository $locationRepository,
        WeatherUtil $util
    ): Response {
        $location = $locationRepository->findByCityAndCountry($city, $country);

        if (!$location) {
            throw $this->createNotFoundException("City '$city' in country '$country' not found in the database.");
        }

        $measurements = $location ? $util->getWeatherForLocation($location) : [];

        return $this->render('weather/country_and_city.html.twig', [
            'location' => $location,
            'city' => $city,
            'country' => $country,
            'measurements' => $measurements,
        ]);
    }
}
