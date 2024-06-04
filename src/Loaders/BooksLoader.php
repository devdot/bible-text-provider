<?php

namespace Devdot\Bible\Text\Loaders;

use Devdot\Bible\Text\Entities\Book;
use Devdot\Bible\Text\Entities\Chapter;
use Devdot\Bible\Text\Entities\Verse;

/**
 * @extends LazyLoader<string, Book>
 */
class BooksLoader extends LazyLoader
{
    /**
     * @var array<string, null|array{id: string, abbreviation: string, name: string, name_long: string, chapters: array<int, array<int, string|array<string>>>}>
     */
    private array $booksRaw = [];
    /**
     * @var array<string, array{id: string, abbreviation: string, description: string, copyright: string, language: string, updated_at: string, books: string}>
     */
    private ?array $bookListRaw = null;

    public function __construct(
        private readonly string $dataPath,
        private readonly string $dataFilename = 'books.php',
    ) {

    }

    /**
     * @param string $id
     */
    protected function load(string|int $id): ?Book
    {
        $id = (string) $id;

        if(!array_key_exists($id, $this->booksRaw)) {
            // check the book list
            $this->bookListRaw ??= $this->getFileInclude($this->dataPath . '/' . $this->dataFilename);

            $bookPath = $this->bookListRaw[$id] ?? null;

            // if the book is not in the book list, abort
            if ($bookPath === null) {
                $this->booksRaw[$id] = null;
                return null;
            }

            // load raw
            // @phpstan-ignore-next-line
            $this->booksRaw[$id] = $this->getFileInclude($this->dataPath . '/' . $bookPath);
        }

        $raw = $this->booksRaw[$id];
        if ($raw === null) {
            return null;
        }

        return new Book(
            $raw['id'],
            $raw['abbreviation'],
            $raw['name'],
            $raw['name_long'],
            new ChaptersLoader($this->buildChaptersFromRaw($raw['chapters']))
        );
    }

    /**
     * @param array<int, array<int, string|array<string>>> $raw
     * @return array<Chapter>
     */
    private function buildChaptersFromRaw(array $raw): array
    {
        $chapters = [];
        foreach($raw as $number => $chapter) {
            $chapters[$number] = new Chapter($number, new VersesLoader($this->buildVersesFromRaw($chapter)));
        }
        return $chapters;
    }

    /**
     * @param array<int, string|array<string>> $raw
     * @return array<Verse>
     */
    private function buildVersesFromRaw(array $raw): array
    {
        $verses = [];
        foreach($raw as $number => $verse) {
            $verses[$number] = new Verse($number, is_string($verse) ? [$verse] : $verse);
        }
        return $verses;
    }
}
