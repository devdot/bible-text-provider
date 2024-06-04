<?php

namespace Tests\Unit;

use Devdot\Bible\Text\BibleTextProvider;
use Devdot\Bible\Text\Entities\Bible;
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
}
