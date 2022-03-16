<?php
declare(strict_types=1);

namespace Lcobucci\MyApi\Retrieval;

use Chimera\Input;
use Chimera\ServiceBus\ReadModelConverter\Query;

final class FindBooks implements Query
{
    private function __construct(public readonly ?string $title, public readonly ?string $author)
    {
    }

    public static function fromInput(Input $input): self
    {
        $data = $input->getData();

        return new self(
            $data['title'] ?? null,
            $data['author'] ?? null
        );
    }

    public function conversionCallback(): callable
    {
        return BookDto::fromEntity(...);
    }
}
