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
     *  Note: this will only work with verses withing a single chapter!
     *  @return array<int, Verse>
     */
    public function getVersesFromReference(BibleVerseInterface $reference, ?string $bibleId = null): array
    {
        $bible = $bibleId ? $this->getBible($bibleId) : $this->getDefaultBible();
        $verses = [];

        $bookId = BookIdResolver::TO_BOOK_ID[$reference->getFromBookId()] ?? '';
        $chapter = $reference->getFromChapter();
        for ($verse = $reference->getFromVerse(); $verse <= $reference->getToVerse(); $verse++) {
            $result = $bible->books[$bookId]?->chapters[$chapter]?->verses[$verse] ?? null;
            if ($result) {
                $verses[] = $result;
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
