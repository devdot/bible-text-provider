<?php

namespace Devdot\Bible\Text\Loaders;

use ArrayAccess;
use Exception;
use Devdot\Bible\Text\Entities\Entity;

/**
 * @template TKey
 * @template TEntity
 * @implements ArrayAccess<TKey, TEntity>
 */
abstract class Loader implements ArrayAccess
{
    /**
     * @param TKey $id
     * @return ?TEntity
     */
    abstract public function get(string|int $id): ?Entity;

    /**
     * @param TKey $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->get($offset) instanceof \Devdot\Bible\Text\Entities\Entity;
    }

    /**
     * @param TKey $offset
     * @return ?TEntity
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new Exception('Cannot set a list item!');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new Exception('Cannot unset a list item!');
    }
}
