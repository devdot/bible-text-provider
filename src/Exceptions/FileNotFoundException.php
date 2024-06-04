<?php

namespace Devdot\Bible\Text\Exceptions;

use Exception;

class FileNotFoundException extends Exception
{
    public function __construct(string $filepath)
    {
        parent::__construct('File not found: ' . $filepath);
    }
}
