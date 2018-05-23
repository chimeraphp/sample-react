<?php
declare(strict_types=1);

namespace Lcobucci\MyApi\Retrieval;

use Lcobucci\MyApi\Book;

final class BookDto
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $name;

    private function __construct(string $id, string $title, string $name)
    {
        $this->id    = $id;
        $this->title = $title;
        $this->name  = $name;
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
