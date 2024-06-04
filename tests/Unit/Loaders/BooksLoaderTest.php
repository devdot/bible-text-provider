<?php

namespace Tests\Unit\Loaders;

use Devdot\Bible\Text\Exceptions\FileNotFoundException;
use Devdot\Bible\Text\Loaders\BooksLoader;
use Devdot\Bible\Text\Loaders\ChaptersLoader;
use Devdot\Bible\Text\Loaders\LazyLoader;
use Devdot\Bible\Text\Loaders\Loader;
use Tests\UnitTestCase;

class BooksLoaderTest extends UnitTestCase
{
    use LoaderArrayAccessTestTrait;
    use LoaderGetTestTrait;

    protected function buildLoader(): Loader
    {
        return $this->getBiblesLoader()->get('TES')->books;
    }

    public function testIsLazyLoader(): void
    {
        $loader = new BooksLoader('somewhere');
        $this->assertInstanceOf(LazyLoader::class, $loader);
    }

    public function testEmptyBookLoader(): void
    {
        $loader = new BooksLoader(__DIR__ . '/../../data/ety');
        $this->assertNull($loader->get('GEN'));
    }

    public function testFailsWhenAttemptingToLoadMissingBooksList(): void
    {
        $loader = new BooksLoader('somewhere');
        $this->expectException(FileNotFoundException::class);
        $loader->get('GEN');
    }

    public function testSuccessfulLoad(): void
    {
        $loader = new BooksLoader(__DIR__ . '/../../data/tes');
        $this->assertFalse($loader->isLoaded('GEN'));
        $genesis = $loader->get('GEN');
        $this->assertTrue($loader->isLoaded('GEN'));

        $this->assertNotNull($genesis);
        $this->assertEquals('GEN', $genesis->id);
        $this->assertEquals('Gen.', $genesis->abbreviation);
        $this->assertEquals('Genesis', $genesis->name);
        $this->assertEquals('Genesis', $genesis->name_long);
        $this->assertInstanceOf(ChaptersLoader::class, $genesis->chapters);

        $this->assertNull($loader->get('does not exist'));
    }
}
