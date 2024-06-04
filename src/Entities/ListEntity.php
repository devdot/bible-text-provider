<?php

namespace Devdot\Bible\Text\Entities;

use ArrayAccess;
use Devdot\Bible\Text\Loaders\Loader;

abstract class ListEntity extends Entity implements ArrayAccess
{
    public function __construct(
        protected Loader $loader,
    ) {
    }

    public function get(string|int $id): ?Entity
    {
        return $this->loader->get($id);
    }


}
