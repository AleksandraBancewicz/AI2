<?php

// src/Controller/WeatherController.php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\WeatherDataRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class WeatherController extends AbstractController
{
    #[Route('/weather/{city}/{countryCode?}', name: 'app_weather')]
    public function city(string $city, ?string $countryCode, WeatherDataRepository $repository, EntityManagerInterface $entityManager): Response
    {
        // Szukamy lokalizacji na podstawie miasta i opcjonalnie kodu kraju
        $location = $entityManager->getRepository(Location::class)->findOneBy([
            'city' => $city,
            'country' => $countryCode,
        ]);

        // Jeśli lokalizacja nie została znaleziona, możemy wyświetlić stronę z błędem lub przekierować
        if (!$location) {
            throw $this->createNotFoundException('Location not found');
        }

        $measurements = $repository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'weatherData' => $measurements,
        ]);
    }
}