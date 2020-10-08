<?php
declare(strict_types=1);

namespace Lcobucci\MyApi\Retrieval;

use Lcobucci\MyApi\Book;

final class BookDto
{
    public string $id;
    public string $title;
    public string $author;

    private function __construct(string $id, string $title, string $author)
    {
        $this->id     = $id;
        $this->title  = $title;
        $this->author = $author;
    }

    public static function fromEntity(Book $book): self
    {
        return new self(
            (string) $book->getId(),
            $book->getTitle(),
            $book->getAuthor()
        );
    }
}
