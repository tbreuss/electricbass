<?php

namespace app\feature\alphaTab\components;

use app\feature\alphaTab\models\AlphaDrums;
use app\feature\alphaTab\models\AlphaTab;

final class AlphaTabApi
{
    const INSTRUMENT_NONE = 'NONE';
    const INSTRUMENT_FOUR_STRING_BASS = 'FOUR_STRING_BASS';
    const OPTION_GROUP_NONE = 'NONE';
    const OPTION_GROUP_DEFAULT = 'DEFAULT';

    private string $uniqueId;

    /**
     * @param string[][]|null $options
     */
    public function __construct(
        private string $alphaTex,
        private string $optionsGroup,
        private ?array $options = null,
        private string $instrument = self::INSTRUMENT_NONE,
        private ?string $uid = null,
        private ?string $title = null,
        private ?string $subtitle = null,
        private ?int $timeSignatureNumerator = null,
        private ?int $timeSignatureDenominator = null,
        private ?int $barCount = null,
        private ?AlphaDrums $drums = null,
        private ?string $previewImagePath = null,
        private ?string $previewImageUrl = null,
        private ?string $previewImageAltText = null,
        private bool $debug = false,
    ) {
        $this->uniqueId = uniqid();
    }

    public static function fromModels(AlphaTab $tab, ?AlphaDrums $drums, bool $debug = false): AlphaTabApi
    {
        [$tsNumerator, $tsDenominator] = $tab->timeSignature();

        return new self(
            alphaTex: $tab->alpha_tex,
            optionsGroup: $tab->options_group,
            options: $tab->options,
            instrument: $tab->instrument,
            title: $tab->title,
            subtitle: $tab->subtitle,
            timeSignatureNumerator: $tsNumerator,
            timeSignatureDenominator: $tsDenominator,
            barCount: $tab->bar_count,
            drums: $drums,
            previewImagePath: null,
            previewImageUrl: null,
            previewImageAltText: null,
            debug: $debug,
        );
    }

    public function uid(): ?string
    {
        return $this->uid;
    }

    public function previewImage(): ?string
    {
        if ($this->uid === null || $this->previewImagePath === null || $this->previewImageUrl === null) {
            return null;
        }

        $imagePath = $this->previewImagePath . '/' . str_replace('/', '-', $this->uid) . '.png';
        $imageUrl = $this->previewImageUrl . '/' . str_replace('/', '-', $this->uid) . '.png';

        return is_file($imagePath) ? $imageUrl : null;
    }

    public function previewImageAltText(): string
    {
        return $this->previewImageAltText ?? '';
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
            self::INSTRUMENT_NONE => array_merge($titles, [
                '\bracketextendmode' => 'noBrackets',
                '\singleTrackTrackNamePolicy' => 'hidden',
                '\hideDynamics' => '', // always hide dynamics
            ]),
            self::INSTRUMENT_FOUR_STRING_BASS => array_merge($titles, [
                '\track' => '"Bass"',
                '\bracketextendmode' => 'noBrackets',
                '\singleTrackTrackNamePolicy' => 'hidden',
                '\hideDynamics' => '', // always hide dynamics
                '\clef' => 'bass',
                '\instrument' => '"Electric Bass Finger"',
                '\tuning' => 'G2 D2 A1 E1 { hide }',
            ]),
            default => [],
        };

        $options = [];
        foreach ($defaults as $k => $v) {
            if (!str_contains($this->alphaTex, $k)) {
                $options[] = $k . ' ' . $v . $nl;
            }
        }

        $notation = join('', $options) . ltrim($this->alphaTex);

        if ($this->drums) {
            $notation = $this->addDrumsNotation($notation, $this->drums);
        }

        return implode(PHP_EOL, array_filter(preg_split("/\r\n|\n|\r/", $notation))); // because of markdown issues
    }

    private function addDrumsNotation(string $notation, AlphaDrums $drums): string
    {
        [$drumsTsNumerator, $drumsTsDenominator] = $drums->timeSignature();

        $invalidTimeSignatures = $this->timeSignatureNumerator === null
            || $this->timeSignatureDenominator === null
            || $drumsTsNumerator === null
            || $drumsTsDenominator === null
            || $drumsTsNumerator <> $this->timeSignatureNumerator
            || $drumsTsDenominator <> $this->timeSignatureDenominator;

        if ($invalidTimeSignatures) {
            return $notation;
        }

        [$drumsConfig, $pattern] = explode(".\n", $drums->alpha_tex);

        $bars = array_map('trim', array_filter(explode('|', trim($pattern))));
        if ($bars === []) {
            return $notation;
        }

        $drumsPattern = [];

        $barIndex = 0;
        for ($i = 0; $i < $this->barCount; $i++) {
            if (!isset($bars[$barIndex])) {
                $barIndex = 0;
            }
            $drumsPattern[] = $bars[$barIndex];
            $barIndex++;
        }

        return join("\n", [
            trim($notation),
            "",
            trim($drumsConfig),
            ".",
            join(" |\n", $drumsPattern),
        ]);
    }

    public function instrument(): string
    {
        return $this->instrument;
    }

    public function arrayOptions(): array
    {
        if ($this->optionsGroup === self::OPTION_GROUP_NONE) {
            return [
                'tex' => true,
                'engine' => 'svg',
            ];
        }

        if ($this->optionsGroup === self::OPTION_GROUP_DEFAULT) {
            $display = array_merge([
                'justifyLastSystem' => true,
            ], $this->options['display'] ?? []);

            $player = array_merge([
                'scrollMode' => 'Off',
                'playTripletFeel' => true,
            ], $this->options['player'] ?? []);

            return array_merge([
                'tex' => true,
                'engine' => 'svg',
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

    public function title(): ?string
    {
        return $this->title;
    }
}
