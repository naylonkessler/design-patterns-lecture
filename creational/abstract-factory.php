<?php

abstract class ComputerPartsFactory
{
    abstract public function makeMemory(): Memory;
    abstract public function makeVideoBoard(): VideoBoard;
}

class OfficeComputerPartsFactory extends ComputerPartsFactory
{
    public function makeMemory(): Memory
    {
        return new OfficeComputerMemory();
    }

    public function makeVideoBoard(): VideoBoard
    {
        return new OfficeComputerVideoBoard();
    }
}

class GamerComputerPartsFactory extends ComputerPartsFactory
{
    public function makeMemory(): Memory
    {
        return new GamerComputerMemory();
    }

    public function makeVideoBoard(): VideoBoard
    {
        return new GamerComputerVideoBoard();
    }
}

abstract class Memory
{
    protected int $baseSpeed = 1800;
    abstract public function getSpeed(): int;
}

abstract class VideoBoard
{
    protected int $baseSpeed = 1000;
    abstract public function getSpeed(): int;
}

class OfficeComputerMemory extends Memory
{
    public function getSpeed(): int
    {
        return $this->baseSpeed;
    }
}

class GamerComputerMemory extends Memory
{
    public function getSpeed(): int
    {
        return $this->baseSpeed * 1.8;
    }
}

class OfficeComputerVideoBoard extends VideoBoard
{
    public function getSpeed(): int
    {
        return $this->baseSpeed;
    }
}

class GamerComputerVideoBoard extends VideoBoard
{
    public function getSpeed(): int
    {
        return $this->baseSpeed * 2.2;
    }
}

class ComputerMaker
{
    public function makeSpecification(ComputerPartsFactory $factory): string
    {
        $memory = $factory->makeMemory();
        $videoBoard = $factory->makeVideoBoard();

        return "Computer / Memory speed: {$memory->getSpeed()}Mhz"
            . " / Video board speed: {$videoBoard->getSpeed()}Mhz";
    }
}

$maker = new ComputerMaker();

echo PHP_EOL;
echo ":: Making an Office Computer" . PHP_EOL;
echo $maker->makeSpecification(new OfficeComputerPartsFactory());
echo PHP_EOL . PHP_EOL;

echo ":: Making a Gamer Computer" . PHP_EOL;
echo $maker->makeSpecification(new GamerComputerPartsFactory());
echo PHP_EOL . PHP_EOL;
