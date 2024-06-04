<?php

namespace Tests\Unit;

use Devdot\Bible\Text\BibleTextProvider;
use Devdot\Bible\Text\Entities\Bible;
use StevenBuehner\BibleVerseBundle\Service\BibleVerseService;
use Tests\UnitTestCase;

class BibleTextProviderTest extends UnitTestCase
{
    public function testGetBible(): void
    {
        $provider = new BibleTextProvider($this->getBiblesLoader());

        $this->assertInstanceOf(Bible::class, $provider->getBible('TES'));
        $this->assertNull($provider->getBible('not there'));
    }

    public function testGetAvailableBibles(): void
    {
        $provider = new BibleTextProvider($this->getBiblesLoader());
        $available = $provider->getAvailableBibles();

        $this->assertCount(4, $available);
        $this->assertSame('Testum Biblicum', $available[0]->name);
        $this->assertSame('MORE24', $available[1]->name);
        $this->assertSame('Empty Bible', $available[2]->name);
        $this->assertSame('Error Bible', $available[3]->name);

        // test for empty bible
        $provider = new BibleTextProvider($this->getBiblesLoader(true));
        $this->assertEmpty($provider->getAvailableBibles());
    }

    public function testGetVersesForReference(): void
    {
        $provider = new BibleTextProvider($this->getBiblesLoader());
        $service = new BibleVerseService();
        $reference = $service->stringToBibleVerse('Gen 1:1')[0] ?? null;
        $this->assertNotNull($reference);

        $this->assertSame('In the Beginning there was void.', $provider->getVersesForReference($reference)[0]->getText());

        $reference = $service->stringToBibleVerse('Gen 1:1-2')[0] ?? null;
        $verses = $provider->getVersesForReference($reference);
        $this->assertCount(2, $verses);
        $this->assertSame(1, $verses[0]->number);
        $this->assertSame(2, $verses[1]->number);
        $this->assertSame('In the Beginning there was void.', $verses[0]->getText());
        $this->assertSame('Then a computer was generated.', $verses[1]->getText());
    }
}
