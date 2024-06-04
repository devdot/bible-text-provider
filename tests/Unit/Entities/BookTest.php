<?php

namespace Tests\Unit\Entities;

use Devdot\Bible\Text\Entities\Book;
use Devdot\Bible\Text\Loaders\ChaptersLoader;
use Tests\UnitTestCase;

class BookTest extends UnitTestCase
{
    public function testImmutableProperties(): void
    {
        $book = new Book('something', 'something', 'something', 'something', new ChaptersLoader([]));
        $this->assertPropertyIsReadonly($book, 'id', 'something');
        $this->assertPropertyIsReadonly($book, 'abbreviation', 'something');
        $this->assertPropertyIsReadonly($book, 'name', 'something');
        $this->assertPropertyIsReadonly($book, 'name_long', 'something');
        $this->assertPropertyIsReadonly($book, 'chapters', new ChaptersLoader([]));
    }
}
