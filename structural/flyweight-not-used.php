<?php

class Level
{
    public function __construct(
        private string $name,
        private $image
    ) {}

    public function __get(string $key): mixed
    {
        return $this->{$key};
    }
}

class Player
{
    public function __construct(
        private int $id,
        private string $name,
        private Level $level
    ) {}

    public function __get(string $key): mixed
    {
        return $this->{$key};
    }
}

class PlayerContainer
{
    private array $players = [];

    public function addPlayer(
        int $id,
        string $name,
        string $levelName,
        string $levelImage
    ) {
        $level = $this->getLevel($levelName, $levelImage);
        $this->players[$id] = new Player($id, $name, $level);
    }

    public function getLevel(string $name, string $image): Level
    {
        return new Level($name, $image);
    }

    public function getPlayerById(int $id): ?Player
    {
        return $this->players[$id] ?? null;
    }

    public function getItemsCount(): int
    {
        return count($this->players);
    }
}

class Client
{
    private PlayerContainer $container;
    private int $startMemory;
    private int $endMemory;

    public function __construct()
    {
        $this->container = new PlayerContainer();
        $this->startMemory = memory_get_usage();
    }

    public function run(): void
    {
        $handle = fopen(__DIR__ . '/flyweight-data.csv', 'r');

        while (($data = fgetcsv($handle)) !== false) {
            $this->container->addPlayer($data[0], $data[1], $data[2], $data[3]);
        }

        fclose($handle);

        $this->endMemory = memory_get_usage();
    }

    public function showStatistics(): void
    {
        echo ':: Using flyweight' . PHP_EOL;
        echo 'Items on container:   ' . $this->container->getItemsCount() . PHP_EOL;
        echo 'Memory at start:      ' . $this->formatMemory($this->startMemory) . PHP_EOL;
        echo 'Memory at end:        ' . $this->formatMemory($this->endMemory) . PHP_EOL;
        echo 'Memory used by items: ' . $this->formatMemory($this->endMemory - $this->startMemory);
    }

    public function formatMemory (int $memory): string
    {
        return round($memory / 1000) . 'KB';
    }
}

$client = new Client();
$client->run();

echo PHP_EOL;
$client->showStatistics();
echo PHP_EOL . PHP_EOL;
