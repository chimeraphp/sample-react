<?php
declare(strict_types=1);

namespace Lcobucci\MyApi;

use Ramsey\Uuid\UuidInterface;

final class Book
{
    public function __construct(
        private readonly UuidInterface $id,
        private readonly string $title,
        private readonly string $author,
    ) {
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
