<?php
declare(strict_types=1);

namespace Lcobucci\MyApi\Retrieval;

use Chimera\Mapping as Chimera;
use Lcobucci\MyApi\Books;

/**
 * @Chimera\Routing\FetchEndpoint(path="/books", query=FindBooks::class, name="book.find")
 * @Chimera\ServiceBus\QueryHandler(handles=FindBooks::class)
 */
final class FindBooksHandler
{
    private Books $books;

    public function __construct(Books $books)
    {
        $this->books = $books;
    }

    public function handle(FindBooks $query): array
    {
        return $this->books->findAll($query->title, $query->author);
    }
}
