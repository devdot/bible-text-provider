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
    private array $booksRaw = [];
    private ?array $bookListRaw = null;

    public function __construct(
        private readonly string $dataPath,
        private readonly string $dataFilename = 'books.php',
    ) {

    }

    protected function load(string|int $id): ?Book
    {
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
            $this->booksRaw[$id] = $this->getFileInclude($this->dataPath . '/' . $bookPath);
        }

        $raw = $this->booksRaw[$id];

        return new Book(
            $raw['id'],
            $raw['abbreviation'],
            $raw['name'],
            $raw['name_long'],
            new ChaptersLoader($this->buildChaptersFromRaw($raw['chapters']))
        );
    }

    /**
     * @param array<int, array<int, string|<array<string>>> $raw
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
