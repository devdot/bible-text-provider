<?php

namespace Tests\Unit\Loaders;

use Devdot\Bible\Text\Entities\Verse;
use Devdot\Bible\Text\Loaders\BooksLoader;
use Devdot\Bible\Text\Loaders\Loader;
use Devdot\Bible\Text\Loaders\StaticLoader;
use Devdot\Bible\Text\Loaders\VersesLoader;
use Tests\UnitTestCase;

class VersesLoaderTest extends UnitTestCase
{
    use LoaderArrayAccessTestTrait;
    use LoaderGetTestTrait;

    protected function buildLoader(): Loader
    {
        return $this->getBiblesLoader()['TES']->books['GEN']->chapters[1]->verses;
    }

    public function testStaticLoaderBehavior(): void
    {
        $loader = new VersesLoader([
            1 => new Verse(1, [1 => 'here we go'])
        ]);
        $this->assertInstanceOf(StaticLoader::class, $loader);

        $this->assertNull($loader->get(0));
        $this->assertInstanceOf(Verse::class, $loader->get(1));
        $this->assertEquals(1, $loader->get(1)->number);
        $this->assertEquals('here we go', $loader->get(1)->getText());
        $this->assertNull($loader->get(12));
    }

    public function testGeneratedByBooksLoader(): void
    {
        $loader = (new BooksLoader(__DIR__ . '/../../data/tes'))->get('GEN')->chapters->get(2)->verses;
        $this->assertInstanceOf(StaticLoader::class, $loader);
        $this->assertInstanceOf(VersesLoader::class, $loader);
        $this->assertNotNull($loader->get(1));
        $this->assertEquals('Verse a Verse b', $loader->get(1)->getText());
        $this->assertEquals('Verse a', $loader->get(1)->getText('a'));
        $this->assertNotNull($loader->get(2));
        $this->assertEquals('Actual verse 2', $loader->get(2)->getText());
        $this->assertNotNull($loader->get(3));
        $this->assertEquals('Normal verse', $loader->get(3)->getText());
        $this->assertNull($loader->get(4));
    }
}
