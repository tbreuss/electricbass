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
            $foundNotes[] = [
                "fingering" => $fretString,
                "note" => $notePerFretString
            ];
        }
    }

    return $foundNotes;
}

function notes(Tuning $tuning, int $fretTo = FRET_TO): array
{
    $notes = [];
    foreach ($tuning->strings as $stringIndex => $string) {
        $stringNumber = $stringIndex + 1;
        [$note] = $string;
        $midi = get($note)->midi;
        foreach (range(0, $fretTo) as $fret) {
            $key = $fret . '/' . $stringNumber;
            $notes[$key] = [];
            $noteName = midiToNoteName($midi + $fret);
            $notes[$key][] = get($noteName)->pc;
            $notes[$key][] = $noteName;
            $enharmonic = enharmonic($noteName);
            if ($enharmonic <> $noteName) {
                $notes[$key][] = get($enharmonic)->pc;
                $notes[$key][] = $enharmonic;
            }
        }
    }
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
