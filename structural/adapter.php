<?php

interface Notification
{
    public function send(string $title, string $message): void;
}

class EmailNotification implements Notification
{
    public function __construct(private string $to) {}

    public function send(string $title, string $message): void
    {
        echo ":: Sending notification by Email to {$this->to} "
            . "with title '{$title}' and message '{$message}'" . PHP_EOL;
    }
}

class DiscordSDK
{
    private string $webhookUrl;
    private string $username;

    public function __construct(string $webhookUrl, string $username)
    {
        $this->webhookUrl = $webhookUrl;
        $this->username = $username;
    }

    public function check(): bool
    {
        return true;
    }

    public function post(string $content): void
    {
        echo ":: Posting to Discord from 'SDK' to webhook '{$this->webhookUrl}' "
            . "to user '{$this->username}' with content '{$content}'" . PHP_EOL;
    }
}

class DiscordNotification implements Notification
{
    private $discord;

    public function __construct(DiscordSDK $discord)
    {
        $this->discord = $discord;
    }

    public function send(string $title, string $message): void
    {
        if (!$this->discord->check()) {
            return;
        }

        echo ":: Sending notification to Discord from adapter "
            . "with title '{$title}' and message '{$message}'" . PHP_EOL;

        $this->discord->post("# {$title} \n {$message}");
    }
}

class Notifier
{
    private array $to = [];

    public function addTo(Notification $notification)
    {
        $this->to[] = $notification;
    }

    public function notify($title, $message)
    {
        array_map(fn ($to) => $to->send($title, $message), $this->to);
    }
}

echo PHP_EOL;
$notifier = new Notifier();
$notifier->addTo(new EmailNotification('someaddress@mail.com'));
$notifier->addTo(new DiscordNotification(new DiscordSDK('https://webhook.on.discord', 'user')));
$notifier->notify('Adapter', 'This thing is useful \o/');
echo PHP_EOL;
