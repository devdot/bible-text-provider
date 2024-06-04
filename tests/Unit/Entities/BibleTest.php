<?php

namespace Tests\Unit\Entities;

use Devdot\Bible\Text\Entities\Bible;
use Devdot\Bible\Text\Loaders\BooksLoader;
use Tests\UnitTestCase;

class BibleTest extends UnitTestCase
{
    public function testImmutableProperties(): void
    {
        $bible = new Bible('id', 'abbr', 'name', 'desc', 'copy', 'lang', new \DateTimeImmutable(), new BooksLoader('somewhere'));
        $this->assertPropertyIsReadonly($bible, 'id', 'something');
        $this->assertPropertyIsReadonly($bible, 'abbreviation', 'something');
        $this->assertPropertyIsReadonly($bible, 'name', 'something');
        $this->assertPropertyIsReadonly($bible, 'description', 'something');
        $this->assertPropertyIsReadonly($bible, 'copyright', 'something');
        $this->assertPropertyIsReadonly($bible, 'language', 'something');
        $this->assertPropertyIsReadonly($bible, 'updated_at', new \DateTimeImmutable());
        $this->assertPropertyIsReadonly($bible, 'books', new BooksLoader('somewhere'));
    }
}
