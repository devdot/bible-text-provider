<?php

namespace Devdot\Bible\Text;

use Devdot\Bible\Text\Entities\Bible;
use Devdot\Bible\Text\Loaders\BiblesLoader;

class BibleTextProvider
{
    public function __construct(
        private readonly BiblesLoader $loader,
    ) {
    }

    public function getBible(string $id): ?Bible
    {
        return $this->loader->get($id);
    }

    /**
     * @return array<int, Bible>
     */
    public function getAvailableBibles(): array
    {
        return $this->loader->all();
    }
}
