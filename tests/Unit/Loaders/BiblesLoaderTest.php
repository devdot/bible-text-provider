<?php

namespace Tests\Unit\Loaders;

use Devdot\Bible\Text\Entities\Bible;
use Devdot\Bible\Text\Exceptions\FileNotFoundException;
use Devdot\Bible\Text\Loaders\BiblesLoader;
use Devdot\Bible\Text\Loaders\BooksLoader;
use Devdot\Bible\Text\Loaders\LazyLoader;
use Devdot\Bible\Text\Loaders\Loader;
use Tests\UnitTestCase;

class BiblesLoaderTest extends UnitTestCase
{
    use LoaderArrayAccessTestTrait;
    use LoaderGetTestTrait;

    protected function buildLoader(): Loader
    {
        return $this->getBiblesLoader();
    }

    public function testIsLazyLoader(): void
    {
        $loader = new BiblesLoader('somewhere');
        $this->assertInstanceOf(LazyLoader::class, $loader);
    }

    public function testFailsOnMissingFile(): void
    {
        $loader = new BiblesLoader('somewhere');
        $this->expectException(FileNotFoundException::class);
        $loader->get('test');
    }

    public function testBiblesRawData(): void
    {
        $loader = $this->getBiblesLoader();
        $raw = $loader->getBiblesRawData();
        $this->assertEquals(require(self::PATH_BIBLES_TEST), $raw);

        $loaderEmpty = $this->getBiblesLoader(true);
        $raw = $loaderEmpty->getBiblesRawData();
        $this->assertEquals([], $raw);
    }

    public function testGetOnEmpty(): void
    {
        $loader = $this->getBiblesLoader(true);

        $this->assertNull($loader->get('asfd'));
        $this->assertNull($loader->get('TES'));
        $this->assertNull($loader->get('MO24'));
        $this->assertNull($loader->get('BB'));
    }

    public function testGet(): void
    {
        $loader = $this->getBiblesLoader();

        $this->assertFalse($loader->isLoaded('asfd'));
        $this->assertNull($loader->get('asfd'));
        $this->assertTrue($loader->isLoaded('asfd'));

        $this->assertFalse($loader->isLoaded('TES'));
        $this->assertNotNull($loader->get('TES'));
        $this->assertTrue($loader->isLoaded('TES'));

        $this->assertFalse($loader->isLoaded('MO24'));
        $this->assertNotNull($loader->get('MO24'));
        $this->assertTrue($loader->isLoaded('MO24'));

        $this->assertFalse($loader->isLoaded('ERR'));
        $this->assertNotNull($loader->get('ERR'));
        $this->assertTrue($loader->isLoaded('ERR'));

        $this->assertFalse($loader->isLoaded('BB'));
        $this->assertNull($loader->get('BB'));
        $this->assertTrue($loader->isLoaded('BB'));

        $testBible = $loader->get('MO24');
        $this->assertEquals('mo24', $testBible->id);
        $this->assertEquals('MO24', $testBible->abbreviation);
        $this->assertEquals('MORE24', $testBible->name);
        $this->assertEquals('Not a real bible', $testBible->description);
        $this->assertEquals('Thomas Kuschan', $testBible->copyright);
        $this->assertEquals('eng', $testBible->language);
        $this->assertEquals(new \DateTimeImmutable('2024-06-05 15:30'), $testBible->updated_at);
        $this->assertInstanceOf(BooksLoader::class, $testBible->books);
    }

    public function testGetDoesNotLoadBibleContents(): void
    {
        $loader = $this->getBiblesLoader();
        $bible = $loader->get('ERR');
        $this->assertInstanceOf(Bible::class, $bible);

        $this->expectException(FileNotFoundException::class);
        $bible->books->get('test'); // only attempts to load non-existing file now
    }
}
