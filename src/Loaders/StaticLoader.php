<?php

namespace Devdot\Bible\Text\Loaders;

use Devdot\Bible\Text\Entities\Entity;

/**
 * @template TKey of int|string
 * @template TEntity of Entity
 * @extends Loader<TKey, TEntity>
 */
class StaticLoader extends Loader
{
    /**
     * @param array<TKey, TEntity> $entities
     */
    public function __construct(
        private array $entities,
    ) {

    }

    /**
     * @param TKey $id
     * @return ?TEntity
     */
    public function get(string|int $id): ?Entity
    {
        return $this->entities[$id] ?? null;
    }
}
