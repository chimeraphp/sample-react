<?php
declare(strict_types=1);

namespace Lcobucci\MyApi\Retrieval;

use Chimera\Input;
use Chimera\ServiceBus\ReadModelConverter\Query;

final class FindBooks implements Query
{
    public ?string $title;
    public ?string $author;

    private function __construct(?string $title, ?string $author)
    {
        $this->author = $author;
        $this->title  = $title;
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
        return [BookDto::class, 'fromEntity'];
    }
}
