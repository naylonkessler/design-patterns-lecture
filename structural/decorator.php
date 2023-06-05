<?php

interface Drawable
{
    public function draw($x = 0, $y = 0): void;
}

class Shape implements Drawable
{
    public function draw($x = 0, $y = 0): void
    {
        echo ":: Drawing from Shape class at position {{$x},{$y}}" . PHP_EOL;
    }
}

abstract class DrawDecorator implements Drawable
{
    protected Drawable $draw;

    public function __construct(Drawable $draw)
    {
        $this->draw = $draw;
    }

    public function draw($x = 0, $y = 0): void
    {
        $this->draw->draw($x, $y);
    }
}

class BorderDecorator extends DrawDecorator
{
    public function __construct(Drawable $draw, protected int $borderWidth = 1)
    {
        parent::__construct($draw);
    }

    public function draw($x = 0, $y = 0): void
    {
        parent::draw($x, $y);
        $this->drawBorder($x, $y);
    }

    public function drawBorder($x = 0, $y = 0)
    {
        echo ":: Drawing border with width '{$this->borderWidth}' "
            . "from decorator at position {{$x},{$y}}" . PHP_EOL;
    }
}

class WatermarkDecorator extends DrawDecorator
{
    public function __construct(Drawable $draw, protected string $mark = '')
    {
        parent::__construct($draw);
    }

    public function draw($x = 0, $y = 0): void
    {
        parent::draw($x, $y);
        $this->drawWatermark($x + 10, $y + 10);
    }

    public function drawWatermark($x = 0, $y = 0)
    {
        echo ":: Drawing watermark from decorator with mark '{$this->mark}' "
            . "at position {{$x},{$y}}" . PHP_EOL;
    }
}

$shape = new Shape();
$borderedShape = new BorderDecorator($shape);
$watermarkedShape = new WatermarkDecorator($shape, 'Deco.');
$watermarkedBorderedShape = new WatermarkDecorator(new BorderDecorator($shape), 'Deco.');

echo PHP_EOL;
echo ":::: Using simple shape" . PHP_EOL;
echo $shape->draw(0, 0);
echo PHP_EOL;

echo ":::: Using bordered shape" . PHP_EOL;
echo $borderedShape->draw(10, 10);
echo PHP_EOL;

echo ":::: Using watermarked shape" . PHP_EOL;
echo $watermarkedShape->draw(10, 10);
echo PHP_EOL;

echo ":::: Using watermarked bordered shape" . PHP_EOL;
echo $watermarkedBorderedShape->draw(10, 10);
echo PHP_EOL;
