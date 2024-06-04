<?php

namespace Devdot\Bible\Text\Loaders;

use Devdot\Bible\Text\Entities\Entity;
use Devdot\Bible\Text\Exceptions\FileNotFoundException;

/**
 * @template TKey
 * @template TEntity
 * @extends Loader<TKey, TEntity>
 */
abstract class LazyLoader extends Loader
{
    /**
     * @var array<TKey, TEntity>
     */
    private array $entities = [];

    /**
     * @param TKey $id
     * @return ?TEntity
     */
    abstract protected function load(string|int $id): ?Entity;

    /**
     * @param array<TKey, TEntity> $entities
     */
    protected function setEntities(array $entities): void
    {
        $this->entities = $entities;
    }

    /**
     * @return array<TKey, TEntity>
     */
    protected function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * @param TKey $id
     */
    public function isLoaded(string|int $id): bool
    {
        return array_key_exists($id, $this->entities);
    }

    /**
     * @param TKey $id
     * @return ?TEntity
     */
    public function get(string|int $id): ?Entity
    {
        if (!$this->isLoaded($id)) {
            $this->entities[$id] = $this->load($id);
        }
        return $this->entities[$id] ?? null;
    }



    protected function getFileInclude(string $path): array
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException($path);
        }
        return include($path);
    }
}
