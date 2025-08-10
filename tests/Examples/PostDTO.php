<?php

namespace Tests\Examples;

use Lokus\Dto\SimpleDTO;

final readonly class PostDTO extends SimpleDTO
{
    public function __construct(
        public string $title,
        public string $content,
    ) {}
}