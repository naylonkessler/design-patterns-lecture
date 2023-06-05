<?php

interface LoadBalancingStrategy
{
    public function chooseServer(array $servers): string;
}

class RandomStrategy implements LoadBalancingStrategy
{
    public function chooseServer(array $servers): string
    {
        return $servers[mt_rand(0, count($servers) - 1)];
    }
}

class RoundRobinStrategy implements LoadBalancingStrategy
{
    private int $lastChoosen = -1;

    public function chooseServer(array $servers): string
    {
        if ($this->lastChoosen >= count($servers) - 1) {
            $this->lastChoosen = -1;
        }

        return $servers[++$this->lastChoosen];
    }
}

class LoadBalancer
{
    private array $servers = [];
    private LoadBalancingStrategy $strategy;

    public function addServer($ip)
    {
        $this->servers[] = $ip;
    }

    public function setStrategy(LoadBalancingStrategy $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function route(array $request): string
    {
        if (!$this->strategy) {
            throw new Exception('Invalid balacing strategy configured.');
        }

        $targetIp = $this->strategy->chooseServer($this->servers);

        return $this->dispatch($request, $targetIp);
    }

    public function dispatch(array $request, $toIp): string
    {
        return "Dispatched to Ip {$toIp} with request " . json_encode($request);
    }
}

$requestsCount = 10;

$balancer = new LoadBalancer();
$balancer->addServer('104.56.33.100');
$balancer->addServer('156.89.80.200');
$balancer->addServer('200.104.5.158');
$balancer->setStrategy(new RandomStrategy());

echo PHP_EOL;
echo ":: Routing {$requestsCount} requests with RandomStrategy" . PHP_EOL;

for ($i = 0; $i < $requestsCount; $i++) {
    echo $balancer->route(['Method' => 'GET', 'Host' => 'somename.com']) . PHP_EOL;
}

$balancer->setStrategy(new RoundRobinStrategy());

echo PHP_EOL;
echo ":: Routing {$requestsCount} requests with RoundRobinStrategy" . PHP_EOL;

for ($i = 0; $i < $requestsCount; $i++) {
    echo $balancer->route(['Method' => 'GET', 'Host' => 'somename.com']) . PHP_EOL;
}

echo PHP_EOL;
