<?php

namespace Tests\Unit\Entities;

use Devdot\Bible\Text\Entities\Chapter;
use Devdot\Bible\Text\Loaders\VersesLoader;
use Tests\UnitTestCase;

class ChapterTest extends UnitTestCase
{
    public function testImmutableProperties(): void
    {
        $chapter = new Chapter(1, new VersesLoader([]));
        $this->assertPropertyIsReadonly($chapter, 'number', 12);
        $this->assertPropertyIsReadonly($chapter, 'verses', new VersesLoader([]));
    }
}
