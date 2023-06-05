<?php

class EventDispatcher
{
    private $observers = [];

    private function getObservers(string $event): array
    {
        return $this->observers[$event] ?? [];
    }

    public function attach(Observer $observer, string $event): void
    {
        $this->observers[$event] = $this->observers[$event] ?? [];
        $this->observers[$event][] = $observer;
    }

    public function fire(string $event, object $emitter, $data = null): void
    {
        foreach ($this->getObservers($event) as $observer) {
            $observer->update($event, $emitter, $data);
        }
    }
}

function events(): EventDispatcher
{
    static $dispatcher;

    if (!$dispatcher) {
        $dispatcher = new EventDispatcher();
    }

    return $dispatcher;
}

interface Observer
{
    public function update(string $event, object $emitter, $data = null);
}

class UserRepository
{
    private $users = [];

    public function create(array $data): User
    {
        $user = new User();
        $user->fill(array_merge($data, ['id' => uniqid('user-')]));

        $this->users[$user->id] = $user;

        events()->fire('users:created', $this, $user);

        return $user;
    }

    public function delete(User $user): void
    {
        if (!isset($this->users[$user->id])) {
            return;
        }

        unset($this->users[$user->id]);
        events()->fire('users:deleted', $this, $user);
    }
}

class User
{
    protected array $data = [];

    public function __get(string $key): mixed
    {
        return $this->data[$key];
    }

    public function __set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function fill(array $data)
    {
        $this->data = $data;
    }
}

class WelcomeNotification implements Observer
{
    public function update(string $event, object $emitter, $data = null): void
    {
        if ($event === 'users:created') {
            echo ":: User created ... Sending welcome notification to {$data->email}" . PHP_EOL;
        }
    }
}

class WeAreSadNotification implements Observer
{
    public function update(string $event, object $emitter, $data = null): void
    {
        if ($event === 'users:deleted') {
            echo ":: User deleted ... Sending we are sad notification to {$data->email}" . PHP_EOL;
        }
    }
}

$users = new UserRepository();

$welcome = new WelcomeNotification();
events()->attach($welcome, 'users:created');

$weAreSad = new WeAreSadNotification();
events()->attach($weAreSad, 'users:deleted');

echo PHP_EOL;

$user = $users->create([
    'name' => 'John Doe',
    'email' => 'john@email.com',
]);

$users->delete($user);

echo PHP_EOL;
