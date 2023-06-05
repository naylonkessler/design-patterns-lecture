<?php

interface PackageState
{
    public function getInfos(Package $package): array;
}

class ShippedState implements PackageState
{
    public function getInfos(Package $package): array
    {
        return [
            'message' => 'The package was shipped.',
            'at' => new DateTime(),
        ];
    }
}

class InTransitState implements PackageState
{
    public function getInfos(Package $package): array
    {
        return [
            'message' => 'The package is in transit.',
            'at' => new DateTime(),
        ];
    }
}

class DeliveredState implements PackageState
{
    public function getInfos(Package $package): array
    {
        return [
            'message' => 'The package was delivered.',
            'at' => new DateTime(),
        ];
    }
}

class Package
{
    public function __construct(private PackageState $state = new ShippedState()) {}

    public function setState(PackageState $state): void
    {
        $this->state = $state;
    }

    public function getStateInfos(): array
    {
        return $this->state->getInfos($this);
    }
}

echo PHP_EOL;

$package = new Package();
$infos = $package->getStateInfos();
echo json_encode($infos) . PHP_EOL;

$package->setState(new InTransitState());
$infos = $package->getStateInfos();
echo json_encode($infos) . PHP_EOL;

$package->setState(new DeliveredState());
$infos = $package->getStateInfos();
echo json_encode($infos) . PHP_EOL;
echo PHP_EOL;
