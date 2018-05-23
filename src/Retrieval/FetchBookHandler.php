<?php
declare(strict_types=1);

namespace Lcobucci\MyApi\Retrieval;

use Chimera\Mapping as Chimera;
use Lcobucci\MyApi\Book;
use Lcobucci\MyApi\Books;

/**
 * @Chimera\Routing\FetchEndpoint(path="/books/{id}", query=FetchBook::class, name="book.fetch_one")
 * @Chimera\ServiceBus\QueryHandler(handles=FetchBook::class)
 */
final class FetchBookHandler
{
    /**
     * @var Books
     */
    private $collection;

    public function __construct(Books $collection)
    {
        $this->collection = $collection;
    }

    public function handle(FetchBook $query): Book
    {
        return $this->collection->find($query->id);
    }
}
