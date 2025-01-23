<?php

namespace App\Command;

use App\Repository\LocationRepository;
use App\Service\WeatherUtil;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:city',
    description: 'Add a short description for your command',
)]
class WeatherCityCommand extends Command
{
    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly WeatherUtil $weatherUtil,
        string $name = null,
    )
    {
        parent::__construct($name);
    }
    protected function configure(): void
    {
        $this
            ->addArgument('country', InputArgument::REQUIRED, 'Country code')
            ->addArgument('city', InputArgument::REQUIRED, 'City name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $country = $input->getArgument('country');
        $city = $input->getArgument('city');

        $location = $this->locationRepository->findByCityAndCountry($city, $country);

        $measurements = $this->weatherUtil->getWeatherForLocation($location);

        $io->writeln(sprintf('Location: %s, %s', $location->getCity(), $location->getCountry()));
        foreach ($measurements as $measurement) {
            $io->writeln(sprintf("\t%s: %s°C",
                $measurement->getDate()->format('Y-m-d'),
                $measurement->getCelsius()
            ));
        }
        return Command::SUCCESS;
    }
}
