<?php

namespace Tests\Unit\Entities;

use Devdot\Bible\Text\Entities\Verse;
use Tests\UnitTestCase;

class VerseTest extends UnitTestCase
{
    private function buildVerse(array|string $segments, int $number = 1): Verse
    {
        if (is_array($segments)) {
            return new Verse($number, $segments);
        }
        return new Verse($number, [$number => $segments]);
    }

    public function testBuildVerseHelperMethod(): void
    {
        $this->assertEquals(new Verse(12, [12 => 'Test']), $this->buildVerse([12 => 'Test'], 12));
        $this->assertEquals(new Verse(12, [12 => 'Test']), $this->buildVerse('Test', 12));
        $this->assertEquals(new Verse(1, [1 => 'String']), $this->buildVerse('String'));
        $this->assertEquals(new Verse(11, [11 => 'String']), $this->buildVerse('String', 11));
        $this->assertEquals(new Verse(1, [1 => 'String']), $this->buildVerse([1 => 'String']));
        $this->assertEquals(new Verse(1, [1 => 'String']), $this->buildVerse([1 => 'String'], 1));
    }

    public function testImmutableProperties(): void
    {
        $verse = new Verse(1, []);
        $this->assertPropertyIsReadonly($verse, 'number', 12);
        $this->assertPropertyIsReadonly($verse, 'segments', []);
    }

    public function testGetTextWithDefaultParams(): void
    {
        $this->assertEquals('A simple normal verse', $this->buildVerse('A simple normal verse')->getText());
        $this->assertEquals('A simple normal verse', $this->buildVerse('A simple normal verse', 42)->getText());
        $this->assertEquals('A simple normal verse', $this->buildVerse([12 => 'A simple normal verse'], 12)->getText());
        $this->assertEquals('A simple normal verse', $this->buildVerse(['12' => 'A simple normal verse'], 12)->getText());

        $this->assertEquals('Verse a and b', $this->buildVerse(['a' => 'Verse a', 'b' => 'and b'])->getText());
        $this->assertEquals('Main', $this->buildVerse(['a' => 'Verse a', 'b' => 'and b', 1 => 'Main'])->getText());
        $this->assertEquals('Verse a and b Wrong number', $this->buildVerse(['a' => 'Verse a', 'b' => 'and b', 2 => 'Wrong number'])->getText());
        $this->assertEquals('Right number', $this->buildVerse(['a' => 'Verse a', 'b' => 'and b', 2 => 'Right number'], 2)->getText());
    }

    public function testGetWithExtra(): void
    {
        $this->assertEquals('A simple normal verse', $this->buildVerse('A simple normal verse')->getText(1));
        $this->assertEquals('', $this->buildVerse('A simple normal verse')->getText('a'));
        $this->assertEquals('A simple normal verse', $this->buildVerse('A simple normal verse', 42)->getText(42));
        $this->assertEquals('', $this->buildVerse('A simple normal verse', 42)->getText('a'));
        $this->assertEquals('A simple normal verse', $this->buildVerse([12 => 'A simple normal verse'], 12)->getText());
        $this->assertEquals('', $this->buildVerse([12 => 'A simple normal verse'], 12)->getText('b'));

        $this->assertEquals('Verse a and b', $this->buildVerse(['a' => 'Verse a', 'b' => 'and b'])->getText(1));
        $this->assertEquals('Verse a', $this->buildVerse(['a' => 'Verse a', 'b' => 'and b'])->getText('a'));
        $this->assertEquals('and b', $this->buildVerse(['a' => 'Verse a', 'b' => 'and b'])->getText('b'));
        $this->assertEquals('', $this->buildVerse(['a' => 'Verse a', 'b' => 'and b'])->getText('c'));

        $this->assertEquals('Main', $this->buildVerse(['a' => 'Verse a', 'b' => 'and b', 1 => 'Main'])->getText(1));
        $this->assertEquals('Verse a', $this->buildVerse(['a' => 'Verse a', 'b' => 'and b', 1 => 'Main'])->getText('a'));
        $this->assertEquals('and b', $this->buildVerse(['a' => 'Verse a', 'b' => 'and b', 1 => 'Main'])->getText('b'));
        $this->assertEquals('', $this->buildVerse(['a' => 'Verse a', 'b' => 'and b', 1 => 'Main'])->getText('c'));

        $this->assertEquals('Works', $this->buildVerse([42 => 'Works'], 1)->getText(42));
        $this->assertEquals('Works', $this->buildVerse([42 => 'Works'], 1)->getText());
        $this->assertEquals('Works', $this->buildVerse([42 => 'Works'], 1)->getText(1));
        $this->assertEquals('', $this->buildVerse([42 => 'Works'], 1)->getText(2));
    }

    public function testGetWithAll(): void
    {
        $this->assertEquals('A simple normal verse', $this->buildVerse('A simple normal verse')->getText());
        $this->assertEquals('A simple normal verse', $this->buildVerse('A simple normal verse')->getText(null, true));

        $this->assertEquals('Main', $this->buildVerse(['a' => 'Verse a', 'b' => 'and b', 1 => 'Main'])->getText());
        $this->assertEquals('Main', $this->buildVerse(['a' => 'Verse a', 'b' => 'and b', 1 => 'Main'])->getText(1));
        $this->assertEquals('Verse a and b Main', $this->buildVerse(['a' => 'Verse a', 'b' => 'and b', 1 => 'Main'])->getText(null, true));
        $this->assertEquals('and b', $this->buildVerse(['a' => 'Verse a', 'b' => 'and b', 1 => 'Main'])->getText('b', true));
    }
}
