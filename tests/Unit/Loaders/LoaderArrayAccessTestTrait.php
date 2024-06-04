<?php

namespace Tests\Unit\Loaders;

use Devdot\Bible\Text\Loaders\Loader;

/**
 * @mixin \Tests\UnitTestCase
 */
trait LoaderArrayAccessTestTrait
{
    abstract protected function buildLoader(): Loader;

    public function testArrayAccessExists(): void
    {
        $loader = $this->buildLoader();
        $this->assertFalse(isset($loader['']));
    }

    public function testArrayAccessGet(): void
    {
        $loader = $this->buildLoader();
        $this->assertNull($loader['']);
        $this->assertSame($loader->get(''), $loader['']);
        $this->assertSame($loader->get(1), $loader[1]);
        $this->assertSame($loader->get('GEN'), $loader['GEN']);
        $this->assertSame($loader->get('TES'), $loader['TES']);
    }

    public function testArrayAccessSetFails(): void
    {
        $loader = $this->buildLoader();
        $this->expectException(\Exception::class);
        $loader['test'] = 'something';
    }

    public function testArrayAccessUnsetFails(): void
    {
        $loader = $this->buildLoader();
        $this->expectException(\Exception::class);
        unset($loader['test']);
    }
}
