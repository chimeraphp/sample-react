<?php
declare(strict_types=1);

namespace Lcobucci\MyApi;

use OutOfBoundsException;
use Ramsey\Uuid\UuidInterface;
use function array_filter;
use function array_values;
use function mb_stripos;
use function sprintf;

final class InMemoryBooks implements Books
{
    /**
     * @var Book[]
     */
    private array $items = [];

    /**
     * {@inheritdoc}
     */
    public function append(Book $book): void
    {
        $this->items[(string) $book->getId()] = $book;
    }

    /**
     * {@inheritdoc}
     */
    public function find(UuidInterface $id): Book
    {
        $requestedId = (string) $id;

        if (! isset($this->items[$requestedId])) {
            throw new BookNotFound(sprintf('The book #%s could not be found in our records', $requestedId));
        }

        return $this->items[$requestedId];
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(?string $title = null, ?string $author = null): array
    {
        $items = array_values($this->items);

        if ($title === null && $author === null) {
            return $items;
        }

        return array_filter(
            $items,
            static fn (Book $book): bool =>
                ($title && mb_stripos($book->getTitle(), $title) !== false)
                || ($author && mb_stripos($book->getAuthor(), $author) !== false)
        );
    }
}
