<?php
declare(strict_types=1);

namespace Lcobucci\MyApi\Creation;

use Chimera\IdentifierGenerator;
use Chimera\Input;
use Ramsey\Uuid\UuidInterface;

final class AddToCollection
{
    public UuidInterface $id;
    public string $title;
    public string $author;

    private function __construct(UuidInterface $id, string $title, string $author)
    {
        $this->id     = $id;
        $this->title  = $title;
        $this->author = $author;
    }

    public static function fromInput(Input $input): self
    {
        $data = $input->getData();

        if (! isset($data['title'], $data['author'])) {
            throw new \InvalidArgumentException('You must send a valid JSON object with "title" and "author"');
        }

        return new self(
            $input->getAttribute(IdentifierGenerator::class),
            $data['title'],
            $data['author']
        );
    }
}
