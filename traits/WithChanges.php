<?php

namespace app\traits;

/**
 * @property string $changes
 */
trait WithChanges
{
    /**
     * @return bool
     */
    public function hasChanges(): bool
    {
        $changes = trim($this->changes);
        return mb_strlen($changes) > 0;
    }

    /**
     * @return array
     */
    public function getChanges(): array
    {
        if (!$this->hasChanges()) {
            return [];
        }
        return array_map(fn($line) => [
            'date' => $this->extractDate($line),
            'text' => $this->extractText($line)
        ], explode(PHP_EOL, trim($this->changes)));
    }

    private function extractDate(string $line): string
    {
        $pos = mb_strpos($line, ' ');
        if ($pos === false) {
            return $line;
        }
        return mb_substr($line, 0, $pos);
    }

    private function extractText(string $line): string
    {
        $pos = mb_strpos($line, ' ');
        if ($pos === false) {
            return $line;
        }
        return mb_substr($line, $pos + 1);
    }
}
