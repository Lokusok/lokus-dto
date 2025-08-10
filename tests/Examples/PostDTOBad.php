<?php

namespace Tests\Examples;

use Lokus\Dto\SimpleDTO;

final readonly class PostDTOBad extends SimpleDTO
{
    public function __construct(
        protected string $title,
        /** @phpstan-ignore property.onlyWritten */
        private string $content,
    ) {}
}