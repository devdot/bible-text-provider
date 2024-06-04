<?php

namespace Devdot\Bible\Text\Entities;

use Devdot\Bible\Text\Loaders\ChaptersLoader;

class Book extends Entity
{
    public function __construct(
        public readonly string $id,
        public readonly string $abbreviation,
        public readonly string $name,
        public readonly string $name_long,
        public readonly ChaptersLoader $chapters,
    ) {
    }
}
