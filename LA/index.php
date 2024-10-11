<?php // index.php
require_once __DIR__ . '/vendor/autoload.php';

$ds = DIRECTORY_SEPARATOR;

// Tworzymy nowy obiekt loggera
$log = new \Monolog\Logger("my_log");

// Dodajemy handler do zapisu logów (poziom DEBUG, żeby logować wszystko)
$log->pushHandler(new \Monolog\Handler\StreamHandler(__DIR__ . $ds . 'monolog.log', \Monolog\Logger::DEBUG));

// Rejestrowanie różnych poziomów logowania
$log->error("error");
$log->warning("warning");
$log->info("info");
$log->debug("debug");

// Tworzymy instancję Duck i wstrzykujemy logger
$duck = new \Abancewicz\LabComposer\Duck($log);

// Wywołanie metody quack() - logowanie na poziomie DEBUG
$duck->quack();
$duck->quack();
$duck->quack();
