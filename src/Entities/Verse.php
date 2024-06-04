<?php

namespace Devdot\Bible\Text\Entities;

class Verse extends Entity
{
    /**
     * @param array<string> $segments
     */
    public function __construct(
        public readonly int $number,
        public readonly array $segments,
    ) {
    }

    public function getText(?string $extra = null, bool $all = false): string
    {
        if ($extra && $extra != $this->number) {
            return $this->segments[$extra] ?? ($all ? $this->getText(null, true) : '');
        } elseif ($all) {
            return implode(' ', $this->segments);
        } else {
            return $this->segments[$this->number] ?? $this->getText(null, true);
        }
    }
}
