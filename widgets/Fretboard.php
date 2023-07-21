<?php

namespace app\widgets;

use app\widgets\assets\FretboardAsset;
use yii\base\Widget;

final class Fretboard extends Widget
{
    public string $colors = 'default'; // default|diatonic
    public bool $showDots = true;
    public bool $showFretNumbers = true;
    public bool $showStringNames = true;
    public array $notes = [];
    public array $strings = [];
    public array $frets = [];

    public ?string $root = null;

    public function init(): void
    {
        $this->frets = range(0, 12);
        $this->strings = [
            ['note' => 'G', 'stringNumber' => 1],
            ['note' => 'D', 'stringNumber' => 2],
            ['note' => 'A', 'stringNumber' => 3],
            ['note' => 'E', 'stringNumber' => 4],
        ];
        FretboardAsset::register($this->getView());
    }

    public function run(): string
    {
        $notes = array_map(fn($note) => $this->stringNumberFromNote($note), $this->notes);

        if ($this->root === null && count($notes) > 0) {
            ['string' => $string, 'fret' => $fret] = $notes[0];
            $this->root = $fret . '/' . $string;
        }

        [$absFret, $absString] = explode('/', $this->root);
        $absRoot = $this->calcAbsoluteValue($absFret, $absString);

        foreach ($notes as &$note) {
            $abs = $this->calcAbsoluteValue($note['fret'], $note['string']);
            $chroma = ($abs - $absRoot) % 12;
            $note['abs'] = $abs;
            $note['chroma'] = $chroma < 0 ? 12+$chroma : $chroma;
        }

        return $this->render('fretboard', [
            'config' => [
                'colors' => $this->colors,
                'showDots' => $this->showDots,
                'showFretNumbers' => $this->showFretNumbers,
                'showStringNames' => $this->showStringNames,
            ],
            'notes' => $notes,
            'frets' => $this->frets,
            'strings' => $this->strings,
        ]);

        /*
        $im = new \Imagick();
        $im->readImageBlob($svg);

        $im->setImageFormat("png24");
        #$im->resizeImage(720, 445, \Imagick::FILTER_LANCZOS, 1);  // Optional, if you need to resize

        #$im->setImageFormat("jpeg");
        #$im->adaptiveResizeImage(720, 445); // Optional, if you need to resize

        #$im->writeImage('us-map.png'); // (or .jpg)
        #$im->clear();

        $png = "<img src='data:image/png;base64,".base64_encode($im->getImageBlob())."' />";

        return $svg . $png;
        */
    }

    private function stringNumberFromNote(array|string $note): array
    {        
        if (is_array($note)) {
            [
                'tab' => $tab,
                'label' => $label,
                'hint' => $hint,
            ] = array_merge([
                'tab' => null,
                'label' => null,
                'hint' => null,
            ], $note);
        } else {
            $noteParts = explode('-', $note);
            $tab = $noteParts[0] ?? null;
            $label = $noteParts[1] ?? null;
            $hint = $noteParts[2] ?? null;
        }

        if (!is_string($tab) || !str_contains($tab, '/')) {
            throw new \RuntimeException('Invalid fret with string');
        }

        [$fretNumber, $stringNumber] = explode('/', $tab);

        if (!is_numeric($fretNumber) || !is_numeric($stringNumber)) {
            throw new \RuntimeException('Fret or string is not numeric');
        }

        foreach ($this->strings as $string) {
            if ($string['stringNumber'] == $stringNumber) {
                return [
                    'string' => $string['stringNumber'], 
                    'fret' => $fretNumber, 
                    'label' => $label, 
                    'hint' => $hint, 
                    'chroma' => null
                ];
            }
        }

        /*
        $noteParts = explode('-', $note);
        [$fretNumber, $stringNumber] = explode('/', $noteParts[0]);
        $noteFunction = (string)($noteParts[1] ?? '');
        $fingering = (string)($noteParts[2] ?? '');
        $noteLabel = $noteFunction;
        $noteSign = '';

        $flatOrShartSign = substr($noteLabel, 0, 1);

        if (in_array($flatOrShartSign, ['d', 'b'])) {
            $noteSign = '&#9837;';
            $noteLabel = substr($noteLabel, 1);
        }

        if ($flatOrShartSign === 'a') {
            $noteSign = '&#9839;';
            $noteLabel = substr($noteLabel, 1);
        }

        if ($flatOrShartSign === 'm') {
            $noteLabel = $flatOrShartSign;
        }

        foreach ($this->strings as $string) {
            if ($string['stringNumber'] == $stringNumber) {
                return [$string['stringNumber'], $fretNumber, $noteFunction, $noteSign, $noteLabel, $fingering];
            }
        }
        */

        throw new \RuntimeException('Could not determine string number from note');
    }

    private function calcAbsoluteValue(int $fretNumber, int $stringNumber): int
    {
        return (-$stringNumber + count($this->strings)) * 5 + $fretNumber;
    }
}
