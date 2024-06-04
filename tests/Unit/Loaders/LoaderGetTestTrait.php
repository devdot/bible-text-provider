<?php

namespace Tests\Unit\Loaders;

use Devdot\Bible\Text\Loaders\Loader;

/**
 * @mixin \Tests\UnitTestCase
 */
trait LoaderGetTestTrait
{
    abstract protected function buildLoader(): Loader;

    public function testGetReturnsIdenticalObjectOnConsecutiveCalls(): void
    {
        $loader = $this->buildLoader();
        // Make sure at least one of those is not null
        $foundNotNull = false;
        $keys = [1, 'MO24', 'GEN'];
        foreach($keys as $key) {
            $obj = $loader->get($key);
            if($obj !== null) {
                $foundNotNull = true;
                $this->assertSame($obj, $loader->get($key));
                $this->assertSame($loader->get($key), $loader->get($key));
            }
        }

        $this->assertTrue($foundNotNull);
    }
}
