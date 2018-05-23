<?php
declare(strict_types=1);

namespace Lcobucci\MyApi\Retrieval;

use Chimera\Mapping as Chimera;
use Lcobucci\MyApi\Book;
use Lcobucci\MyApi\Books;

/**
 * @Chimera\Routing\FetchEndpoint(path="/books", query=FindBooks::class, name="book.find")
 * @Chimera\ServiceBus\QueryHandler(handles=FindBooks::class)
 */
final class FindBooksHandler
{
    /**
     * @var Books
     */
    private $collection;

    public function __construct(Books $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @return Book[]
     */
    public function handle(FindBooks $query): array
    {
        return $this->collection->findAll($query->title, $query->author);
    }
}
