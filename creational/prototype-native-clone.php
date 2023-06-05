<?php

class Article
{
    public function __construct(
        private string $title,
        private array $bodySections,
        private DateTime $publishAt,
        private array $comments
    ) {}

    public function __get(string $name): mixed
    {
        return $this->{$name};
    }

    public function __clone()
    {
        $this->title = 'Copy of ' . $this->title;
        $this->publishAt = new DateTime();
        $this->comments = [];
    }
}

class Client
{
    public function fetchArticle(): Article
    {
        return new Article(
            'Test Article',
            ['First section', 'Second section'],
            new DateTime('-1 day'),
            ['First comment', 'Second comment']
        );
    }

    public function createArticleFrom($prototype): Article
    {
        return clone $prototype;
    }
}

$client = new Client();
$original = $client->fetchArticle();
$new = $client->createArticleFrom($original);

echo PHP_EOL;
echo ":: Original article" . PHP_EOL;
echo "Title: {$original->title}" . PHP_EOL;
echo "Body section: " . implode(', ', $original->bodySections) . PHP_EOL;
echo "Publish at: {$original->publishAt->format(DateTime::COOKIE)}" . PHP_EOL;
echo "Comments: " . implode(', ', $original->comments) . PHP_EOL;
echo PHP_EOL . PHP_EOL;

echo ":: New article" . PHP_EOL;
echo "Title: {$new->title}" . PHP_EOL;
echo "Body section: " . implode(', ', $new->bodySections) . PHP_EOL;
echo "Publish at: {$new->publishAt->format(DateTime::COOKIE)}" . PHP_EOL;
echo "Comments: " . implode(', ', $new->comments) . PHP_EOL;
echo PHP_EOL . PHP_EOL;
