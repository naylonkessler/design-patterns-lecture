<?php

interface Observer
{
    public function update(array $data): void;
}

interface Subject
{
    public function addObserver(Observer $observer): void;
    public function removeObserver(Observer $observer): void;
}

abstract class BaseSubject implements Subject
{
    protected array $observers = [];

    public function addObserver(Observer $observer): void
    {
        $this->observers[] = $observer;
    }

    public function removeObserver(Observer $observer): void
    {
        $index = array_search($observer, $this->observers);

        if ($index !== false) {
            array_splice($this->observers, $index, 1);
        }
    }

    abstract protected function notifyObservers(): void;
}

class WeatherStation extends BaseSubject
{
    private $temperature;
    private $pressure;

    public function updateWeatherData(float $temperature, float $pressure): void
    {
        $this->temperature = $temperature;
        $this->pressure = $pressure;
        $this->notifyObservers();
    }

    protected function notifyObservers(): void
    {
        $data = [
            'temperature' => $this->temperature,
            'pressure' => $this->pressure,
        ];

        array_walk($this->observers, fn ($observer) => $observer->update($data));
    }
}

class InternationalDisplay implements Observer
{
    public function update(array $data): void
    {
        $temperature = $data['temperature'] + 273.15;
        $pressure = $data['pressure'] * 100000;

        echo ':: Wheather update' . PHP_EOL;
        echo "Temperature: {$temperature} K" . PHP_EOL;
        echo "Pressure: {$pressure} Pa" . PHP_EOL;
    }
}

class SimpleDisplay implements Observer
{
    public function update(array $data): void
    {
        echo ':: Wheather update' . PHP_EOL;
        echo "Temperature: {$data['temperature']} °C" . PHP_EOL;
        echo "Pressure: {$data['pressure']} Bar" . PHP_EOL;
    }
}

$weatherStation = new WeatherStation();
$intlDisplay = new InternationalDisplay();
$simpleDisplay = new SimpleDisplay();

echo PHP_EOL;
echo ':::: Adding 2 observers' . PHP_EOL;

$weatherStation->addObserver($intlDisplay);
$weatherStation->addObserver($simpleDisplay);

echo PHP_EOL;
$weatherStation->updateWeatherData(25.5, 1.01325);
echo PHP_EOL;

echo ':::: Removing 1 observer' . PHP_EOL;
$weatherStation->removeObserver($simpleDisplay);

echo PHP_EOL;
$weatherStation->updateWeatherData(24.8, 1.01420);
echo PHP_EOL;
