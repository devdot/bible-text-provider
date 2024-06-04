<?php

namespace Devdot\Bible\Text;

use Devdot\Bible\Text\Entities\Bible;
use Devdot\Bible\Text\Entities\Verse;
use Devdot\Bible\Text\Helper\BookIdResolver;
use Devdot\Bible\Text\Loaders\BiblesLoader;
use StevenBuehner\BibleVerseBundle\Interfaces\BibleVerseInterface;

class BibleTextProvider
{
    public function __construct(
        private readonly BiblesLoader $loader,
        private ?string $defaultBibleId = null,
    ) {
    }

    public function getBible(string $id): ?Bible
    {
        return $this->loader->get($id);
    }

    /**
     * @return array<int, Bible>
     */
    public function getAvailableBibles(): array
    {
        return $this->loader->all();
    }

    /**
     * @return array<int, Verse>
     */
    public function getVersesForReference(BibleVerseInterface $reference, ?string $bibleId = null): array
    {
        $verses = [];
        $bible = $bibleId ? $this->getBible($bibleId) : $this->getDefaultBible();
        // todo: this wont work well for references spanning multiple chapters or books
        for ($book = $reference->getFromBookId(); $book <= $reference->getToBookId(); $book++) {
            $bookId = BookIdResolver::TO_BOOK_ID[$book] ?? '';
            for ($chapter = $reference->getFromChapter(); $chapter <= $reference->getToChapter(); $chapter++) {
                for ($verse = $reference->getFromVerse(); $verse <= $reference->getToVerse(); $verse++) {
                    $result = $bible->books[$bookId]?->chapters[$chapter]?->verses[$verse] ?? null;
                    if ($result) {
                        $verses[] = $result;
                    }
                }
            }
        }
        return $verses;
    }

    public function getDefaultBible(): ?Bible
    {
        if ($this->defaultBibleId === null) {
            $bible = $this->getAvailableBibles()[0] ?? null;
            $this->defaultBibleId = $bible?->abbreviation ?? null;
            return $bible;
        }

        return $this->getBible($this->defaultBibleId);
    }
}
