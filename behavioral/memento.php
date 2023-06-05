<?php

class EditorMemento
{
    public function __construct(private string $content) {}

    public function getContent(): string
    {
        return $this->content;
    }
}

class Editor
{
    private string $content = '';
    
    public function type(string $text): void
    {
        $this->content .= $text;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function save(): EditorMemento
    {
        return new EditorMemento($this->content);
    }

    public function restore(EditorMemento $memento): void
    {
        $this->content = $memento->getContent();
    }
}

class History
{
    private array $mementos = [];
    
    public function push(EditorMemento $memento): void
    {
        $this->mementos[] = $memento;
    }

    public function pop(): EditorMemento
    {
        return array_pop($this->mementos);
    }
}

$editor = new Editor();
$history = new History();

$editor->type('First line of text' . PHP_EOL);
$history->push($editor->save());

$editor->type('Second line of text' . PHP_EOL);
$editor->type('Third line of text' . PHP_EOL);
$history->push($editor->save());

$editor->type('Fourth line of text' . PHP_EOL);

echo PHP_EOL;
echo ':::: Current content ' . PHP_EOL;
echo $editor->getContent();
echo PHP_EOL;

$editor->restore($history->pop());
echo ':::: Restored content ' . PHP_EOL;
echo $editor->getContent();
echo PHP_EOL;

$editor->restore($history->pop());
echo ':::: Restored content ' . PHP_EOL;
echo $editor->getContent();
echo PHP_EOL;
