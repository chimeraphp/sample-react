<?php
declare(strict_types=1);

namespace Lcobucci\MyApi;

use Ramsey\Uuid\UuidInterface;

final class Book
{
    private UuidInterface $id;
    private string $title;
    private string $author;

    public function __construct(UuidInterface $id, string $title, string $author)
    {
        $this->id     = $id;
        $this->title  = $title;
        $this->author = $author;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }
}
