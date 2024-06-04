<?php

namespace Tests;

use Devdot\Bible\Text\Loaders\BiblesLoader;
use PHPUnit\Framework\TestCase as BaseTestCase;

class UnitTestCase extends BaseTestCase
{
    protected const PATH_BIBLES_TEST = __DIR__ . '/data/test-bibles.php';
    protected const PATH_BIBLES_EMPTY = __DIR__ . '/data/empty-bibles.php';

    protected function getBiblesLoader(bool $empty = false): BiblesLoader
    {
        $path = $empty ? self::PATH_BIBLES_EMPTY : self::PATH_BIBLES_TEST;
        return new BiblesLoader(dirname($path), basename($path));
    }

    protected function assertPropertyIsReadonly(object $object, string $property, mixed $value = null): void
    {
        try {
            $object->$property = $value;
            $this->assertTrue(false, 'Property ' . $property . ' of ' . $object::class . ' is supposed to be readonly!');
        } catch(\Error $e) {
            $this->assertEquals('Cannot modify readonly property ' . $object::class . '::$' . $property, $e->getMessage());
        }

    }
}
