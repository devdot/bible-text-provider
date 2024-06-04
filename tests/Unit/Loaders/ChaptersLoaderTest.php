<?php

namespace Tests\Unit\Loaders;

use Devdot\Bible\Text\Entities\Chapter;
use Devdot\Bible\Text\Loaders\BooksLoader;
use Devdot\Bible\Text\Loaders\ChaptersLoader;
use Devdot\Bible\Text\Loaders\Loader;
use Devdot\Bible\Text\Loaders\StaticLoader;
use Devdot\Bible\Text\Loaders\VersesLoader;
use Tests\UnitTestCase;

class ChaptersLoaderTest extends UnitTestCase
{
    use LoaderArrayAccessTestTrait;
    use LoaderGetTestTrait;

    protected function buildLoader(): Loader
    {
        return $this->getBiblesLoader()['TES']->books['GEN']->chapters;
    }

    public function testStaticLoaderBehavior(): void
    {
        $loader = new ChaptersLoader([
            1 => new Chapter(1, new VersesLoader([])),
        ]);
        $this->assertInstanceOf(StaticLoader::class, $loader);

        $this->assertNull($loader->get(0));
        $this->assertInstanceOf(Chapter::class, $loader->get(1));
        $this->assertEquals(1, $loader->get(1)->number);
        $this->assertEquals(new VersesLoader([]), $loader->get(1)->verses);
        $this->assertNull($loader->get(12));
    }

    public function testGeneratedByBooksLoader(): void
    {
        $loader = (new BooksLoader(__DIR__ . '/../../data/tes'))->get('GEN')->chapters;
        $this->assertInstanceOf(StaticLoader::class, $loader);
        $this->assertInstanceOf(ChaptersLoader::class, $loader);
        $this->assertNotNull($loader->get(1));
        $this->assertNotNull($loader->get(2));
        $this->assertNull($loader->get(3));
    }
}
