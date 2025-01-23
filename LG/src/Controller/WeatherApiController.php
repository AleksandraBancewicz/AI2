<?php

namespace App\Controller;

use App\Service\WeatherUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use App\Repository\LocationRepository;

final class WeatherApiController extends AbstractController
{
    private WeatherUtil $weatherUtil;
    private LocationRepository $locationRepository;

    public function __construct(WeatherUtil $weatherUtil, LocationRepository $locationRepository)
    {
        $this->weatherUtil = $weatherUtil;
        $this->locationRepository = $locationRepository;
    }

    #[Route('/api/v1/weather', name: 'app_weather_api', methods: ['GET'])]
    public function getWeather(
        #[MapQueryParameter] string $country,
        #[MapQueryParameter] string $city,
        #[MapQueryParameter] string $format = 'json', // Domyślny format to JSON
        #[MapQueryParameter('twig')] bool $twig = false // Parametr twig
    ): Response
    {
        // Pobranie lokalizacji z bazy danych na podstawie kraju i miasta
        $location = $this->locationRepository->findOneBy([
            'country' => $country,
            'city' => $city,
        ]);

        if (!$location) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Location not found.',
            ], 404);
        }

        // Pobranie prognozy pogody za pomocą serwisu WeatherUtil
        $measurements = $this->weatherUtil->getWeatherForLocation($location);

        // Przekształcenie wyników prognozy na wymagany format
        $measurementsData = array_map(fn($m) => [
            'date' => $m->getDate()->format('Y-m-d'),
            'celsius' => $m->getCelsius(),
            'fahrenheit' => $m->getFahrenheit(),  // Dodajemy temperaturę w Fahrenheicie
        ], $measurements);

        // Jeśli parametr twig jest ustawiony na true, używamy TWIG do renderowania odpowiedzi
        if ($twig) {
            if ($format === 'json') {
                return $this->render('weather_api/index.json.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurementsData,
                ]);
            }

            if ($format === 'csv') {
                return $this->render('weather_api/index.csv.twig', [
                    'city' => $city,
                    'country' => $country,
                    'measurements' => $measurementsData,
                ]);
            }
        }

        // Jeśli format to JSON, zwróć dane w formacie JSON
        if ($format === 'json') {
            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    'location' => $city,
                    'country' => $country,
                    'measurements' => $measurementsData,
                ],
            ]);
        }

        // Jeśli format to CSV, generuj odpowiedź w formacie CSV
        if ($format === 'csv') {
            $csvData = [];
            // Dodaj nagłówki kolumn
            $csvData[] = implode(',', ['city', 'country', 'date', 'celsius', 'fahrenheit']); // Dodajemy kolumnę 'fahrenheit'

            // Dodaj dane pomiarów
            foreach ($measurementsData as $measurement) {
                $csvData[] = implode(',', [
                    $city,
                    $country,
                    $measurement['date'],
                    $measurement['celsius'],
                    $measurement['fahrenheit'],  // Dodajemy temperaturę w Fahrenheicie
                ]);
            }

            // Stwórz odpowiedź CSV
            $csvContent = implode("\n", $csvData);

            return new Response(
                $csvContent,
                Response::HTTP_OK,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'inline; filename="weather.csv"' // zamiast 'attachment'
                ]
            );
        }

        // Jeśli format nie jest poprawny, zwróć błąd
        return new JsonResponse([
            'status' => 'error',
            'message' => 'Invalid format. Use "json" or "csv".',
        ], 400);
    }
}