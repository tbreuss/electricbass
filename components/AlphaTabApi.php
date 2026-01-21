<?php

namespace app\components;

class AlphaTabApi
{
    const INSTRUMENT_NONE = 'NONE';
    const INSTRUMENT_FOUR_STRING_BASS = 'FOUR_STRING_BASS';
    const OPTION_GROUP_NONE = 'NONE';
    const OPTION_GROUP_DEFAULT = 'DEFAULT';

    private string $uniqueId;

    public function __construct(
        private string $notation,
        private string $optionGroup,
        private ?array $options = null,
        private string $instrument = self::INSTRUMENT_NONE,
        private ?string $uid = null,
        private ?string $title = null,
        private ?string $subtitle = null,
        private bool $debug = false,
    ) {
        $this->uniqueId = uniqid();
    }

    public function uid(): ?string
    {
        return $this->uid;
    }

    public function notation(): string
    {
        $nl = "\n";

        $titles = [];
        if ($this->title) {
            $titles['\title'] = '"' . $this->title . '"';
        }
        if ($this->subtitle) {
            $titles['\subtitle'] = '"' . $this->subtitle . '"';
        }

        $defaults = match ($this->instrument) {
            self::INSTRUMENT_FOUR_STRING_BASS => array_merge($titles, [
                '\bracketextendmode' => 'noBrackets',
                '\hideDynamics' => '', // always hide dynamics
                '\clef' => 'bass',
                '\instrument' => '"Electric Bass Finger"',
                '\tuning' => 'G2 D2 A1 E1 { hide }',
            ]),
            default => [],
        };

        $options = [];
        foreach ($defaults as $k => $v) {
            if (!str_contains($this->notation, $k)) {
                $options[] = $k . ' ' . $v . $nl;
            }
        }

        return join('', $options) . ltrim($this->notation);
    }

    public function instrument(): string
    {
        return $this->instrument;
    }

    public function arrayOptions(): array
    {
        if ($this->optionGroup === self::OPTION_GROUP_NONE) {
            return [
                'tex' => true,
            ];
        }

        if ($this->optionGroup === self::OPTION_GROUP_DEFAULT) {
            $display = array_merge([
                'justifyLastSystem' => true,
            ], $this->options['display'] ?? []);

            $player = array_merge([
                'scrollMode' => 'Off',
            ], $this->options['player'] ?? []);

            return array_merge([
                'tex' => true,
                'padding' => [0, 0, 0, 0],
                'barsPerRow' => $this->options['barsPerRow'] ?? -1,
                'layoutMode' => $this->options['Page'] ?? 'Page',
                'display' => $display,
                'player' => $player,
            ], $this->options ?? []);
        }

        return [];
    }

    public function options(): string
    {
        return json_encode($this->arrayOptions(), $this->isDebug() ? JSON_PRETTY_PRINT : 0) ?: '';
    }

    public function isDebug(): bool
    {
        return $this->debug;
    }

    public function uniqueId(): string
    {
        return $this->uniqueId;
    }
}
