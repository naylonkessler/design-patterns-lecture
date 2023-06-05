<?php

class Car
{
    public function __construct(
        private string $brand, 
        private string $model, 
        private int $year, 
        private string $color, 
        private string $engine
    ) {}

    public function __get(string $name): mixed
    {
        return $this->{$name};
    }

    public function __toString(): string
    {
        return "{$this->brand} {$this->model} {$this->year} {$this->engine} {$this->color}";
    }
}

interface CarBuilder
{
    public function reset(): CarBuilder;
    public function setBrand(string $brand): CarBuilder;
    public function setModel(string $model): CarBuilder;
    public function setYear(int $year): CarBuilder;
    public function setColor(string $color): CarBuilder;
    public function setEngine(string $engine): CarBuilder;
    public function build(): Car;
}

class BaseCarBuilder implements CarBuilder
{
    private string $brand;
    private string $model;
    private int $year;
    private string $color;
    private string $engine;

    public function __construct()
    {
        $this->reset();
    }

    public function reset(): CarBuilder
    {
        return $this->setBrand('')
            ->setModel('')
            ->setYear((int) date('Y'))
            ->setColor('Black')
            ->setEngine('1.0 Flex');
    }

    public function setBrand(string $brand): CarBuilder
    {
        $this->brand = $brand;
        return $this;
    }

    public function setModel(string $model): CarBuilder
    {
        $this->model = $model;
        return $this;
    }

    public function setYear(int $year): CarBuilder
    {
        $this->year = $year;
        return $this;
    }

    public function setColor(string $color): CarBuilder
    {
        $this->color = $color;
        return $this;
    }

    public function setEngine(string $engine): CarBuilder
    {
        $this->engine = $engine;
        return $this;
    }

    public function build(): Car
    {
        return new Car(
            $this->brand,
            $this->model,
            $this->year,
            $this->color,
            $this->engine
        );
    }
}

class CarBuildDirector
{
    public function __construct(private CarBuilder $builder) {}

    public function buildPopularCar(): void
    {
        $this->builder->reset()
            ->setBrand('Fiat')
            ->setModel('Mobi');
    }

    public function buildPremiumCar(): void
    {
        $this->builder->reset()
            ->setBrand('BMW')
            ->setModel('320i M Sport')
            ->setYear(2023)
            ->setColor('Blue')
            ->setEngine('2.0 Turbo ActiveFlex');
    }
}

$builder = new BaseCarBuilder();
$director = new CarBuildDirector($builder);

$director->buildPopularCar();
$popularCar = $builder->build();
$director->buildPremiumCar();
$premiumCar = $builder->build();

echo PHP_EOL;
echo ":: Built popular car" . PHP_EOL;
echo $popularCar;
echo PHP_EOL . PHP_EOL;

echo ":: Built premium car" . PHP_EOL;
echo $premiumCar;
echo PHP_EOL . PHP_EOL;
