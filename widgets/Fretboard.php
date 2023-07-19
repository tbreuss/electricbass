<?php

namespace app\widgets;

use app\widgets\assets\FretboardAsset;
use yii\base\Widget;

final class Fretboard extends Widget
{
    public $colors = 'default'; // default|intervals
    public $showDots = true;
    public $showFretNumbers = true;
    public $showStringNames = true;
    public $notes = [];

    public function init(): void
    {
        FretboardAsset::register($this->getView());
    }

    public function run(): string
    {
        return $this->render('fretboard', [
            'config' => [
                'colors' => $this->colors,
                'showDots' => $this->showDots,
                'showFretNumbers' => $this->showFretNumbers,
                'showStringNames' => $this->showStringNames,
            ],
            'notes' => $this->notes,
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

    public static function stringNumberFromNote(string $note, array $strings): array
    {
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

        foreach ($strings as $string) {
            if ($string['stringNumber'] == $stringNumber) {
                return [$string['stringNumber'], $fretNumber, $noteFunction, $noteSign, $noteLabel, $fingering];
            }
        }

        throw new \RuntimeException('Could not determine string number from note');
    }
}
