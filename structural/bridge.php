<?php

abstract class Info
{
    public function __construct(protected Renderer $renderer) {}

    public function changeRenderer(Renderer $renderer): void
    {
        $this->renderer = $renderer;
    }

    abstract public function view(): string;
}

class GenericInfo extends Info
{
    public function __construct(
        Renderer $renderer,
        protected string $title,
        protected string $description
    ) {
        parent::__construct($renderer);
    }

    public function view(): string
    {
        return $this->renderer->render([
            $this->renderer->title($this->title),
            $this->renderer->description($this->description),
        ]);
    }
}

class EventInfo extends Info
{
    public function __construct(Renderer $renderer, protected Event $event)
    {
        parent::__construct($renderer);
    }

    public function view(): string
    {
        return $this->renderer->render([
            $this->renderer->title($this->event->title),
            $this->renderer->description($this->event->description),
            $this->renderer->image($this->event->image),
            $this->renderer->link("/event/{$this->event->id}/register", 'Register'),
        ]);
    }
}

class Event
{
    public function __construct(
        private string $id,
        private string $title,
        private string $description = '',
        private string $image = ''
    ) {}

    public function __get(string $key): mixed
    {
        return $this->{$key};
    }
}

interface Renderer
{
    public function title(string $title): string;
    public function description(string $description): string;
    public function image(string $url): string;
    public function link(string $url): string;
    public function render(array $parts): string;
}

class XmlRenderer implements Renderer
{
    public function title(string $title): string
    {
        return "<title>{$title}</title>";
    }

    public function description(string $description): string
    {
        return "<description>{$description}</description>";
    }

    public function image(string $url): string
    {
        return "<image>{$url}</image>";
    }

    public function link(string $url): string
    {
        return "<link>{$url}</link>";
    }

    public function render(array $parts): string
    {
        return '<item>' . implode('', $parts) . '</item>';
    }
}

class JsonRenderer implements Renderer
{
    public function title(string $title): string
    {
        return '"title": "' . $title . '"';
    }

    public function description(string $description): string
    {
        return '"description": "' . $description . '"';
    }

    public function image(string $url): string
    {
        return '"image": "' . $url . '"';
    }

    public function link(string $url): string
    {
        return '"link": "' . $url . '"}';
    }

    public function render(array $parts): string
    {
        return '{' . implode(', ', $parts) . '}';
    }
}

$xmlRenderer = new XmlRenderer();
$jsonRenderer = new JsonRenderer();

echo PHP_EOL;
$info = new GenericInfo($xmlRenderer, 'Sample', "Some description for the item");
echo ':: Rendering generic information with XML renderer' . PHP_EOL;
echo $info->view() . PHP_EOL;
echo PHP_EOL;

$event = new Event(
    '478',
    'JS for PHP developers.',
    'Come see a proper way for PHP developers in the world of JavaScript.',
    '/images/js-for-php-devs.jpeg'
);
$info = new EventInfo($jsonRenderer, $event);
echo ':: Rendering event information with JSON renderer' . PHP_EOL;
echo $info->view() . PHP_EOL;
echo ':: Changing renderer to XML' . PHP_EOL;
$info->changeRenderer($xmlRenderer);
echo ':: Rendering event information with XML renderer' . PHP_EOL;
echo $info->view() . PHP_EOL;
echo PHP_EOL;
