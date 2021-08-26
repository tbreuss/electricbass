<?php

namespace app\entities;

final class AtoZEntry
{
    private string $title;
    private string $url;
    private bool $isNew;

    public function __construct(string $title, string $url, bool $isNew)
    {
        $this->title = $title;
        $this->url = $url;
        $this->isNew = $isNew;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function isNew(): bool
    {
        return $this->isNew;
    }
}
