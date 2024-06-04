<?php

namespace Devdot\Bible\Text\Entities;

use Devdot\Bible\Text\Loaders\VersesLoader;

class Chapter extends Entity
{
    public function __construct(
        public readonly int $number,
        public readonly VersesLoader $verses,
    ) {
    }
}
