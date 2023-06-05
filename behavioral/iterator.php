<?php

interface SimpleIterator
{
    public function hasNext(): bool;
    public function next(): mixed;
}

class ListIterator implements SimpleIterator
{
    private $current = 0;

    public function __construct(private array $items) {}

    public function hasNext(): bool
    {
        return $this->current < count($this->items);
    }

    public function next(): mixed
    {
        return $this->items[$this->current++];
    }
}

interface Collection
{
    public function getIterator(): SimpleIterator;
}

class ItemsList implements Collection
{
    private array $items = [];

    public function add(mixed $item): void
    {
        $this->items[] = $item;
    }

    public function getIterator(): SimpleIterator
    {
        return new ListIterator($this->items);
    }
}

$items = new ItemsList();
$items->add(['name' => 'John Doe', 'age' => 48]);
$items->add(['name' => 'Mary Doe', 'age' => 45]);
$items->add(['name' => 'John Doe Junior', 'age' => 18]);

$iterator = $items->getIterator();

echo PHP_EOL;

while ($iterator->hasNext()) {
    $item = $iterator->next();

    echo 'Item: ' . json_encode($item) . PHP_EOL;
}

echo PHP_EOL;
