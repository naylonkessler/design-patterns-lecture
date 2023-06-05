<?php

interface IsImage
{
    public function display(): void;
    public function getName(): string;
}

class Image implements IsImage
{
    private $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
        $this->load();
    }

    private function load(): void
    {
        echo "Loading image: {$this->filename}" . PHP_EOL;
    }

    public function display(): void
    {
        echo "Displaying image: {$this->filename}" . PHP_EOL;
    }

    public function getName(): string
    {
        return basename($this->filename);
    }
}

class ImageProxy implements IsImage
{
    private $filename;
    private $image;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function display(): void
    {
        if (!$this->image) {
            $this->image = new Image($this->filename);
        }

        $this->image->display();
    }

    public function getName(): string
    {
        return basename($this->filename);
    }
}

echo PHP_EOL;
$imageProxy = new ImageProxy('/var/www/domain/image.jpg');
echo "Image name: {$imageProxy->getName()} " . PHP_EOL;
$imageProxy->display();
$imageProxy->display();
echo PHP_EOL;
