<?php
declare(strict_types=1);

namespace Lcobucci\MyApi;

use Lcobucci\ErrorHandling\Problem\ResourceNotFound;
use Lcobucci\ErrorHandling\Problem\Titled;
use OutOfBoundsException;

final class BookNotFound extends OutOfBoundsException implements ResourceNotFound, Titled
{
    public function getTitle(): string
    {
        return 'Book does not exist';
    }
}
