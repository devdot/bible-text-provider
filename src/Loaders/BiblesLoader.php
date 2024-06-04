<?php

namespace Devdot\Bible\Text\Loaders;

use DateTimeImmutable;
use Devdot\Bible\Text\Entities\Bible;

/**
 * @extends LazyLoader<string, Bible>
 */
class BiblesLoader extends LazyLoader
{
    /**
     * @var ?array<string, array{id:string, abbreviation: string, name: string, description: string, copyright: string, language: string, updated_at: string, books: string}>
     */
    private ?array $biblesData = null;

    public function __construct(
        private readonly string $dataPath,
        private readonly string $dataFilename = 'bibles.php',
    ) {

    }

    /**
     * @return array<int, Bible>
     */
    public function all(): array
    {
        // make sure all are loaded
        foreach(array_keys($this->getBiblesRawData()) as $key) {
            if (!$this->isLoaded($key)) {
                $this->get($key);
            }
        }

        return array_values(array_filter($this->getEntities()));
    }

    /**
     * @param string $id
     */
    protected function load(string|int $id): ?Bible
    {
        $raw = $this->getBiblesRawData()[$id] ?? null;
        if($raw) {
            return new Bible(
                $raw['id'],
                $raw['abbreviation'],
                $raw['name'],
                $raw['description'],
                $raw['copyright'],
                $raw['language'],
                new DateTimeImmutable($raw['updated_at']),
                new BooksLoader($this->dataPath . '/' . dirname($raw['books']), basename($raw['books'])),
            );
        } else {
            return null;
        }
    }

    /**
     * @return array<string, array{id:string, abbreviation: string, name: string, description: string, copyright: string, language: string, updated_at: string, books: string}>
     */
    public function getBiblesRawData(): array
    {
        $this->biblesData ??= $this->getFileInclude($this->dataPath . '/' . $this->dataFilename);
        return $this->biblesData;
    }
}
