<?php

interface Expression
{
    public function interpret(): int;
}

class Number implements Expression
{
    public function __construct(private int $value) {}

    public function interpret(): int
    {
        return $this->value;
    }
}

class Plus implements Expression
{
    public function __construct(
        private Expression $left,
        private Expression $right
    ) {}

    public function interpret(): int
    {
        return $this->left->interpret() + $this->right->interpret();
    }
}

class Minus implements Expression
{
    public function __construct(
        private Expression $left,
        private Expression $right
    ) {}

    public function interpret(): int
    {
        return $this->left->interpret() - $this->right->interpret();
    }
}

$expression = new Minus(
    new Plus(new Number(10), new Number(5)),
    new Number(3)
);

echo PHP_EOL;
echo ':: Result of expression ((10 + 5) - 3): ' . $expression->interpret();
echo PHP_EOL . PHP_EOL;
