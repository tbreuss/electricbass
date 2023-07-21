<?php

namespace tebe\tonal\fretboard;

use tebe\tonal\core\note\Note;

use function tebe\tonal\note\get;
use function tebe\tonal\midi\midiToNoteName;
use function tebe\tonal\note\enharmonic;

const STRING_FROM = 0;
const STRING_TO = 4;
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

function findNotes(Tuning $tuning, array $notes, array $frets = [FRET_FROM, FRET_TO], array $strings = [STRING_FROM, STRING_TO]): array
{
    [$fretFrom, $fretTo] = $frets;
    [$stringFrom, $stringTo] = $strings;

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
                "fingering" => $fretString,
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
            /*
            if ($noteClass->letter === 'B') {
                $notes[$key][] = 'Cb';
                $notes[$key][] = 'Cb' . $noteClass->oct + 1;
            }             
            if ($noteClass->letter === 'C') {
                $notes[$key][] = 'B#';
                $notes[$key][] = 'B#' . $noteClass->oct - 1;
            }            
            if ($noteClass->letter === 'E') {
                $notes[$key][] = 'Fb';
                $notes[$key][] = 'Fb' . $noteClass->oct;
            }            
            if ($noteClass->letter === 'F') {
                $notes[$key][] = 'E#';
                $notes[$key][] = 'E#' . $noteClass->oct;
            }
            */
        }
    }
    #echo "<pre>";print_r($notes);echo"</pre>";
    return $notes;
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
