<?php

interface Command
{
    public function execute(): void;
}

class CopyCommand implements Command
{
    public function __construct(
        private mixed $value,
        private Clipboard $clipboard
    ) {}

    public function execute(): void
    {
        echo "Copying value on clipboard: {$this->value}" . PHP_EOL;
        $this->clipboard->set($this->value);
    }
}

class PasteCommand implements Command
{
    public function __construct(private Clipboard $clipboard) {}

    public function execute(): void
    {
        echo "Pasting value from clipboard: " . $this->clipboard->get() . PHP_EOL;;
    }
}

class Clipboard
{
    protected mixed $value;

    public function set(mixed $value): void
    {
        $this->value = $value;
    }
    
    public function get(): mixed
    {
        return $this->value;
    }
}

class Editor
{
    private $commands = [];

    public function addCommand(Command $command): void
    {
        $this->commands[] = $command;
    }

    public function execute(): void
    {
        array_walk($this->commands, fn ($command) => $command->execute());
    }
}

$editor = new Editor();
$clipboard = new Clipboard();

$editor->addCommand(new CopyCommand('COPYED VALUE', $clipboard));
$editor->addCommand(new PasteCommand($clipboard));
$editor->addCommand(new PasteCommand($clipboard));
$editor->addCommand(new PasteCommand($clipboard));
$editor->addCommand(new PasteCommand($clipboard));

echo PHP_EOL;
$editor->execute();
echo PHP_EOL;
