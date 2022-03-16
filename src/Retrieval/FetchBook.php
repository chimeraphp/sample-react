<?php
declare(strict_types=1);

namespace Lcobucci\MyApi\Retrieval;

use Chimera\Input;
use Chimera\ServiceBus\ReadModelConverter\Query;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class FetchBook implements Query
{
    private function __construct(public readonly UuidInterface $id)
    {
    }

    public static function fromInput(Input $input): self
    {
        return new self(Uuid::fromString($input->getAttribute('id')));
    }

    public function conversionCallback(): callable
    {
        return BookDto::fromEntity(...);
    }
}
