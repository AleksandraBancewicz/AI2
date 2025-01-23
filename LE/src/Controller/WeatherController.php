<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\MeasurementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class WeatherController extends AbstractController
{
    #[Route('/weather/{city}/{countryCode?}', name: 'app_weather')]
    public function city(string $city, ?string $countryCode, MeasurementRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $location = $entityManager->getRepository(Location::class)->findOneBy([
            'city' => $city,
            'country' => $countryCode,
        ]);

        if (!$location) {
            throw $this->createNotFoundException('Location not found');
        }

        $measurements = $repository->findByLocation($location);

        return $this->render('weather/city.html.twig', [
            'location' => $location,
            'measurements' => $measurements,
        ]);
    }
}
