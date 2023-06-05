<?php

interface Graphic
{
    public function draw(): void;
}

class Rectangle implements Graphic
{
    public function draw(): void
    {
        echo 'Drawing a Rectangle.' . PHP_EOL;
    }
}

class Circle implements Graphic
{
    public function draw(): void
    {
        echo 'Drawing a Circle.' . PHP_EOL;
    }
}

abstract class GraphicComposite implements Graphic
{
    public function __construct(private int $level = 1, private $graphics = []) {}

    public function add(Graphic $graphic): void
    {
        $this->graphics[] = $graphic;
    }

    public function draw(): void
    {
        array_walk($this->graphics, [$this, 'drawWithLevel']);
    }

    private function drawWithLevel(Graphic $graphic)
    {
        echo str_repeat('-- ', $this->level);
        $graphic->draw();
    }
}

class Picture extends GraphicComposite
{
    public function draw(): void
    {
        echo 'Drawing graphics from a Picture.' . PHP_EOL;
        parent::draw();
    }
}

class Group extends GraphicComposite
{
    public function draw(): void
    {
        echo 'Drawing graphics from a Group.' . PHP_EOL;
        parent::draw();
    }
}

$picture = new Picture();
$picture->add(new Rectangle());
$picture->add(new Rectangle());
$group = new Group(2);
$group->add(new Rectangle());
$group->add(new Circle());
$picture->add($group);

echo PHP_EOL;
$picture->draw();
echo PHP_EOL;
