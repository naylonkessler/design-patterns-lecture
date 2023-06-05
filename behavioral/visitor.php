<?php

interface Vehicle
{
    public function accept(PrintVisitor $visitor): void;
    public function getPlate(): string;
}

abstract class BaseVehicle implements Vehicle
{
    public function __construct(protected string $plate) {}

    abstract public function accept(PrintVisitor $visitor): void;

    public function getPlate(): string
    {
        return $this->plate;
    }
}

class Car extends BaseVehicle
{
    public function accept(PrintVisitor $visitor): void
    {
        $visitor->visitCar($this);
    }
}

class Motorcycle extends BaseVehicle
{
    public function accept(PrintVisitor $visitor): void
    {
        $visitor->visitMotorcycle($this);
    }
}

interface Visitor
{
    public function visitCar(Vehicle $vehicle): void;
    public function visitMotorcycle(Vehicle $vehicle): void;
}

class PrintVisitor implements Visitor
{
    public function visitCar(Vehicle $vehicle): void
    {
        echo " - Parked Car: {$vehicle->getPlate()}" . PHP_EOL;
    }

    public function visitMotorcycle(Vehicle $vehicle): void
    {
        echo " - Parked Motorcycle: {$vehicle->getPlate()}" . PHP_EOL;
    }
}

class Parking
{
    protected PrintVisitor $printVisitor;
    protected array $slots = [];

    public function __construct(protected int $slotsCount = 20)
    {
        $this->printVisitor = new PrintVisitor();
    }

    public function park(Vehicle $vehicle)
    {
        if (count($this->slots) >= $this->slotsCount) {
            throw new Exception('Unable to park vehicle. Parking is full.');
        }

        $this->slots[] = $vehicle;
    }

    public function displayOccupationInfos()
    {
        $takenSlots = count($this->slots);
        $freeSlots = $this->slotsCount - $takenSlots;

        echo ':::: Occupation Infos' . PHP_EOL;
        echo ":: Total slots: {$this->slotsCount}" . PHP_EOL;
        echo ":: Taken slots: {$takenSlots}" . PHP_EOL;
        echo ":: Free slots: {$freeSlots}" . PHP_EOL;
        echo ':: Vehicles ---------------------' . PHP_EOL;
        array_walk($this->slots, fn ($vehicle) => $vehicle->accept($this->printVisitor));
    }
}

$parking = new Parking();
$parking->park(new Car('HKG9C05'));
$parking->park(new Car('UFV1F01'));
$parking->park(new Motorcycle('ITV9A07'));
$parking->park(new Motorcycle('OWN0B09'));
$parking->park(new Car('POJ2H01'));
$parking->park(new Car('AEN5Z10'));
$parking->park(new Motorcycle('UHF2C02'));

echo PHP_EOL;
$parking->displayOccupationInfos();
echo PHP_EOL;
