<?php

namespace tebe\tonal\fretboard;

use Exception;
use function tebe\tonal\note\get;
use function tebe\tonal\midi\midiToNoteName;
use function tebe\tonal\note\enharmonic;

const FRET_FROM = 0;
const FRET_TO = 24;

function getNote(Tuning $tuning, string $fingering): array
{
    [$fret, $string] = explode('/', $fingering);

    if ($fret < FRET_FROM || $fret > FRET_TO) {
        return [];
    }

    $stringIndex = $string - 1;
    
    if (!isset($tuning->strings[$stringIndex])) {
        return [];
    }

    $emptyFretNote = $tuning->strings[$stringIndex][0];
    $midi = get($emptyFretNote)->midi + $fret;
    
    $note = midiToNoteName($midi);    
    $enharmonic = enharmonic($note);

    return $note === $enharmonic ? [$note] : [$note, $enharmonic];
}

function findNotes(Tuning $tuning, array $notes, ?int $fretFrom = null, ?int $fretTo = null, ?int $stringFrom = null, ?int $stringTo = null): array
{
    $stringCount = count($tuning->strings);
    $stringFrom = is_null($stringFrom) ? 1 : bound(abs($stringFrom), 1, $stringCount);
    $stringTo = is_null($stringTo) ? count($tuning->strings) : bound(abs($stringTo), 1, $stringCount);
    $fretFrom = is_null($fretFrom) ? 0 : bound(abs($fretFrom), 0, FRET_TO);
    $fretTo = is_null($fretTo) ? FRET_TO : bound(abs($fretTo), 0, FRET_TO);

    if ($stringFrom > $stringTo || $fretFrom > $fretTo) {
        return [];
    }

    $labels = array_map(fn($note) => is_array($note) ? $note[1] : $note, $notes);
    $notes = array_map(fn($note) => is_array($note) ? $note[0] : $note, $notes);

    $foundNotes = [];

    foreach (notes($tuning) as $fretString => $notesPerFretString) {
        [$fret, $string] = explode('/', $fretString);
        foreach ($notesPerFretString as $notePerFretString) {
            if ($fret < $fretFrom || $fret > $fretTo) {
                continue;
            }
            if ($string < $stringFrom || $string > $stringTo) {
                continue;
            }
            if (!in_array($notePerFretString, $notes)) {
                continue;
            }
            $foundKey = array_search($notePerFretString, $notes);
            if ($foundKey === false) {
                continue;
            }
            $foundNotes[] = [
                "coord" => $fretString,
                "note" => $notes[$foundKey],
                "label" => $labels[$foundKey],
            ];
        }
    }

    return $foundNotes;
}

function findLowestNote(Tuning $tuning, string $note): string
{
    foreach (notes($tuning) as $fretString => $notesPerFretString) {
        if (in_array($note, $notesPerFretString)) {
            return $fretString;
        }
    }
    return '';
}

function midi(Tuning $tuning, int $fretTo = FRET_TO): array
{
    $notes = [];
    foreach ($tuning->strings as $stringIndex => [$note]) {
        $stringNumber = $stringIndex + 1;
        $midi = get($note)->midi;
        foreach (range(0, $fretTo) as $fret) {
            $notes[$fret . '/' . $stringNumber] = $midi + $fret;
        }
    }
    return $notes;
}

function notes(Tuning $tuning, int $fretTo = FRET_TO): array
{
    $notes = [];    
    $stringCount = count($tuning->strings);
    foreach (array_reverse($tuning->strings) as $stringIndex => $string) {
        $stringNumber = $stringCount - $stringIndex;
        [$note] = $string;
        $midi = get($note)->midi;
        foreach (range(0, $fretTo) as $fret) {
            $key = $fret . '/' . $stringNumber;
            $notes[$key] = [];
            $noteName = midiToNoteName($midi + $fret);
            $noteClass = get($noteName);
            $notes[$key][] = $noteClass->pc;
            $notes[$key][] = $noteName;
            $enharmonic = enharmonic($noteName);
            if ($enharmonic <> $noteName) {
                $notes[$key][] = get($enharmonic)->pc;
                $notes[$key][] = $enharmonic;
            }
            $specials = [['B', 'Cb', 1], ['C', 'B#', -1], ['E', 'Fb', 0], ['F', 'E#', 0]];
            foreach ($specials as [$sLetter, $sFix, $sOct]) {
                if ($noteClass->letter === $sLetter) {
                    $notes[$key][] = $sFix;
                    $notes[$key][] = $sFix . ($noteClass->oct + $sOct);
                }
            }
        }
    }
    return $notes;
}

function bound(int $x, int $min, int $max): int
{
     return min(max($x, $min), $max);
}

function tuning(string $name, array $strings): Tuning
{
    return new Tuning($name, $strings);
}

class Tuning 
{
    public function __construct(
        public string $tuning,
        public array $strings
    ) {}
}
