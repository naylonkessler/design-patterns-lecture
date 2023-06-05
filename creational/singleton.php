<?php

class Configs
{
    private static $instance;

    private function __construct() {}

    private function __clone() {}

    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize singleton');
    }

    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function get(?string $key = null): array|string|false
    {
        return getenv($key);
    }
}

$configs = Configs::getInstance();

echo PHP_EOL;
echo ":: Getting PWD value" . PHP_EOL;
echo $configs->get('PWD');
echo PHP_EOL . PHP_EOL;

$configsAlt = Configs::getInstance();
echo ":: Getting another instance" . PHP_EOL;
echo "- Is same instance: " . ($configs === $configsAlt ? 'Yes' : 'No');
echo PHP_EOL . PHP_EOL;
