<?php

namespace Devdot\Bible\Text\Entities;

use DateTimeImmutable;
use Devdot\Bible\Text\Loaders\BooksLoader;

class Bible extends Entity
{
    public function __construct(
        public readonly string $id,
        public readonly string $abbreviation,
        public readonly string $name,
        public readonly string $description,
        public readonly string $copyright,
        public readonly string $language,
        public readonly DateTimeImmutable $updated_at,
        public readonly BooksLoader $books,
    ) {
    }
}
