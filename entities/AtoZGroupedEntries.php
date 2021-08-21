<?php

namespace app\entities;

class AtoZGroupedEntries
{
    private string $initial;

    /** @var AtoZEntry[] */
    private array $entries;

    public function __construct(string $initial, array $entries)
    {
        $this->initial = $initial;
        $this->entries = $entries;
    }

    /**
     * @return string
     */
    public function getInitial(): string
    {
        return $this->initial;
    }

    /**
     * @return AtoZEntry[]
     */
    public function getEntries(): array
    {
        return $this->entries;
    }

}
