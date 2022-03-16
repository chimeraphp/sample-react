<?php
declare(strict_types=1);

namespace Lcobucci\MyApi\Retrieval;

use Lcobucci\MyApi\Book;

final class BookDto
{
    private function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $author,
    ) {
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
