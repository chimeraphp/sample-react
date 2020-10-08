<?php
declare(strict_types=1);

namespace Lcobucci\MyApi\Retrieval;

use Chimera\Input;
use Chimera\ServiceBus\ReadModelConverter\Query;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class FetchBook implements Query
{
    public UuidInterface $id;

    private function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public static function fromInput(Input $input): self
    {
        return new self(Uuid::fromString($input->getAttribute('id')));
    }

    public function conversionCallback(): callable
    {
        return [BookDto::class, 'fromEntity'];
    }
}
